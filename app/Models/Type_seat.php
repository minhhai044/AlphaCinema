<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_seat extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'price',
    ];
}
