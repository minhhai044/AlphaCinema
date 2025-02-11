<?php

namespace App\Services;

use App\Repositories\Modules\CinemaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            if ($data['image']) {
                $data['image'] = Storage::put('cinemaImages', $data['image']);
            }
            $Cinema = $this->cinemaRepository->createCinemaRepository($data);
            return $Cinema;
        });
    }
    public function updateSevice($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $findCinema = $this->cinemaRepository->findByIdCinemaRepository($id);

            if ($data['image'] && Storage::exists($findCinema['image'])) {
                Storage::delete($findCinema['image']);
            }
            if ($data['image']) {
                $data['image'] = Storage::put('cinemaImages', $data['image']);
            }
            $Cinema =  $findCinema->update($data);
            return $Cinema;
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
