<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComboFood extends Model
{
    use HasFactory;
    protected $fillable = [
        'combo_id',
        'food_id',
        'quantity'
    ];
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
