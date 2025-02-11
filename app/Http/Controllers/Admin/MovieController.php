<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Filters\MovieFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    // 1. Hiển thị danh sách phim
    public function index(Request $request)
    {
        // Lấy các tham số lọc từ request
        $filters = $request->only(['id','name', 'movie_versions', 'movie_genres']);

        // Áp dụng bộ lọc cho Movie
        $movieFilter = new MovieFilter($filters);
        $movies = $movieFilter->apply()->paginate(10);
        // dd($movies);
        // Truyền kết quả và các tham số lọc (để giữ giá trị ở form) sang view
        return view('admin.movies.index', compact('movies', 'filters'));
    }

    // 2. Hiển thị form thêm mới phim
    public function create()
    {
        return view('admin.movies.create');
    }

    // 3. Lưu phim mới
    public function store(MovieRequest $request)
    {
        // dd($request->all());
        $validated = $request->validated();
        $this->movieService->createMovie($validated);
        // dd($request['movie_versions']);
        return redirect()->route('admin.movies.index')->with('success', 'Thêm phim thành công!');
    }

    // 4. Hiển thị chi tiết phim
    public function show($id)
    {
        $movie = $this->movieService->getMovieById($id);
        return view('admin.movies.show', compact('movie'));
    }

    // 5. Hiển thị form chỉnh sửa phim
    public function edit($id)
    {
        $movie = $this->movieService->getMovieById($id);
        return view('admin.movies.edit', compact('movie'));
    }

    // 6. Cập nhật phim
    public function update(MovieRequest $request, $id)
    {
        $validated = $request->validated();

        $this->movieService->updateMovie($id, $validated);
        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công!');
    }

    // 7. Xóa phim
    public function destroy($id)
    {
        $this->movieService->deleteMovie($id);
        return redirect()->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
    }
}