<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img_thumbnail',
        'price',
        'description',
        'type',
        'is_active',
        'created_at',
        'updated_at',
    ];

    const TYPE_FOOD = [
        'Đồ ăn',
        'Đồ uống',
        'Khác'
    ];

    public function combos(){
        return $this->belongsToMany(Combo::class, 'combo_food', 'food_id', 'combo_id');
    }

}
