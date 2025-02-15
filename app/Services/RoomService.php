<?php

namespace App\Services;

use App\Models\Room;


class RoomService
{
    public function getAll($request)
    {
        Room::latest('id')->paginate(10);
    }
   
}
