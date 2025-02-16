<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat_template;
use App\Models\Showtime;
use App\Models\Type_room;

class ShowtimeService
{
    public function createService()
    {
        $branchs = Branch::with('cinemas.rooms')->where('is_active', 1)->get();
        $branchsRelation = [];
        foreach ($branchs as $branch) {
            $branchsRelation[$branch['id']] = $branch->cinemas->where('is_active', 1)->pluck('name', 'id')->all();
        }
        $roomsRelation = [];
        foreach ($branchs as $branch) {
            foreach ($branch->cinemas as $cinema) {
                $roomsRelation[$cinema['id']] = $cinema->rooms->where('is_active', 1)->pluck('name', 'id')->all();
            }
        }
        $rooms = Room::query()->where('is_active',1)->get();
        $movies = Movie::query()->where('is_active',1)->get();
        $days = Day::query()->pluck('name', 'id')->all();
        $slug = Showtime::generateCustomRandomString();
        return [$branchs, $branchsRelation, $rooms, $movies,$days,$slug,$roomsRelation];
    }
}
