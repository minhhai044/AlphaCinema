<?php

namespace App\Repositories\Modules;

use App\Models\Cinema;
use App\Repositories\BaseRepository;

class CinemaRepository extends BaseRepository
{
    public function __construct(Cinema $cinema)
    {
        parent::__construct($cinema);
    }

    public function getPaginateCinemaRepository($perPage = 10, string $latest = 'id')
    {
        return $this->model->latest($latest)->paginate($perPage);
    }

    public function findByIdCinemaRepository($id)
    {
        return $this->find($id);
    }

    public function createCinemaRepository(array $data)
    {
        return $this->create($data);

    }
}