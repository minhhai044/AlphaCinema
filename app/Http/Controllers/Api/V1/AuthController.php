<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Filters\Filter;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Models\Rank;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiRequestJsonTrait;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function signIn(LoginRequest $request)
    {
        try {
            // Validate dữ liệu đầu vào
            $data = $request->validated();

            // Lấy user theo email
            $user = $this->userService->getUserApi($data['email']);

            // Kiểm tra nếu user không tồn tại
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email không tồn tại'
                ], Response::HTTP_NOT_FOUND);
            }

            // Kiểm tra mật khẩu (So sánh password hash)
            if (!Hash::check($data['password'], $user->password)) {
                return $this->errorResponse('Thông tin tài khoản không chính xác', Response::HTTP_UNAUTHORIZED);
            }

            // Tạo token xác thực
            $token = $user->createToken('UserToken')->plainTextToken;

            // Tạo cookie chứa token (thời gian sống 24h - 1440 phút)
            $cookie = cookie('user_token', $token, 1440);

            return $this->successResponse([
                'user' => $user,
                'token' => $token,
                'cookie' => $cookie
            ], 'Đăng nhập thành công', Response::HTTP_OK)->withCookie($cookie);
        } catch (\Throwable $th) {
            // Ghi log chi tiết lỗi
            Log::error('Lỗi đăng nhập: ' . $th->getMessage(), [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ]);

            // Trả về lỗi JSON

            $this->errorResponse(
                'Đã xảy ra lỗi, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function signUp(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['type_user'] = 0;

            $user = $this->userService->storeUser($data);

            $token = $user->createToken('UserToken')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'Đăng ký thành công', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        // Kiểm tra nếu người dùng đã xác thực
        if ($request->user()) {
            // Xóa token truy cập hiện tại
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Người dùng chưa đăng nhập'
        ], 401)
            ->withCookie(cookie()->forget('admin_token'))
            ->withCookie(cookie()->forget('auth'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate request data
            $data = $request->validate([
                'name'    => 'nullable|string|max:255',
                'phone'   => 'nullable|string|max:15|regex:/^[0-9]+$/',
                'gender'  => 'nullable|in:0,1',
                'address' => 'nullable|string|max:255',
                'avatar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Update user info
            $user = $this->userService->updateInfo($data, $user);

            // Return success response
            return $this->successResponse([
                'message' => "Update thành công",
                'data' => $user
            ]);
        } catch (Exception $e) {
            // Handle any exceptions
            Log($e->getMessage());
            return $this->errorResponse([
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'error'   => $e->getTraceAsString()
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $changePasswordRequest)
    {
        try {
            $user = Auth::user();  // Get the current authenticated user

            // Get validated data from the request
            $data = $changePasswordRequest->validated();

            // Check if the old password matches the current password
            if (!Hash::check($data['password_old'], $user->password)) {
                return $this->errorResponse([
                    'message' => 'Mật khẩu cũ không chính xác.'
                ]);
            }

            // Remove the 'password_old' field from the data array
            unset($data['password_old']);

            // Proceed with updating the password
            $user = $this->userService->changePassword($data, $user);

            return $this->successResponse([
                'message' => "Cập nhật mật khẩu thành công",
                'data' => $user
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse([
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'error'   => $e->getTraceAsString()
            ]);
        }
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallBack()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->id],
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Str::password(12),
                'avatar' => $googleUser->avatar,
                'type_user' => 0,
            ]
        );

        // Auth::login($user);

        $token = $user->createToken('authToken')->plainTextToken;

        $authData = json_encode([
            'user' => $user->only(['id', 'name', 'email', 'avatar']),
            'token' => $token,
            'isLogin' => true
        ]);

        $authDataEncoded = base64_encode(json_encode($authData));

        return redirect()->to('https://alphacinema.me:3000/auth/callback?data=' . urlencode($authDataEncoded));
    }

    public function getUserRank()
    {
        try {
            $user_total_amount = Auth::user()->total_amount;

            $rank = Rank::where("total_spent", "<=", $user_total_amount)
                ->orderBy("total_spent", 'desc')
                ->first();

            if (!$rank) {
                $rank = Rank::where("is_default", true)->first();
            }

            // Trả về kết quả
            return $this->successResponse([
                "rank" => $rank
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse([
                "message" => "Đã xảy ra lỗi: " . $e->getMessage(),
                "error" => $e->getTraceAsString()
            ]);
        }
    }
}
