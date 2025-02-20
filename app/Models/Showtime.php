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
        $part1 = bin2hex(random_bytes(18)); // Tạo 36 ký tự hex (18 byte)
        $part2 = bin2hex(random_bytes(18)); // Tạo 36 ký tự hex (18 byte)

        // Chia nhỏ chuỗi theo định dạng yêu cầu
        $formattedPart1 = substr($part1, 0, 8) . '-' . substr($part1, 8, 4) . '-' . substr($part1, 12, 4) . '-' . substr($part1, 16, 4) . '-' . substr($part1, 20);
        $formattedPart2 = substr($part2, 0, 8) . '-' . substr($part2, 8, 4) . '-' . substr($part2, 12, 4) . '-' . substr($part2, 16, 4) . '-' . substr($part2, 20);

        return $formattedPart1 . '-' . $formattedPart2;
    }
    public const SPECIALSHOWTIMES = [
        ['id' => 1, 'name' => 'Suất thường'],
        ['id' => 2, 'name' => 'Suất đặc biệt'],
    ];
    public function room(){
        return $this->belongsTo(Room::class);
    }

}
