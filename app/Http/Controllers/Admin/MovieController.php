<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Filters\MovieFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
        $this->middleware('can:Danh sách phim')->only('index');
        $this->middleware('can:Thêm phim')->only(['create', 'store']);
        $this->middleware('can:Sửa phim')->only(['edit', 'update']);
        $this->middleware('can:Xóa phim')->only('destroy');
        $this->middleware('can:Xem chi tiết phim')->only('show');
    }

    // 1. Hiển thị danh sách phim
    public function index(Request $request)
    {
        $filters = $request->only(['id', 'name', 'movie_versions', 'movie_genres']);

        $movieFilter = new MovieFilter($filters);
        $movies = $movieFilter->apply()->paginate(10);
        // dd($movies);
        return view('admin.movies.index', compact('movies', 'filters'));
    }
    

    // 2. Hiển thị form thêm mới phim
    public function create()
    {
        $branches = \App\Models\Branch::all();
        $selectedBranches = [];
        // dd($branches);
        return view('admin.movies.create', compact('branches', 'selectedBranches'));
    }

    // 3. Lưu phim mới
    public function store(MovieRequest $request)
    {
        // dd($request->all());

        $validated = $request->validated();
        $this->movieService->createMovie($validated);
        // dd($request->all());

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
        $movie = Movie::with('branches')->findOrFail($id);
        $branches = \App\Models\Branch::all();
        $selectedBranches = $movie->branches()->pluck('branches.id')->toArray();

        return view('admin.movies.edit', compact('movie', 'branches', 'selectedBranches'));
    }



    public function update(MovieRequest $request, $id)
    {
        // dd(1);
        // dd($id);

        $validated = $request->validated();

        $this->movieService->updateMovie( $id, $validated);


        return redirect()->route('admin.movies.index')->with('success', 'Cập nhật phim thành công!');
    }



    // 7. Xóa phim
    public function destroy($id)
    {
        $this->movieService->deleteMovie($id);
        return redirect()->route('admin.movies.index')->with('success', 'Xóa phim thành công!');
    }
}
