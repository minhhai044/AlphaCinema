<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'name',
        'slug',
        'address',
        'description',
        'is_active',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function rooms(){
        return $this->hasMany(Room::class);

    }
    public function user(){
        return $this->hasOne(User::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

   
}
