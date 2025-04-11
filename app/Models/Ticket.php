<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vat',
        'cinema_id',
        'room_id',
        'movie_id',
        'showtime_id',
        'code',
        'voucher_code',
        'voucher_discount',
        'point_use',
        'point_discount',
        'payment_name',
        'ticket_seats',
        'ticket_combos',
        'ticket_foods',
        'total_price',
        'staff',
        'expiry',
        'status',
    ];

    protected $casts = [
        'ticket_seats' => 'array',
        'ticket_combos' => 'array',
        'ticket_foods' => 'array',
        // 'total_price' => 'decimal:2',
        // 'expiry' => 'datetime',

        // 'total_price' => 'decimal:2',
        // 'expiry' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Mối quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với Cinema
     */
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    /**
     * Mối quan hệ với Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Mối quan hệ với Movie (giả sử bạn có model Movie)
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Mối quan hệ với Showtime
     */
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }
    public function branch()
    {
        return $this->hasOneThrough(
            Branch::class,
            Cinema::class,
            'id',
            'id',
            'cinema_id',
            'branch_id'
        );
    }


    public function scopeByUser($query, $user)
    {
        if ($user->hasRole('system-admin')) {
            return $query;
        } elseif ($user->branch_id) {
            return $query->whereHas('cinema', fn($q) => $q->where('branch_id', $user->branch_id));
        } elseif ($user->cinema_id) {
            return $query->where('cinema_id', $user->cinema_id);
        }
        return $query->where('id', 0);
    }
}
