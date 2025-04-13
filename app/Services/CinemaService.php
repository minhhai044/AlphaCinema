<?php

namespace App\Services;

use App\Models\Cinema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Modules\CinemaRepository;
use Illuminate\Support\Facades\Auth;

class CinemaService
{
    protected $cinemaRepository;
    public function __construct(CinemaRepository $cinemaRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
    }
    public function getAllPaginateService($perPage = 10, string $latest = 'id')
    {
        $query = Cinema::query()->latest($latest);

        if (Auth::user()->branch_id) {
            $query->where('branch_id', Auth::user()->branch_id);
        }
        // if (Auth::user()->cinema_id) {
        //     $query->where('id', Auth::user()->cinema_id);
        // }
        return $query->get();
    }
    public function storeService($data)
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] ??= 1;
            $data['slug'] = Str::slug($data['name'], '-') . '-' . Str::ulid();

            return $this->cinemaRepository->createCinemaRepository($data);
        });
    }
    public function updateSevice($cinema, $data)
    {
        return DB::transaction(function () use ($cinema, $data) {
            $data['is_active'] ??= 1;
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
