<?php

namespace App\Services;

use App\Events\RealTimeSeatEvent;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Day;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Seat_template;
use App\Models\Showtime;
use App\Models\Type_room;
use App\Models\Type_seat;
use Carbon\Carbon;

class ShowtimeService
{
    public function getService($request)
    {

        $date = $request->query('date', '');
        $branch_id = $request->query('branch_id', '');
        $cinema_id = $request->query('cinema_id', '');
        $listShowtimesByDates = collect();
        if (empty($date) && !empty($branch_id) && !empty($cinema_id)) {
            $showtimes = Showtime::with('movie', 'room.type_room', 'branch', 'cinema')
                ->where('date', '>=', Carbon::now()->toDateString())
                ->where('branch_id', $branch_id)
                ->where('cinema_id', $cinema_id)
                ->get();

            $listShowtimesByDates = $showtimes->groupBy('date')->map(function ($showtimesByDate) {
                return [
                    'movies' => $showtimesByDate->groupBy('movie_id')->map(function ($showtimes) {
                        return [
                            'movie' => $showtimes->first()->movie,
                            'showtimes' => $showtimes->map(function ($showtime) {
                                return $showtime;
                            })->values(),
                        ];
                    })->values(),
                ];
            });
        }
        if (empty($date) || empty($branch_id) || empty($cinema_id)) {
            $showtimes = collect();
        } else {
            $showtimes = Showtime::with('movie', 'room.type_room', 'branch', 'cinema')
                ->where('date', $date)
                ->where('branch_id', $branch_id)
                ->where('cinema_id', $cinema_id)
                ->get();
        }



        $listShowtimes = $showtimes->groupBy('movie_id')->map(function ($showtimes) {
            return [
                'movie' => $showtimes->first()->movie,
                'showtimes' => $showtimes->toArray(),
            ];
        });

        $branchs = Branch::with('cinemas.rooms')->where('is_active', 1)->get();
        $branchsRelation = [];
        foreach ($branchs as $branch) {
            $branchsRelation[$branch['id']] = $branch->cinemas->where('is_active', 1)->pluck('name', 'id')->all();
        }

        $movies = Movie::query()->where('is_active', 1)->get();

        return [$branchs, $branchsRelation, $listShowtimes, $movies, $listShowtimesByDates];
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
        $rooms = Room::with('type_room')->where('is_active', 1)->get();
        $movie = Movie::query()->findOrFail($id);
        $days = Day::query()->get();
        $slug = Showtime::generateCustomRandomString();
        $specialshowtimes = Showtime::SPECIALSHOWTIMES;
        $type_rooms = Type_room::query()->get();
        $type_seats = Type_seat::query()->get();

        return [$branchs, $branchsRelation, $rooms, $movie, $days, $slug, $roomsRelation, $specialshowtimes, $type_seats, $type_rooms];
    }
    public function storeService(array $data)
    {
        foreach ($data['start_time'] as $key => $start_time) {
            $showtimeData = array_merge($data, [
                'price_special' => !empty($data['price_special']) ? str_replace('.', '', $data['price_special']) : 0,
                'start_time' => $start_time,
                'end_time'   => $data['end_time'][$key] ?? null,
                'slug'       => Showtime::generateCustomRandomString()
            ]);
            Showtime::query()->create($showtimeData);
        }
        return true;
    }
    public function updateService(string $id, array $data)
    {
        $showtime = Showtime::query()->findOrFail($id);
        return $showtime->update($data);
    }
    public function deleteService(string $id)
    {
        $showtime = Showtime::query()->findOrFail($id);
        $showtime->delete();
        return true;
    }
    public function resetSuccessService(array $data, string $id)
    {
        $showtime = Showtime::query()->where('id', $id)->lockForUpdate()->firstOrFail();

        $seat_structures = json_decode($showtime->seat_structure, true);
        $seatIds = $data['seat_id'];

        /** code new */
        $updated_seats = array_map(function ($seat) use ($data, $seatIds) {
            if (isset($seat['id']) && in_array($seat['id'], $seatIds)) {
                $seat['status'] = $data['status'] ?? $seat['status'];
                $seat['user_id'] = $data['user_id'] ?? $seat['user_id'];

                broadcast(new RealTimeSeatEvent($seat['id'], $seat['status'], $seat['user_id']))->toOthers();
            }
            return $seat;
        }, $seat_structures);



        $showtime->update([
            'seat_structure' => json_encode($updated_seats),
        ]);

        return $showtime;
    }
}
