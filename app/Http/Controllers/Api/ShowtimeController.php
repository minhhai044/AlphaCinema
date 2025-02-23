<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Services\ShowtimeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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
}
