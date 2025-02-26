<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cinema_id',
        'room_id',
        'movie_id',
        'showtime_id',
        'voucher_code',
        'voucher_discount',
        'point_use',
        'point_discount',
        'payment_name',
        'ticket_seats',
        'total_price',
        'status',
    ];

    protected $casts = [
        'ticket_seats' => 'array', // Chuyển đổi từ JSON sang array PHP
        'total_price' => 'decimal:2',
        // 'status' => 'integer',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function cinemas(){
        return $this->belongsTo(Cinema::class);
    }

    public function room(){
        return $this->belongsTo(Room::class);
    }
    public function showtime(){
        return $this->belongsTo(Showtime::class);
    }
}
