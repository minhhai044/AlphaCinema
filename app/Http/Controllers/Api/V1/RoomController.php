<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\RoomService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    use ApiResponseTrait;

    public function activeRoom(Request $request, string $id)
    {
        $room = Room::with('cinema')->find($id);

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Phòng chiếu không tồn tại.']);
        }

        if ($room->cinema && $request->is_active == 1) {
            if ($room->cinema->is_active == 0) {
                return response()->json(['success' => false, 'message' => 'Bạn không thể bật hoạt động phòng chiếu khi các rạp chiếu vẫn ngừng hoạt động !!!']);
            }
        }
        $room->is_active = $request->is_active;
        $room->save();

        return response()->json(['success' => true]);
    }
}
