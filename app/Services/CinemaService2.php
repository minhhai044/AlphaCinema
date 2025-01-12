<?php

namespace App\Services;

use App\Models\Cinema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CinemaService2
{
    public function getAllPaginateService($perPage = 10, string $latest = 'id')
    {
        return Cinema::query()->latest($latest)->paginate($perPage);
    }
    public function storeService($data)
    {
        return DB::transaction(function () use ($data) {
            if ($data['image']) {
                $data['image'] = Storage::put('cinemaImages', $data['image']);
            }
            $Cinema = Cinema::create($data);
            return $Cinema;
        });
    }
    public function updateSevice($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $findCinema = Cinema::findOrFail($id);

            if ($data['image'] && Storage::exists($findCinema['image'])) {
                Storage::delete($findCinema['image']);
            }
            if ($data['image']) {
                $data['image'] = Storage::put('cinemaImages', $data['image']);
            }
            $findCinema->update($data);
            return $findCinema;
        });
    }
    public function deleteSevice($id)
    {
        $findCinema = Cinema::findOrFail($id);
        if (Storage::exists($findCinema['image'])) {
            Storage::delete($findCinema['image']);
        }
        $findCinema->delete();
        return true;
    }
}
