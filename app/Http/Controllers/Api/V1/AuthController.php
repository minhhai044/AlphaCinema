<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Filters\Filter;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\ConfirmVerifyEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Mail\SendOtpMail;
use App\Models\Point_history;
use App\Models\Rank;
use App\Models\User;
use App\Models\User_voucher;
use App\Models\Vat;
use App\Services\UserService;
use App\Traits\ApiRequestJsonTrait;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
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

            // $this->sendMailOtp($data['email'], $data['name']);

            $vat = Vat::first();


            // Lấy user theo email
            $user = $this->userService->getUserApi($data['email']);

            // Kiểm tra nếu user không tồn tại
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'email' => 'Email không tồn tại'
                ], Response::HTTP_NOT_FOUND);
            }

            // Kiểm tra mật khẩu (So sánh password hash)
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'password' => 'Mật khẩu không chính xác.'
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($user->is_active == false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản đã bị khóa',
                    'code' => 401
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($user->type_user == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tài khoản không phải là tài khoản khách hàng',
                    'code' => 401
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Tạo token xác thực
            $token = $user->createToken('UserToken')->plainTextToken;

            // Tạo cookie chứa token (thời gian sống 24h - 1440 phút)
            // $cookie = cookie('user_token', $token, 1440);

            return $this->successResponse([
                'user' => $user,
                'token' => $token,
                // 'cookie' => $cookie,
                'vat' => $vat
            ], 'Đăng nhập thành công', Response::HTTP_OK);
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

    // public function signUp(RegisterRequest $request)
    // {
    //     try {
    //         $data = $request->validated();
    //         $vat = Vat::first();
    //         $data['type_user'] = 0;

    //         $user = $this->userService->storeUser($data);

    //         $token = $user->createToken('UserToken')->plainTextToken;

    //         return $this->successResponse([
    //             'user' => $user,
    //             'token' => $token,
    //             'vat' => $vat
    //         ], 'Đăng ký thành công', Response::HTTP_CREATED);
    //     } catch (\Throwable $th) {
    //         Log::error($th->getMessage());
    //         return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function register($data)
    {
        try {
            $vat = Vat::first();
            $data['type_user'] = 0;

            $user = $this->userService->storeUser($data);

            $token = $user->createToken('UserToken')->plainTextToken;

            return $data = [
                'user' => $user,
                'token' => $token,
                'vat' => $vat
            ];
            // return $this->successResponse([
            //     'user' => $user,
            //     'token' => $token,
            //     'vat' => $vat
            // ], 'Đăng ký thành công', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkUserResgister(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            if (!empty($data)) {
                $this->sendMailOtp($data['email'], $data['name']);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function formatAvatar($avatar)
    {
        if (Str::startsWith($avatar, 'avatar')) {
            return Storage::exists($avatar)
                ? Storage::url($avatar)
                : asset('https://i.sstatic.net/l60Hf.png');
        }

        return $avatar;
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

    public function updateProfile(UpdateProfileRequest $request, string $id)
    {
        // UpdateProfileRequest
        try {
            $data = $request->validated();

            $user = User::findOrFail($id);

            if (!empty($data['avatar'])) {
                if ($user->avatar && Storage::exists($user->avatar)) {
                    Storage::delete($user->avatar);
                }
                $data['avatar'] = Storage::put('avatar', $data['avatar']);
            }

            $user->update($data);

            $user->avatar = $this->formatAvatar($user->avatar);
            // $user = $this->userService->updateInfo($request->validated(), $id);

            return $this->successResponse([
                'user' => $user
            ], 'Update thành công');
        } catch (Exception $e) {
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

            // return response()->json([
            //     'user' => $user
            // ]);

            // Get validated data from the request
            $data = $changePasswordRequest->validated();

            // Check if the old password matches the current password
            if (!Hash::check($data['password_old'], $user->password)) {
                // return $this->errorResponse([
                //     'password_old' => 'Mật khẩu cũ không chính xác.'
                // ]);

                return response()->json([
                    'errors' => [
                        'password_old' => 'Mật khẩu cũ không chính xác.'
                    ],
                    'status' => false
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $updateData = [];

        $vat = Vat::first();

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'google_id' => $googleUser->id,
                'name' => $googleUser->name,
                'password' => Hash::make(Str::password(12)),
                'avatar' => $googleUser->avatar,
                'type_user' => 0,
                'email_verified_at' => now(),
            ]
        );

        if (!$user->google_id) {
            $updateData['google_id'] = $googleUser->id;
        }

        if (!$user->avatar) {
            $updateData['avatar'] = $googleUser->avatar;
        }

        if (!$user->email_verified_at) {
            $updateData['email_verified_at'] = now();
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // if (!$user->google_id) {
        //     $user->update(['google_id' => $googleUser->id, 'avatar' => $googleUser->avatar]);
        // }

        // if (!$user->email_verified_at) {
        //     $user->update(['email_verified_at' => now()]);
        // }

        // Auth::login($user);

        $user->avatar = $this->formatAvatar($user->avatar);

        $token = $user->createToken('authToken')->plainTextToken;

        $authData = json_encode([
            'user'      => $user,
            'token'     => $token,
            'isLogin'   => true,
            'vat'       => $vat
        ]);

        $authDataEncoded = base64_encode(json_encode($authData));

        $pathRedirect = env('APP_URL_FE');

        return redirect()->to("{$pathRedirect}/auth/callback?data=" . urlencode($authDataEncoded));
    }

    public function getUserRank()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
            }

            $totalTransaction = $user->total_amount;
            $point = $user->point;
            $created_at = $user->created_at;

            // Lấy rank hiện tại
            $currentRank = Rank::where("total_spent", "<=", $totalTransaction)
                ->orderBy("total_spent", "desc")
                ->first();

            if (!$currentRank) {
                $currentRank = Rank::where("is_default", true)->first();
            }

            // Lấy rank tiếp theo (rank cao hơn)
            $allRanks = Rank::query()
                ->orderBy("total_spent", "asc")
                ->get();

            return response()->json([
                "status" => "success",
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "total_amount" => $totalTransaction,
                    "point" => $point, // point hiện có
                    "created_at" => $created_at
                ],
                "rank" => $currentRank,
                "next_rank" => $allRanks
            ]);
        } catch (\Exception $e) {

            return response()->json([
                "status" => "error",
                "message" => "Đã xảy ra lỗi: " . $e->getMessage(),
            ], 500);
        }
    }
    public function getUserVoucher()
    {
        try {
            // Lấy ID người dùng đang đăng nhập
            $userId = Auth::id();

            // Kiểm tra người dùng đã đăng nhập chưa
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Lấy danh sách voucher của người dùng
            $vouchers = User_voucher::where('user_id', $userId)->where('is_active', 1)->with('voucher')->get();

            return response()->json([
                'success' => true,
                'vouchers' => $vouchers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserPointHistory(Request $request)
    {
        try {
            // Lấy thông tin user đã đăng nhập
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
            $point = $user->point;
            // Lấy lịch sử điểm của người dùng
            $history = Point_history::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "point" => $point,
                ],
                "history-point" => $history,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy lịch sử điểm',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Gửi mail kèm mã otp dựa vào email người dùng nhập vào
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendOtp(SendOtpRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse('Email không tồn tại', Response::HTTP_NOT_FOUND);
            }

            $otp = rand(100000, 999999);
            // $expiresAt = Carbon::now()->addMinutes(2);

            Redis::setex("otp_{$request->email}", 300, Hash::make($otp));

            Mail::to($request->email)->queue(new SendOtpMail($otp, $user->name));

            return $this->successResponse([], 'Vui lòng kiểm tra email của bạn', Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function verifyEmail(VerifyEmailRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse('Email không tồn tại', Response::HTTP_NOT_FOUND);
            }

            if (!empty($user->email_verified_at)) {
                return $this->successResponse([], 'Email đã được xác minh trước đó', Response::HTTP_OK);
            }

            $otp = rand(100000, 999999);
            // $expiresAt = Carbon::now()->addMinutes(2);

            // Redis::setex("otp_{$request->email}", 300, Hash::make($otp));
            Redis::setex("verify-email-{$request->email}", 300, Hash::make($otp));

            Mail::to($request->email)->queue(new SendOtpMail($otp, $user->name));

            return $this->successResponse([], 'Vui lòng kiểm tra email của bạn', Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function confirmVerifyEmail(ConfirmVerifyEmailRequest $request)
    {
        try {
            // $user = User::where('email', $request->email)->first();

            // if (!$user) {
            //     return $this->errorResponse('Email không tồn tại', Response::HTTP_NOT_FOUND);
            // }

            $redisKey = "verify-email-{$request->email}";
            $hashedOtp = Redis::get($redisKey);

            if (!$hashedOtp) {
                return $this->errorResponse('OTP đã hết hạn hoặc không tồn tại', Response::HTTP_BAD_REQUEST);
            }

            if (!Hash::check($request->otp, $hashedOtp)) {
                return $this->errorResponse('Mã OTP không chính xác', Response::HTTP_UNAUTHORIZED);
            }

            // $user->email_verified_at = now();
            // $user->save();

            $data = $this->register($request->except('otp'));

            // Cập nhật thông tin người dùng và xác minh email
            $user = $data['user'];
            $user->email_verified_at = now();
            $user->save();

            // Trả về thông tin người dùng đã xác minh
            return $this->successResponse(['user' => $user, 'token' => $data['token'], 'vat' => $data['vat']], 'Xác minh email thành công', Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Kiểm tra otp người dùng nhập vào có đúng không
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        try {
            $otpRedis = Redis::get("otp_{$request->email}");

            if (!$otpRedis) {
                Redis::del("otp_{$request->email}");
                return $this->errorResponse('OTP không tồn tại hoặc đã hết hạn', Response::HTTP_BAD_REQUEST);
            }

            if (!Hash::check($request->otp, $otpRedis)) {
                return $this->errorResponse('OTP không đúng', Response::HTTP_BAD_REQUEST);
            }

            return $this->successResponse([
                'otp' => $otpRedis,
                'verify_otp' => true
            ], 'OTP hợp lệ, bạn có thể nhập mật khẩu mới', Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Thay đổi password dựa vào mã otp và email người dùng yêu cầu reset
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $otpRedis = Redis::get("otp_{$request->email}");

            if (!$otpRedis) {
                return $this->errorResponse('OTP không tồn tại hoặc đã hết hạn', Response::HTTP_BAD_REQUEST);
            }

            if (!Hash::check($request->otp, $otpRedis)) {
                return $this->errorResponse('OTP không đúng', Response::HTTP_BAD_REQUEST);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse('Email không tồn tại', Response::HTTP_NOT_FOUND);
            }

            $user->update(['password' => Hash::make($request->password)]);

            Redis::del("otp_{$request->email}");

            return $this->successResponse($user, 'Thay đổi mật khẩu thành công', Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendMailOtp($email, $name)
    {
        try {
            $otp = rand(100000, 999999);
            // $expiresAt = Carbon::now()->addMinutes(2);

            Redis::setex("verify-email-{$email}", 300, Hash::make($otp));

            Mail::to($email)->queue(new SendOtpMail($otp, $name));

            return $this->successResponse([], 'Vui lòng kiểm tra email của bạn', Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
