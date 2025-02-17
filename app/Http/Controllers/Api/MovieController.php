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
            // Lấy tổng số bản ghi (không lọc)
            $totalRecords = Movie::count();

            // Tạo query gốc
            $query = Movie::query();

            // Lọc theo tên phim từ form lọc (nếu có)
            if ($request->filled('name')) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }

            // Lọc theo phiên bản phim (movie_versions) nếu có
            if ($request->filled('movie_versions')) {
                // Nếu cột movie_versions lưu dưới dạng JSON
                $query->whereJsonContains('movie_versions', $request->movie_versions);
                // Nếu không, có thể dùng:
                // $query->where('movie_versions', 'LIKE', '%' . $request->movie_versions . '%');
            }

            // Lọc theo thể loại phim (movie_genres) nếu có
            if ($request->filled('movie_genres')) {
                // Nếu cột movie_genres lưu dưới dạng JSON
                $query->whereJsonContains('movie_genres', $request->movie_genres);
                // Nếu không, có thể dùng:
                // $query->where('movie_genres', 'LIKE', '%' . $request->movie_genres . '%');
            }

            // Nếu DataTables có truyền tham số tìm kiếm (mảng search)
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    // Tìm kiếm theo tên phim (có thể bổ sung thêm các cột khác nếu cần)
                    $q->orWhere('name', 'LIKE', "%{$searchValue}%");
                });
            }

            // Số bản ghi sau khi lọc
            $filteredRecords = $query->count();

            // Lấy tham số phân trang của DataTables
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);

            // Lấy dữ liệu theo phân trang
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

    // public function index(Request $request)
    // {
    //     try {
    //         $query = Movie::query();

    //         // Lưu tổng số bản ghi không lọc
    //         $totalRecords = $query->count();

    //         // Lọc theo tên phim (nếu có)
    //         if ($request->has('search') && !empty($request->search['value'])) {
    //             $searchValue = $request->search['value'];
    //             $query->where('name', 'LIKE', "%$searchValue%");
    //         }

    //         // Số bản ghi sau khi lọc
    //         $filteredRecords = $query->count();

    //         // Lấy tham số start và length từ request
    //         $length = $request->input('length', 10);
    //         $start = $request->input('start', 0);

    //         // Lấy dữ liệu theo trang
    //         $movies = $query->skip($start)->take($length)->get();

    //         return response()->json([
    //             "draw" => intval($request->draw),
    //             "recordsTotal" => $totalRecords,   // Tổng số bản ghi (không lọc)
    //             "recordsFiltered" => $filteredRecords, // Tổng số bản ghi sau khi lọc
    //             "data" => $movies,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }


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
