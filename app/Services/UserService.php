<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Modules\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;
    protected $user;

    public function __construct(UserRepository $userRepository, User $user)
    {
        $this->userRepository = $userRepository;
        $this->user = $user;
    }
    public function getAllPaginateUsers($perPage = 10, string $latest = 'id')
    {
        return $this->userRepository->getPaginateUserRepository($perPage, $latest);
    }

    public function storeUser($data)
    {
        try {
            return DB::transaction(function () use ($data) {
                // Kiểm tra nếu có ảnh thumbnail thì lưu trữ vào thư mục
                if (isset($data['avatar'])) {
                    $data['avatar'] = Storage::put('avatar', $data['avatar']);
                }

                $user = $this->userRepository->createUserRepository($data);
                return $user;
            });
        } catch (\Throwable $th) {
            Log::error('Error storing user: ' . $th->getMessage(), [
                'exception' => $th,
                'data' => $data
            ]);

            throw new \Exception('An error occurred while saving the user.');
        }
    }

    public function updateUser($id, $data)
    {
        try {
            $user = $this->userRepository->findByIdUserRepository($id);

            $data['type_user'] = $data['type_user'] ?? 0;

            $data['password'] = empty($data['password']) ? $user->password : $data['password'];

            if (!empty($data['avatar']) && Storage::exists($user['avatar'])) {
                Storage::delete($user['avatar']);
            }
            if (!empty($data['avatar'])) {
                $data['avatar'] = Storage::put('avatar', $data['avatar']);
            }

            $user->update($data);

            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function getUserApi($email)
    {
        try {
            // Tìm user theo email
            $user = User::where('email', $email)->first();
            return $user;
            // Kiểm tra nếu user không tồn tại
        } catch (\Exception $e) {
            // Ghi log lỗi chi tiết
            Log::error('Lỗi lấy thông tin user: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Trả về lỗi JSON
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi, vui lòng thử lại'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public  function updateInfo($data, $user)
    {
        try {

            // $data['type_user'] = $data['type_user'] ?? 0;

            // $data['password'] = empty($data['password']) ? $user->password : $data['password'];

            if (!empty($data['avatar']) && Storage::exists($user['avatar'])) {
                Storage::delete($user['avatar']);
            }
            if (!empty($data['avatar'])) {
                $data['avatar'] = Storage::put('avatar', $data['avatar']);
            }

            $user->update($data);

            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public  function changePassword($data, $user)
    {
        try {
            $user->update($data);
            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
