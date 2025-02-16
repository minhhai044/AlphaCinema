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
            $query = Movie::query();

            // Lọc theo tên phim
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where('name', 'LIKE', "%$searchValue%");
            }

            // Lấy số lượng bản ghi theo yêu cầu DataTable
            $movies = $query->paginate($request->length ?? 10);

            return response()->json([
                "draw" => intval($request->draw),
                "recordsTotal" => $movies->total(),
                "recordsFiltered" => $movies->total(),
                "data" => $movies->items(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
