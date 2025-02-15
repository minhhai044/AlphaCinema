<?php

namespace App\Services;

use App\Models\Branch;
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
        $branchs = Branch::with('cinemas')->where('is_active', 1)->get();
        $branchsRelation = [];
        foreach ($branchs as $branch) {
            $branchsRelation[$branch['id']] = $branch->cinemas->pluck('name', 'id');
        }
        $rooms = Room::query()->where('is_active',1)->get();
        $movies = Movie::query()->where('is_active',1)->get();
        $days = Day::query()->pluck('name', 'id')->all();
        $slug = Showtime::generateCustomRandomString();
        $versions = Showtime::VERSIONS;
        return [$branchs, $branchsRelation, $rooms, $movies,$days,$slug,$versions];
    }
}
