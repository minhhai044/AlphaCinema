<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Combo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img_thumbnail',
        'price',
        'price_sale',
        'description',
        'is_active',
    ];
}
