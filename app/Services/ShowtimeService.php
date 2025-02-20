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
use App\Models\Type_seat;

class ShowtimeService
{
    public function getService(){
        $movies = Movie::query()->where('is_active',1)->get();
        return [$movies];
       
    }
    public function createService(string $id)
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
        $rooms = Room::with('type_room')->where('is_active',1)->get();
        $movie = Movie::query()->findOrFail($id);
        $days = Day::query()->get();
        $slug = Showtime::generateCustomRandomString();
        $specialshowtimes = Showtime::SPECIALSHOWTIMES;
        $type_rooms = Type_room::query()->get();
        $type_seats = Type_seat::query()->get();

        return [$branchs, $branchsRelation, $rooms, $movie,$days,$slug,$roomsRelation,$specialshowtimes,$type_seats,$type_rooms];
    }
    public function storeService(array $data) {
        
        foreach ($data['start_time'] as $key => $start_time) {
            $showtimeData = array_merge($data, [
                'price_special' => !empty($data['price_special']) ? str_replace('.', '', $data['price_special']) : 0,
                'start_time' => $start_time,
                'end_time'   => $data['end_time'][$key] ?? null,
            ]);
            Showtime::query()->create($showtimeData);
        }
        return true;
    }
    public function deleteService(string $id) {
        $showtime = Showtime::query()->findOrFail($id);
        $showtime->delete();
        return true;
    }
}
