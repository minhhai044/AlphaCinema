<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Modules\CinemaRepository;

class CinemaService
{
    protected $cinemaRepository;
    public function __construct(CinemaRepository $cinemaRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
    }
    public function getAllPaginateService($perPage = 10, string $latest = 'id')
    {
        return $this->cinemaRepository->getPaginateCinemaRepository($perPage, $latest);
    }
    public function storeService($data)
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] ??= 0;
            $data['slug'] = Str::slug($data['name'], '-') . '-' . Str::ulid();

            return $this->cinemaRepository->createCinemaRepository($data);
        });
    }
    public function updateSevice($cinema, $data)
    {
        return DB::transaction(function () use ($cinema, $data) {
            $data['is_active'] ??= 0;
            $data['slug'] = Str::slug($data['name'], '-') . '-' . Str::ulid();

            return $cinema->update($data);
        });
    }

    public function deleteSevice($id)
    {
        $findCinema = $this->cinemaRepository->findByIdCinemaRepository($id);
        if (Storage::exists($findCinema['image'])) {
            Storage::delete($findCinema['image']);
        }
        $findCinema->delete();
        return true;
    }
}
