<?php

namespace App\Repositories\Modules;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getPaginateUserRepository($perPage = 10, string $latest = 'id')
    {
        return $this->model->where('type_user', 1)->with('cinema')->latest($latest)->paginate($perPage);
    }

    public function findByIdUserRepository($id)
    {
        try {
            return $this->find($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public function createUserRepository(array $data)
    {
        return $this->create($data);
    }
}
