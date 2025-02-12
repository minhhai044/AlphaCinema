<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cinema;
use App\Models\Seat_template;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    const PATH_VIEW = "admin.rooms.";
    public function index(){
        $branchs = Branch::query()->pluck('name','id')->all();
        $cinemas = Cinema::query()->pluck('name','id')->all();
        $seat_templates = Seat_template::query()->pluck('name','id')->all();
        $type_rooms = [
            '1' => "Ghế Thường",
            '2' => "Ghế Vip",
            '3' => "Ghế đôi"
        ];
        // dd($branchs,$cinemas,$type_rooms);
        // $type_rooms = Type_room::query()->pluck('name','id')->all();
        return view(self::PATH_VIEW . __FUNCTION__ , compact('branchs','cinemas','type_rooms','seat_templates'));
    }
}
