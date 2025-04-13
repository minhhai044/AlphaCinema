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
            $user = auth()->user();

            // Tổng số bản ghi (toàn bộ)
            $totalRecords = Movie::count();

            // Tạo query gốc
            $query = Movie::query()->latest();

            //PHÂN QUYỀN: Nếu KHÔNG phải System Admin thì lọc theo chi nhánh
            if (!$user->hasRole('System Admin')) {
                \Log::info('User info:', ['user_id' => $user->id, 'branch_id' => $user->branch_id]);
                if ($user->branch_id) {
                    $query->join('movie_branches', 'movies.id', '=', 'movie_branches.movie_id')
                        ->where('movie_branches.branch_id', $user->branch_id)
                        ->select('movies.*');
                    \Log::info('Query SQL:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
                } else {
                    \Log::warning('No branch assigned for user: ' . $user->id);
                    return response()->json([
                        'draw' => intval($request->draw),
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => [],
                    ]);
                }
            }

            \Log::info('Request filters:', [
                'id' => $request->id,
                'movie_versions' => $request->movie_versions,
                'movie_genres' => $request->movie_genres,
                'search' => $request->search['value'] ?? null,
            ]);

            if ($request->filled('id')) {
                $query->where('movies.id', 'LIKE', '%' . $request->id . '%');
            }

            if ($request->filled('movie_versions')) {
                $versions = is_array($request->movie_versions) ? $request->movie_versions : [$request->movie_versions];
                foreach ($versions as $version) {
                    $query->whereJsonContains('movies.movie_versions', $version);
                }
            }

            if ($request->filled('movie_genres')) {
                $genres = is_array($request->movie_genres) ? $request->movie_genres : [$request->movie_genres];
                foreach ($genres as $genre) {
                    $query->whereJsonContains('movies.movie_genres', $genre);
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where('movies.name', 'LIKE', "%{$searchValue}%");
            }

            \Log::info('Final query before count:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $filteredRecords = $query->count();

            // Phân trang
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $movies = $query->skip($start)->take($length)->get();

            \Log::info('Movies fetched:', ['movies' => $movies->toArray()]);

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $movies,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ApiMovieController: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }
    }
    public function getMoviesByCinema(Request $request)
    {
        $cinemaId = $request->input('cinema_id');
        $movies = Movie::where('is_active', 1)->get(['id', 'name']);
        return response()->json(['movies' => $movies]);
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
    public function updateStatus(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');

        if (in_array($field, ['is_active', 'is_hot', 'is_publish'])) {
            $movie->$field = $value;
            $movie->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
        }

        return response()->json(['success' => false, 'message' => 'Trường không hợp lệ'], 400);
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
