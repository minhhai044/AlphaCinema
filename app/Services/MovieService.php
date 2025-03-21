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
            // Tìm movie hoặc throw exception nếu không tồn tại
            $movie = Movie::findOrFail($id);

            // Gán giá trị mặc định cho các trường boolean
            $data['is_active'] = $data['is_active'] ?? 0;
            $data['is_hot'] = $data['is_hot'] ?? 0;
            $data['is_special'] = $data['is_special'] ?? 0;
            $data['is_publish'] = $data['is_publish'] ?? 0;

            // Xử lý surcharge dựa trên is_special
            $data['surcharge'] = $data['is_special'] == 0 ? 0 : ($data['surcharge'] ?? $movie->surcharge);

            // Giữ nguyên giá trị cũ nếu không có dữ liệu mới
            $data['release_date'] = $data['release_date'] ?? $movie->release_date;
            $data[' end_date'] = $data['end_date'] ?? $movie->end_date;

            // Xử lý ảnh thumbnail nếu có
            if (isset($data['img_thumbnail'])) {
                if (Storage::exists($movie->img_thumbnail)) {
                    Storage::delete($movie->img_thumbnail);
                }
                $data['img_thumbnail'] = Storage::put('movie_images', $data['img_thumbnail']);
            }

            // Xử lý movie_versions và movie_genres, mặc định là mảng rỗng nếu không có
            $data['movie_versions'] = $data['movie_versions'] ?? [];
            $data['movie_genres'] = $data['movie_genres'] ?? [];

            // Cập nhật dữ liệu và trả về model
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
