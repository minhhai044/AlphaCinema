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
    public function listMovies(Request $request)
    {
        $branchId = $request->branchId;
        $cinemId = $request->cinemId;

        if ($branchId && $cinemId) {

            // $movies = Movie::whereHas('showtime', function ($query) use ($branchId, $cinemId) {
            //     $query->where('branch_id', $branchId);
            //     $query->where('cinema_id', $cinemId);
            //     $query->where('date', '>', Carbon::now()->toDateString())
            //         ->orWhere(function ($q) {
            //             $q->where('date', Carbon::now()->toDateString())
            //                 ->where('start_time', '>=', Carbon::now()->toTimeString());
            //         });
            // })
            //     ->with('showtime')
            //     ->latest('id')
            //     ->get();

            $movies = Movie::whereHas('showtime', function ($query) use ($branchId, $cinemId) {
                $query->where('branch_id', $branchId)
                    ->where('cinema_id', $cinemId)
                    ->where(function ($q) {
                        $q->where('date', '>=', Carbon::now()->toDateString())
                            ->where('start_time', '>=', Carbon::now()->toTimeString());
                    });
            })
                ->with('showtime')
                ->latest('id')
                ->get();


            return $this->successResponse(
                $movies,
                'Thao tác thành công'
            );
        }


        $movies = Movie::query()->latest('id')->get();
        return $this->successResponse(
            $movies,
            'Thao tác thành công'
        );
    }


    // public function movieShowTimes(string $slug,Request $request)
    // {
    //     $branchId = $request->branchId;
    //     $cinemId = $request->cinemId;
    //     $movie = Movie::with([
    //         'showtime' => function ($query) {
    //             $query->where('date', '>=', Carbon::now())
    //                 ->where('is_active', 1);
    //         },
    //         'room'
    //     ])->where('slug', $slug)->first();

    //     $data = [];

    //     $showtimes = [];

    //     foreach ($movie->showtime as $showtime) {
    //         $showtimes[$showtime['date']][] = [
    //             'id' => $showtime['id'],
    //             'start_time' => $showtime['start_time'],
    //             'slug' => $showtime['slug'],
    //             'name_room' => $showtime['room']['name']
    //         ];
    //     }

    //     $data = [
    //         'showtimes' => $showtimes, // Lịch chiếu theo ngày
    //         'movie' => $movie, // Thông tin phim
    //     ];

    //     return $this->successResponse(
    //         $data,
    //         'Thao tác thành công'
    //     );
    // }


    public function movieShowTimes(string $slug, Request $request)
    {
        $branchId = $request->branchId;
        $cinemId = $request->cinemId;
        $movie = null;

        if ($branchId && $cinemId) {
            $movie = Movie::with([
                'showtime' => function ($query) use ($branchId, $cinemId) {
                    $query->where('date', '>=', Carbon::now()->toDateString())
                        ->where('is_active', 1)
                        ->where('branch_id', $branchId)
                        ->where('cinema_id', $cinemId)
                        ->where('start_time', '>=', Carbon::now()->toTimeString());
                },
                'showtime.room'
            ])->where('slug', $slug)->first();
        }

        if (!$movie) {
            return $this->errorResponse('Không tìm thấy phim hoặc lịch chiếu', 404);
        }

        $showtimes = [];

        foreach ($movie->showtime as $showtime) {
            $showtimes[$showtime['date']][] = [
                'id' => $showtime['id'],
                'start_time' => $showtime['start_time'],
                'slug' => $showtime['slug'],
                'name_room' => optional($showtime->room)->name
            ];
        }

        $data = [
            'movie' => $movie,
            'showtimes' => $showtimes
        ];

        return $this->successResponse(
            $data,
            'Thao tác thành công'
        );
    }

    public function showtimeDetail(string $slug, Request $request)
    {
        try {
            $branchId = $request->branchId;
            $cinemId = $request->cinemId;
            $showtime = Showtime::with('branch', 'movie', 'cinema', 'room')
                ->where('slug', $slug)
                // ->where('start_time', '>=', Carbon::now()->toTimeString())
                ->where('branch_id', $branchId)
                ->where('cinema_id', $cinemId)
                ->first();

            if (!$showtime) {
                return $this->errorResponse([
                    'message' => 'Showtime not found',
                    'code' => 404
                ], Response::HTTP_NOT_FOUND);
                // throw new \Exception('Showtime not found', 404);
            }

            if (empty($showtime->seat_structure)) {
                return $this->errorResponse('Dữ liệu ghế không khả dụng', Response::HTTP_BAD_REQUEST);
            }


            $seatMap = json_decode($showtime['seat_structure'], true);
            // $seatMap = $showtime['seat_structure'];


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

    // public function changeSeatStatus(ShowtimeRequest $showtimeRequest, string $id)
    // {
    //     try {
    //         $showtime = Showtime::query()->where('id', $id)->firstOrFail();

    //         $seat_structures = $showtime->seat_structure;

    //         if (empty($seat_structures)) {
    //             return response()->json(['error' => 'Không tìm thấy danh sách ghế'], 400);
    //         }

    //         $checkTotalSeatUser = count(array_keys(array_column($seat_structures, 'user_id'), $showtimeRequest->user_id));

    //         if ($checkTotalSeatUser >= 10 && $showtimeRequest->status === 'hold') {
    //             return response()->json(['error' => 'Bạn chỉ có thể chọn tối đa 10 ghế !!!'], 409);
    //         }

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

    //         broadcast(new RealTimeSeatEvent($showtimeRequest->seat_id, $showtimeRequest->status, $showtimeRequest->user_id))->toOthers();

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


    public function changeSeatStatus(ShowtimeRequest $showtimeRequest, string $id)
    {
        try {
            $showtime = Showtime::query()->where('id', $id)->firstOrFail();
            $seat_structures = json_decode($showtime->seat_structure, true);

            if (empty($seat_structures)) {
                return response()->json(['error' => 'Không tìm thấy danh sách ghế'], 400);
            }

            $checkTotalSeatUser = count(array_keys(array_column($seat_structures, 'user_id'), $showtimeRequest->user_id));

            if ($checkTotalSeatUser >= 10 && $showtimeRequest->status === 'hold') {
                return response()->json(['error' => 'Bạn chỉ có thể chọn tối đa 10 ghế !!!'], 409);
            }

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

            $showtime->update([
                'seat_structure' => json_encode($seat_structures),
            ]);

            broadcast(new RealTimeSeatEvent($showtimeRequest->seat_id, $showtimeRequest->status, $showtimeRequest->user_id))->toOthers();

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

    public function resetSuccessSeat(Request $request, string $id)
    {
        // Validate đầu vào ngay từ đầu
        $validatedData = $request->validate([
            'user_id' => 'nullable',
            'status' => 'nullable|string',
            'seat_id' => 'required|array'
        ]);

        try {
            $showtime = $this->showtimeService->resetSuccessService($validatedData, $id);
            return $this->successResponse(
                $showtime,
                'Thao tác thành công!'
            );
        } catch (\Throwable $th) {

            return $this->errorResponse($th->getMessage());
        }
    }
}
