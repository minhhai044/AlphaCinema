<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'img_thumbnail',
        'director',
        'duration',
        'rating',
        'release_date',
        'end_date',
        'trailer_url',
        'surcharge',
        'movie_versions',
        'movie_genres',
        'is_active',
        'is_hot',
        'is_special',
        'is_publish',
    ];

    protected $casts = [
        'movie_versions' => 'array',
        'movie_genres' => 'array',
        'is_active' => 'boolean',
        'is_hot' => 'boolean',
        'is_special' => 'boolean',
        'is_publish' => 'boolean',
    ];


    public function showtime()
    {
        return $this->hasMany(Showtime::class);
    }
    public function room(){
        return $this->belongsTo(Room::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function branch(){
        return $this->hasMany(Branch::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'movie_branches', 'movie_id', 'branch_id');
    }
}
