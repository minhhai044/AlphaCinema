<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MovieService
{
    // public function getAllMovies()
    // {
    //     return Movie::latest()->paginate(5);
    // }
    public function getAllMovies()
    {
        $query = Movie::query();

        $totalRecords = $query->count(); // Tổng số phim
        $filteredRecords = $totalRecords; // Số phim sau khi lọc

        $movies = $query->paginate(request()->get('length', 10)); // Lấy số lượng từ request

        return [
            "draw" => request()->get('draw', 0),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $movies->items()
        ];
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
            // $data['movie_versions'] = json_encode($data['movie_versions']) ;
            // $data['movie_genres'] = json_encode($data['movie_genres']) ;
            // dd($data['movie_versions']);
            Movie::create($data);
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

            // Kiểm tra phị phí nếu là đặc biệt
            $data['surcharge'] = $data['is_special'] == 0 ? 0 : $movie->surcharge;

            // Kiểm tra và giữ lại giá trị cũ nếu không cập nhật
            $data['release_date'] = $data['release_date'] ?? $movie->release_date;
            $data['end_date'] = $data['end_date'] ?? $movie->end_date;

            if (isset($data['img_thumbnail'])) {
                if (Storage::exists($movie->img_thumbnail)) {
                    Storage::delete($movie->img_thumbnail);
                }
                $data['img_thumbnail'] = Storage::put('movie_images', $data['img_thumbnail']);
            }

            $data['movie_versions'] = isset($data['movie_versions']) ? ($data['movie_versions']) : ([]);
            $data['movie_genres'] = isset($data['movie_genres']) ? ($data['movie_genres']) : ([]);

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
