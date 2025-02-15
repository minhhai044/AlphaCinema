<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Modules\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

            return  $user->update($data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
