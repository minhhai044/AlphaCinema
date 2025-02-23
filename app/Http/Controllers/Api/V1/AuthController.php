<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use App\Traits\ApiRequestJsonTrait;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait, ApiRequestJsonTrait;

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::query()->where('email', $credentials['email'])->first();

        if (!$user) {
            return $this->errorResponse(
                'Tài khoản không tồn tại',
                Response::HTTP_NOT_FOUND
            );
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return $this->errorResponse('Thông tin tài khoản chưa chính xác', Response::HTTP_NOT_FOUND);
        }

        $token = $user->createToken('luxchill')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token
        ], 'Đăng nhập thành công');
    }

    public function register(RegisterRequest $request)
    {

        $user = User::create($request->validated());

        $token = $user->createToken('luxchill')->plainTextToken;

        return $this->successResponse(
            [
                'user' => $user,
                'token' => $token
            ],
            'Tạo tài khoản thành công',
            Response::HTTP_CREATED
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Đăng xuất thành công');
    }
}
