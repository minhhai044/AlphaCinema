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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

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
        // $movies = Movie::whereHas('showtime', function ($query) use ($branch, $cinema) {
        //     $query->where('date', '>=', Carbon::now()->toDateString());
        //     $query->where('branch_id', $branch);
        //     $query->where('cinema_id', $cinema);
        // })
        //     ->with('showtime')
        //     ->latest('id')
        //     ->get();

        // return $this->successResponse(
        //     $movies,
        //     'Thao tác thành công'
        // );

        $movies = Movie::query()->latest('id')->get();
        return $this->successResponse(
            $movies,
            'Thao tác thành công'
        );
    }


    public function movieShowTimes(string $slug)
    {
        $movie = Movie::with([
            'showtime' => function ($query) {
                $query->where('date', '>=', Carbon::now());
            },
            'room'
        ])->where('slug', $slug)->first();

        $data = [];

        $showtimes = [];

        foreach ($movie->showtime as $showtime) {
            $showtimes[$showtime['date']][] = [
                'id' => $showtime['id'],
                'start_time' => $showtime['start_time'],
                'slug' => $showtime['slug'],
                'name_room' => $showtime['room']['name']
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

            // $seatMap = json_decode($showtime['seat_structure'], true);
            $seatMap = $showtime['seat_structure'];


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
                    'seatMapRegular' => $seatMap,
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

    /*** */

    /**
     * Code ban đầu
     */

    // public function changeSeatStatus(ShowtimeRequest $showtimeRequest, string $id)
    // {
    //     try {
    //         // Khóa bản ghi showtime để tránh race condition
    //         $showtime = Showtime::query()->where('id', $id)->firstOrFail();

    //         // $seat_structures = json_decode($showtime->seat_structure, true);
    //         $seat_structures = $showtime->seat_structure;

    //         if (empty($seat_structures)) {
    //             return response()->json(['error' => 'Không tìm thấy danh sách ghế'], 400);
    //         }
    //         // Minh Hải Check 1 user chỉ được 10 ghế
    //         $checkTotalSeatUser = count(array_keys(array_column($seat_structures, 'user_id'), $showtimeRequest->user_id));

    //         if ($checkTotalSeatUser >= 10 && $showtimeRequest->status === 'hold') {
    //             return response()->json(['error' => 'Bạn chỉ có thể chọn tối đa 10 ghế !!!'], 409);
    //         }

    //         // Kiểm tra trạng thái ghế trước khi cập nhật
    //         foreach ($seat_structures as &$seat_structure) {
    //             if ($seat_structure['id'] == $showtimeRequest->seat_id) {
    //                 // Nếu ghế đã được đặt hoặc giữ chỗ, từ chối cập nhật
    //                 if ($seat_structure['status'] !== 'available' && $seat_structure['status'] !== 'hold') {
    //                     return response()->json(['error' => 'Ghế này đã được đặt hoặc không khả dụng'], 409);
    //                 }

    //                 // Cập nhật trạng thái ghế
    //                 $seat_structure['user_id'] = $showtimeRequest->user_id;
    //                 $seat_structure['status'] = $showtimeRequest->status;
    //                 break;
    //             }
    //         }

    //         $showtime->update([
    //             'seat_structure' => $seat_structures,
    //         ]);

    //         $seatId = $showtimeRequest->seat_id;
    //         $status = $showtimeRequest->status;
    //         $userId = $showtimeRequest->user_id;

    //         // broadcast(new RealTimeSeatEvent($seatId, $status, $userId))->toOthers();
    //         broadcast(new RealTimeSeatEvent($seatId, $status, $userId));

    //         return response()->json([
    //             'message' => 'Thao tác thành công',
    //             'data' => $seat_structures,
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         Log::error($th->getMessage());
    //         return response()->json([
    //             'error' => 'Thao tác không thành công',
    //             'message' => $th->getMessage()
    //         ], 500);
    //     }
    // }

    /** 
     * Code queue redis
     */

    public function changeSeatStatus(ShowtimeRequest $showtimeRequest, string $id)
    {
        try {
            $start = microtime(true);
            Log::info('Start API');

            $showtime = Showtime::query()->where('id', $id)->firstOrFail();
            Log::info('After Query: ' . (microtime(true) - $start) . ' seconds');

            $seat_structures = $showtime->seat_structure;
            Log::info('After Get Seats: ' . (microtime(true) - $start) . ' seconds');

            if (empty($seat_structures)) {
                return response()->json(['error' => 'Không tìm thấy danh sách ghế'], 400);
            }

            $checkTotalSeatUser = count(array_keys(array_column($seat_structures, 'user_id'), $showtimeRequest->user_id));

            if ($checkTotalSeatUser >= 10 && $showtimeRequest->status === 'hold') {
                return response()->json(['error' => 'Bạn chỉ có thể chọn tối đa 10 ghế !!!'], 409);
            }
            Log::info('After Seat Count: ' . (microtime(true) - $start) . ' seconds');

            foreach ($seat_structures as &$seat_structure) {
                if ($seat_structure['id'] == $showtimeRequest->seat_id) {
                    // Nếu ghế đã được đặt hoặc giữ chỗ, từ chối cập nhật
                    if ($seat_structure['status'] !== 'available' && $seat_structure['status'] !== 'hold') {
                        return response()->json(['error' => 'Ghế này đã được đặt hoặc không khả dụng'], 409);
                    }

                    // Cập nhật trạng thái ghế
                    $seat_structure['user_id'] = $showtimeRequest->user_id;
                    $seat_structure['status'] = $showtimeRequest->status;
                    break;
                }
            }

            Log::info('After Update: ' . (microtime(true) - $start) . ' seconds');


            $showtime->update([
                'seat_structure' => $seat_structures,
            ]);

            Log::info('Pushing event to queue');

            // event(new RealTimeSeatEvent($showtimeRequest->seat_id, $showtimeRequest->status, $showtimeRequest->user_id), ['queue' => 'high']);

            broadcast(new RealTimeSeatEvent($showtimeRequest->seat_id, $showtimeRequest->status, $showtimeRequest->user_id))->toOthers();

            Log::info('After Broadcast: ' . (microtime(true) - $start) . ' seconds');

            return response()->json([
                'message' => 'Thao tác thành công',
                'data' => $seat_structures,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Thao tác không thành công',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**** */

    // public function changeSeatStatus(ShowtimeRequest $showtimeRequest, string $id)
    // {
    //     try {
    //         $start = microtime(true);
    //         Log::info('Start API');

    //         // Khóa bản ghi showtime để tránh race condition
    //         $showtime = Showtime::query()->where('id', $id)->firstOrFail();
    //         Log::info('After Query: ' . (microtime(true) - $start) . ' seconds');

    //         // $seat_structures = json_decode($showtime->seat_structure, true);
    //         $seat_structures = $showtime->seat_structure;
    //         Log::info('After Get Seats: ' . (microtime(true) - $start) . ' seconds');

    //         if (empty($seat_structures)) {
    //             return response()->json(['error' => 'Không tìm thấy danh sách ghế'], 400);
    //         }
    //         // Minh Hải Check 1 user chỉ được 10 ghế
    //         $checkTotalSeatUser = count(array_keys(array_column($seat_structures, 'user_id'), $showtimeRequest->user_id));

    //         if ($checkTotalSeatUser >= 10 && $showtimeRequest->status === 'hold') {
    //             return response()->json(['error' => 'Bạn chỉ có thể chọn tối đa 10 ghế !!!'], 409);
    //         }
    //         Log::info('After Seat Count: ' . (microtime(true) - $start) . ' seconds');

    //         // Kiểm tra trạng thái ghế trước khi cập nhật
    //         foreach ($seat_structures as &$seat_structure) {
    //             if ($seat_structure['id'] == $showtimeRequest->seat_id) {
    //                 // Nếu ghế đã được đặt hoặc giữ chỗ, từ chối cập nhật
    //                 if ($seat_structure['status'] !== 'available' && $seat_structure['status'] !== 'hold') {
    //                     return response()->json(['error' => 'Ghế này đã được đặt hoặc không khả dụng'], 409);
    //                 }

    //                 // Cập nhật trạng thái ghế
    //                 $seat_structure['user_id'] = $showtimeRequest->user_id;
    //                 $seat_structure['status'] = $showtimeRequest->status;
    //                 break;
    //             }
    //         }

    //         Log::info('After Update: ' . (microtime(true) - $start) . ' seconds');

    //         $showtime->update([
    //             'seat_structure' => $seat_structures,
    //         ]);

    //         $broadcastStart = microtime(true);

    //         // $pusher = new Pusher(
    //         //     env('PUSHER_APP_KEY'),
    //         //     env('PUSHER_APP_SECRET'),
    //         //     env('PUSHER_APP_ID'),
    //         //     options: ['cluster' => 'ap1', 'useTLS' => true]
    //         // );

    //         // $pusher->trigger('showtime', 'RealTimeSeatEvent', [
    //         //     'seat_id' => $showtimeRequest->seat_id,
    //         //     'status' => $showtimeRequest->status,
    //         //     'user_id' => $showtimeRequest->user_id,
    //         // ]);

    //         $seatId = $showtimeRequest->seat_id;
    //         $status = $showtimeRequest->status;
    //         $userId = $showtimeRequest->user_id;

    //         broadcast(new RealTimeSeatEvent($seatId, $status, $userId))->toOthers();

    //         Log::info('Laravel to Pusher time: ' . (microtime(true) - $broadcastStart) . ' seconds');

    //         Log::info('After Broadcast: ' . (microtime(true) - $start) . ' seconds');

    //         return response()->json([
    //             'message' => 'Thao tác thành công',
    //             'data' => $seat_structures,
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         Log::error($th->getMessage());
    //         return response()->json([
    //             'error' => 'Thao tác không thành công',
    //             'message' => $th->getMessage()
    //         ], 500);
    //     }
    // }



    public function resetSuccessSeat(Request $request, string $id)
    {
        // Validate đầu vào ngay từ đầu
        $validatedData = $request->validate([
            'user_id' => 'nullable',
            'status' => 'nullable|string',
            'seat_id' => 'required|array'
        ]);

        try {
            $showtime = Showtime::query()->where('id', $id)->lockForUpdate()->firstOrFail();

            $seat_structures = json_decode($showtime->seat_structure, true);
            $seatIds = $validatedData['seat_id'];
            $status = $validatedData['status'];


            // return $this->successResponse(
            //     [
            //         'seat_id' => $seatIds,
            //         'showtime' => $showtime,
            //         'status' => $status,
            //         'idShowtime' => $id
            //     ],
            //     'Thao tác thành công!'
            // );


            // Cập nhật trạng thái ghế
            // $updated_seats = array_map(function ($seat) use ($validatedData) {
            //     if ($seat['user_id'] == $validatedData['user_id'] && $seat['status'] !== "sold") {
            //         if (!empty($validatedData['status'])) {
            //             // sold
            //             // $seat['status'] = $validatedData['status'];
            //             $seat['status'] = 'sold';
            //         } else {
            //             $seat['user_id'] = null;
            //             $seat['status'] = "available";
            //         }
            //     }
            //     return $seat;
            // }, $seat_structures);


            /** code new */
            $updated_seats = array_map(function ($seat) use ($validatedData, $seatIds) {
                if (isset($seat['id']) && in_array($seat['id'], $seatIds)) {
                    $seat['status'] = $validatedData['status'] ?? $seat['status'];
                    $seat['user_id'] = $validatedData['user_id'] ?? $seat['user_id'];

                    broadcast(new RealTimeSeatEvent($seat['id'], $seat['status'], $seat['user_id']))->toOthers();
                }
                return $seat;
            }, $seat_structures);



            $showtime->update([
                'seat_structure' => json_encode($updated_seats),
            ]);

            return $this->successResponse(
                $showtime,
                'Thao tác thành công!'
            );
        } catch (\Throwable $th) {

            return $this->errorResponse($th->getMessage());
        }
    }
}
