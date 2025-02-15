<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name',
        'img_thumbnail',
        'price',
        'description',
        'is_active',
        'created_at',
        'updated_at',
    ];


}
