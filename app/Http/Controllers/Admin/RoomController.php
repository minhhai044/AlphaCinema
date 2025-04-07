<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Branch;
use App\Models\Seat_template;
use App\Models\Type_room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{

    private const PATH_VIEW = "admin.rooms.";
    private $roomService;
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }
    public function index(Request $request)
    {
        [$branchs, $branchsRelation, $seat_templates, $type_rooms, $rooms] = $this->roomService->getService($request);
        return view(self::PATH_VIEW . __FUNCTION__, compact('branchs', 'type_rooms', 'seat_templates', 'branchsRelation', 'rooms'));
    }
    public function store(RoomRequest $roomRequest)
    {
        try {
            $this->roomService->storeService($roomRequest->validated());
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            Log::error(__CLASS__ . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Thao tác không thành công !!!');
        }
    }
    public function show(string $id)
    {
        [$room, $seatMap] = $this->roomService->showService($id);

        return view(self::PATH_VIEW . __FUNCTION__, compact('room', 'seatMap'));
    }
    public function update(RoomRequest $roomRequest, string $id)
    {
        try {
            $this->roomService->updateService($roomRequest->validated(), $id);
            return back()->with('success', 'Thao tác thành công !!!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Thao tác không thành công !!!');
        }

    }
}
