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
    use ApiResponseTrait, ApiRequestJsonTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
   
    public function signIn(UserRequest $userRequest)
    {
        try {
            // Validate dữ liệu đầu vào
            $data = $userRequest->validated();

            // Lấy user theo email
            $user = $this->userService->getUserApi($data['email']);

            // Kiểm tra nếu user không tồn tại
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email không tồn tại'
                ], Response::HTTP_NOT_FOUND);
            }

            // Kiểm tra mật khẩu (So sánh password hash)
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mật khẩu không đúng'
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Tạo token xác thực
            $token = $user->createToken('UserToken')->plainTextToken;

            // Tạo cookie chứa token (thời gian sống 24h - 1440 phút)
            $cookie = cookie('user_token', $token, 1440);

            // Trả về phản hồi JSON thành công
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'type_user' => $user->type_user
                    ],
                    'token' => $token,
                    'cookie' => $cookie
                ]
            ], Response::HTTP_OK)->withCookie($cookie);
        } catch (\Throwable $th) {
            // Ghi log chi tiết lỗi
            Log::error('Lỗi đăng nhập: ' . $th->getMessage(), [
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ]);

            // Trả về lỗi JSON
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function signUp(UserRequest $UserRequest)
    {
        try {
            $data = $UserRequest->validated();
            $data['type_user'] = 0;

            $user = $this->userService->storeUser($data);

            $token = $user->createToken('UserToken')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'token' => $token
            ], 'success', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Đăng xuất thành công');
    }
}
