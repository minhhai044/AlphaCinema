<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MovieService
{
    public function getAllMovies()
    {
        return Movie::latest()->paginate(5);
    }

    public function getMovieById($id)
    {
        return Movie::findOrFail($id);
    }

    public function createMovie($data)
    {
        
        return DB::transaction(function () use ($data) {
            if (isset($data['img_thumbnail'])) {
                $data['img_thumbnail'] = Storage::put('movieImages', $data['img_thumbnail']);
            }
            return Movie::create($data);
        });

    }

    public function updateMovie($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $movie = Movie::findOrFail($id);

            $data['is_active'] = $data['is_active'] ?? 0;
            $data['is_hot'] = $data['is_hot'] ?? 0;
            $data['is_special'] = $data['is_special'] ?? 0;
            $data['is_publish'] = $data['is_publish'] ?? 0;
            if (isset($data['img_thumbnail'])) {
                if (Storage::exists($movie->img_thumbnail)) {
                    Storage::delete($movie->img_thumbnail);
                }
                $data['img_thumbnail'] = Storage::put('movie_images', $data['img_thumbnail']);
            }

            $movie->update($data);
            return $movie;
        });
    }

    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);
        if (Storage::exists($movie->img_thumbnail)) {
            Storage::delete($movie->img_thumbnail);
        }
        $movie->delete();
        return true;
    }
}