<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowtimeRequest;
use App\Services\ShowtimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShowtimeController extends Controller
{
    private const PATH_VIEW = "admin.showtimes.";

    private $showtimeService;
    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }
    public function index(Request $request)
    {

        [$branchs,$branchsRelation ,$listShowtimes,$movies] = $this->showtimeService->getService($request);
        return view(self::PATH_VIEW . __FUNCTION__, compact('branchs','branchsRelation','listShowtimes','movies'));
    }
    public function create(string $id)
    {
        [$branchs, $branchsRelation, $rooms, $movie, $days, $slug, $roomsRelation, $specialshowtimes, $type_seats, $type_rooms] = $this->showtimeService->createService($id);
        return view(self::PATH_VIEW . __FUNCTION__, compact('type_seats', 'branchs', 'branchsRelation', 'rooms', 'movie', 'days', 'slug', 'roomsRelation', 'specialshowtimes', 'type_rooms'));
    }
    public function store(ShowtimeRequest $showtimeRequest)
    {
        try {
            $this->showtimeService->storeService($showtimeRequest->validated());
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function delete(Request $request)
    {
        try {
            $this->showtimeService->deleteService($request->showtime_id);
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
}
