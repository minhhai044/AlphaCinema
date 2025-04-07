<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point_history extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'point',
        'type',
        'description',
        'expiry_date',
        'processed',
    ];

    /**
     * Định nghĩa quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
