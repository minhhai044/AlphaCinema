<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'seat_structure',
        'name',
        'branch_id',
        'cinema_id',
        'seat_template_id',
        'type_room_id',
        'description',
        'is_publish',
        'is_active',
        'matrix_colume'
    ];
    protected $casts = [
        'is_publish' => 'boolean',
        'is_active' => 'boolean',
        'seat_structure' => 'array'
    ];
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function cinema(){
        return $this->belongsTo(Cinema::class);
    }
    public function seat_template(){
        return $this->belongsTo(Seat_template::class);
    }
    public function type_room(){
        return $this->belongsTo(Type_room::class);
    }
   

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
