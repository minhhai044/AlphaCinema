<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\RealTimeSeatEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShowtimeRequest;
use App\Models\Movie;
use App\Models\Showtime;
use App\Services\ShowtimeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ShowtimeController extends Controller
{
    use ApiResponseTrait;
    private $showtimeService;
    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }

    public function getByDate(Request $request, string $id)
    {
        $date = $request->query('date');
        $showtime = Showtime::with('room.type_room')->where([
            ['date', $date],
            ['room_id', $id]
        ])->get();
        return $this->successResponse(
            $showtime,
            'Thao tác thành công !!!'
        );
    }
    public function activeShowtime(Request $request, string $id)
    {
        try {
            $data = $this->showtimeService->updateService($id, $request->all());
            return $this->successResponse(
                $data,
                'Thao tác thành công !!!'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage()
            );
        }
    }
    public function listMovies()
    {
        $movies = Movie::query()->latest('id')->get();
        return $this->successResponse(
            $movies,
            'Thao tác thành công'
        );
    }


    public function movieShowTimes(string $slug)
    {
        $movie = Movie::with('showtime')->where('slug', $slug)->first();

        $data = [];

        $showtimes = [];

        foreach ($movie->showtime as $showtime) {
            $showtimes[$showtime['date']][] = [
                'id' => $showtime['id'],
                'start_time' => $showtime['start_time'],
                'slug' => $showtime['slug'],
            ];
        }

        $data = [
            'showtimes' => $showtimes, // Lịch chiếu theo ngày
            'movie' => $movie, // Thông tin phim
        ];

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
                [
                    'seatMap' => $result,
                    'showTime' => $showtime
                ],
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
    public function changeSeatStatus(ShowtimeRequest $showtimeRequest, string $id)
    {
        try {
            $showtime = Showtime::query()->findOrFail($id);
            $seat_structures = json_decode($showtime->seat_structure, true);

            if (!empty($seat_structures)) {
                foreach ($seat_structures as &$seat_structure) {
                    if ($seat_structure['id'] == $showtimeRequest->seat_id) {
                        $seat_structure['user_id'] = $showtimeRequest->user_id;
                        $seat_structure['status'] = $showtimeRequest->status;
                        break;
                    }
                }
            }

            $showtime->update([
                'seat_structure' => json_encode($seat_structures),
            ]);
            broadcast(new RealTimeSeatEvent($seat_structure['id'], $seat_structure['status'], $seat_structure['user_id']))->toOthers();
            // if ($seat_structure['status'] === 'hold') {
            //     broadcast(new RealTimeSeatEvent($seat_structure['id'], $seat_structure['status']))->toOthers();
            // }
            return $this->successResponse(
                $seat_structures,
                'Thao tác thành công'
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Thao tác không thành công',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
