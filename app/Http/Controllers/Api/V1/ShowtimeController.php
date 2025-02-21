<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use App\Traits\ApiResponseTrait;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShowtimeController extends Controller
{
    use ApiResponseTrait;
    public function listMovies()
    {
        $movies = Movie::query()->latest('id')->get();
        return $this->successResponse(
            $movies,
            'Thao tác thành công'
        );
    }
    public function listShowtimes(string $movie)
    {
        $showtimes = Showtime::query()
            ->where('movie_id', $movie)
            ->get();
        $data = [];
        foreach ($showtimes as $showtime) {
            $data[$showtime['date']][] = [
                'id' => $showtime['id'],
                'start_time' => $showtime['start_time'],
                'slug' => $showtime['slug'],
            ];
        }

        return $this->successResponse(
            $data,
            'Thao tác thành công'
        );
    }
    public function showtimeDetail(string $slug)
    {
        try {
            $showtime = Showtime::with('branch', 'movie', 'cinema', 'room')
                ->where('slug', $slug)
                ->first();
            $seatMap = json_decode($showtime['seat_structure'], true);
            $result = [];
            foreach ($seatMap as $seat) {
                $coordinates_y = $seat['coordinates_y'] ?? null;
                $coordinates_x = $seat['coordinates_x'] ?? null;

                if ($coordinates_y === null || $coordinates_x === null) {
                    continue;
                }

                if (!isset($result[$coordinates_y])) {
                    $result[$coordinates_y] = [];
                }

                $result[$coordinates_y][$coordinates_x] = $seat;
            }
            return $this->successResponse(
                $result,
                'Thao tác thành công'
            );
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return $this->errorResponse(
                $th->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
