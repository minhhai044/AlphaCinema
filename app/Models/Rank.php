<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    public const MAX_RANK = 5;
    public const MIN_RANK = 2;

    protected $fillable = [
        'name',
        'total_spent',
        'ticket_percentage',
        'combo_percentage',
        'feedback_percentage',
        'is_default',
    ];
}
