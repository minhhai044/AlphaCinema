<?php 
namespace App\Services;

use App\Models\Seat_template;

class SeatTemplateService {
    public function getAll(){
        return Seat_template::all();
    }
}