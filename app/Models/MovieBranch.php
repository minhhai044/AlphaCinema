<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieBranch extends Model
{
    protected $table = 'movie_branches';


    protected $fillable = [
        'movie_id',
        'branch_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
