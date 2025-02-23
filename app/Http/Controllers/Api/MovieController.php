<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Services\MovieService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MovieController extends Controller
{
    use ApiResponseTrait;

    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request)
    {
        try {
            // Tổng số bản ghi (không lọc)
            $totalRecords = Movie::count();

            // Tạo query gốc
            $query = Movie::query();

            // Lọc theo tên phim
            if ($request->filled('id')) {
                $query->where('id', 'LIKE', '%' . $request->id . '%');
            }

            // Lọc theo movie_versions (dữ liệu JSON)
            if ($request->filled('movie_versions')) {
                $versions = is_array($request->movie_versions) ? $request->movie_versions : [$request->movie_versions];
                foreach ($versions as $version) {
                    $query->whereJsonContains('movie_versions', $version);
                }
            }

            // Lọc theo movie_genres (dữ liệu JSON)
            if ($request->filled('movie_genres')) {
                $genres = is_array($request->movie_genres) ? $request->movie_genres : [$request->movie_genres];
                foreach ($genres as $genre) {
                    $query->whereJsonContains('movie_genres', $genre);
                }
            }

            // Tìm kiếm từ DataTables (nếu có)
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }

            // Số bản ghi sau khi lọc
            $filteredRecords = $query->count();

            // Phân trang
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $movies = $query->skip($start)->take($length)->get();

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $totalRecords,     // Tổng số bản ghi trước lọc
                "recordsFiltered" => $filteredRecords,  // Số bản ghi sau khi lọc
                "data" => $movies,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $movie = Movie::query()->findOrFail($id);
            return $this->successResponse(
                $movie,
                'Movie retrieved successfully!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function store(MovieRequest $request)
    {
        try {
            $data = $this->movieService->createMovie($request->all());
            return $this->successResponse(
                $data,
                'Movie created successfully!',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function update(MovieRequest $request, string $id)
    {
        try {
            $data = $this->movieService->updateMovie($id, $request->all());
            return $this->successResponse(
                $data,
                'Movie updated successfully!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function delete(string $id)
    {
        try {
            $this->movieService->deleteMovie($id);
            return $this->successResponse(
                null,
                'Movie deleted successfully!',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
