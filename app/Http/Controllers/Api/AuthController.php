<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllPaginateUsers(10, 'id');
            $roles = Role::all();

            return $this->successResponse([
                'users' => $users,
                'roles' => $roles
            ], 'Thao tác thành công', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function signIn(UserRequest $userRequest)
    {
        // $user =  $this->userService->($id);
        try {
            $data = $userRequest->validated();
            dd($data);

            $response = $this->userService->getUserApi($data->email, $data->password);
            return $response;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return $this->errorResponse('Đã xảy ra lỗi, vui lòng thử lại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
