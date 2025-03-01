<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiRequestJsonTrait;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
                'token' => $token
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
        ], 401);
    }
}
