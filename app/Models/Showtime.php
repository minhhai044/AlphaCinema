<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'movie_id',
        'day_id',
        'cinema_id',
        'room_id',
        'seat_structure',
        'slug',
        'date',
        'price_special',
        'description_special',
        'status_special',
        'start_time',
        'end_time',
        'is_active'
    ];
    protected $casts = [
        'is_publish' => 'boolean',
        'is_active' => 'boolean',
        'seat_structure' => 'array'
    ];
    public static function generateCustomRandomString()
    {
        $characters = 'abcdefghjklmnpqrstuvwxyz';
        $charLength = strlen($characters);

        $code = '';
        for ($i = 0; $i < 13; $i++) {
            $code .= $characters[rand(0, $charLength - 1)];
        }

        $formattedCode = substr($code, 0, 3) . '-' .
            substr($code, 3, 4) . '-' .
            substr($code, 7, 3) . '-' .
            substr($code, 10, 3);

        return $formattedCode;
    }
    public static function generateOrderId()
    {
        $timestamp = substr(time(), -6);
        $randomNumber = mt_rand(100000, 999999);

        return $timestamp . $randomNumber;
    }
    public const SPECIALSHOWTIMES = [
        ['id' => 1, 'name' => 'Suất thường'],
        ['id' => 2, 'name' => 'Suất đặc biệt'],
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
   

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
