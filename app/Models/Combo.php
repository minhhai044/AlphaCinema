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

    // quan hệ 1 nhiều 1 combo nhiều combofood
    public function comboFood(){
        return $this->hasMany(ComboFood::class);
    }
    // quan hệ nhiều nhiều 1 food có nhiều combofood
    public function food(){
        return $this->belongsToMany(Food::class, 'combo_food')->withPivot('quantity');
    }

}
