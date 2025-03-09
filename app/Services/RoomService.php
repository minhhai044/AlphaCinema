<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat_template;
use App\Models\Type_room;

class RoomService
{
    public function getService($request)
    {
        $branchs = Branch::with('cinemas')->where('is_active', 1)->get();
        $branchsRelation = [];
        foreach ($branchs as $branch) {
            $branchsRelation[$branch['id']] = $branch->cinemas->pluck('name', 'id');
        }
        $seat_templates = Seat_template::query()->where('is_active', 1)->get();

        $type_rooms = Type_room::query()->pluck('name', 'id')->all();
        $rooms = Room::with('branch','cinema','seat_template','type_room')->latest('id')->get();
        return [$branchs,$branchsRelation,$seat_templates,$type_rooms, $rooms];
    }
    
    public function storeService(array $data){
        return Room::create($data);
    }
    public function updateService(array $data , string $id){
        $room = Room::findOrFail($id);
        $room->update($data);
        return $room;
    }
    public function showService(string $id){
        $room = Room::with('branch','cinema','seat_template','type_room')->findOrFail($id);
        $seatMap = [];
        if ($room->seat_structure) {
           
            $seats = json_decode($room->seat_structure, true);
            if ($seats) {
                foreach ($seats as $seat) {
                    $coordinates_y = $seat['coordinates_y'];
                    $coordinates_x = $seat['coordinates_x'];

                    if (!isset($seatMap[$coordinates_y])) {
                        $seatMap[$coordinates_y] = [];
                    }

                    $seatMap[$coordinates_y][$coordinates_x] = $seat['type_seat_id'];
                }
            }
        }
        return [$room,$seatMap];
    }
}
