<?php

namespace App\Services;

use App\Models\Day;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DayService
{
    public function getAllDays()
    {
        return Day::latest()->paginate(5);
    }

    public function getDayById($id)
    {
        return Day::findOrFail($id);
    }

    public function createDay($data)
    {
        return DB::transaction(function () use ($data) {
            return Day::create($data);
        });
    }

    public function updateDay($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $day = Day::findOrFail($id);
            $day->update($data);
            return $day;
        });
    }

    public function deleteDay($id)
    {
        $day = Day::findOrFail($id);

        $day->delete();
        return true;
    }
}