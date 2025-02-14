@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">MOVIES</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Movies</a></li>
                                        <li class="breadcrumb-item active">Table Movies</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <h4 class="card-title">Danh sách phim</h4>
                    <div class="d-flex align-items-center mb-3 ms-auto">
                        <a href="{{ route('admin.movies.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>

                        <!-- Form tìm kiếm theo ID (Quick search) -->
                        <form method="GET" action="{{ route('admin.movies.index') }}" class="mb-0 me-2">
                            <div class="input-group">
                                <input type="text" name="id" class="form-control" placeholder="Tìm kiếm theo ID"
                                    value="{{ request('id', $filters['id'] ?? '') }}">
                                <button class="btn btn-primary" type="submit"><i class="la la-search"></i> </button>
                            </div>
                        </form>
                        <!-- End Form tìm kiếm theo ID -->

                        <!-- Nút hiển thị form tìm kiếm nâng cao -->
                        <button class="btn btn-outline-primary dropdown-toggle btn-closed-search" type="button"
                            data-bs-toggle="collapse" data-bs-target="#searchForm">
                            <i class="la la-search"></i> Tìm kiếm
                        </button>
                    </div>


                    <!-- Form lọc -->
                    <div class="collapse mt-3" id="searchForm">
                        <div class="card card-body">
                            <form method="GET" action="{{ route('admin.movies.index') }}">
                                <div class="row">
                                    <!-- Tên phim -->
                                    <div class="col-md-4">
                                        <label class="form-label">Tên phim</label>
                                        <input type="text" name="name" class="form-control" placeholder="Tên phim"
                                            value="{{ request('name', $filters['name'] ?? '') }}">
                                    </div>
                                    <!-- Phiên bản -->
                                    <div class="col-md-4">
                                        <label class="form-label">Thể loại</label>
                                        <select name="movie_versions" class="form-control">
                                            <option value="">Chọn Thể loại phim</option>
                                            <option value="Action"
                                                {{ isset($filters['movie_versions']) && $filters['movie_versions'] == 'Action' ? 'selected' : '' }}>
                                                Action</option>
                                            <option value="Horror"
                                                {{ isset($filters['movie_versions']) && $filters['movie_versions'] == 'Horror' ? 'selected' : '' }}>
                                                Horror</option>
                                            <option value="Comedy"
                                                {{ isset($filters['movie_versions']) && $filters['movie_versions'] == 'Comedy' ? 'selected' : '' }}>
                                                Comedy</option>
                                        </select>
                                    </div>
                                    <!-- Thể loại -->
                                    <div class="col-md-4">
                                        <label class="form-label">Thể loại</label>
                                        <select name="movie_genres" class="form-control">
                                            <option value="">Chọn thể loại</option>
                                            <option value="2D"
                                                {{ isset($filters['movie_genres']) && $filters['movie_genres'] == '2D' ? 'selected' : '' }}>
                                                2D</option>
                                            <option value="3D"
                                                {{ isset($filters['movie_genres']) && $filters['movie_genres'] == '3D' ? 'selected' : '' }}>
                                                3D</option>
                                            <option value="4D"
                                                {{ isset($filters['movie_genres']) && $filters['movie_genres'] == '4D' ? 'selected' : '' }}>
                                                4D</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                    <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table danh sách phim -->
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Tên phim</th>
                                <th>Ảnh</th>
                                <th>Thể loại phim</th>
                                <th>Phiên bản phim</th>
                                <th>Hoạt động</th>
                                <th>Nổi bật</th>
                                <th>Thời lượng</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movies as $movie)
                                <tr>
                                    <td></td>
                                    <td>{{ $movie->name }}</td>
                                    <td>
                                        <img src="{{ Storage::url($movie->img_thumbnail) }}" alt="" width="100px">
                                    </td>
                                    <td>
                                        {{ implode(', ', json_decode($movie->movie_versions, true) ?? []) }}
                                    </td>
                                    <td>
                                        {{ implode(', ', json_decode($movie->movie_genres, true) ?? []) }}
                                        {{-- {{ $movie->movie_genres }} --}}
                                    </td>
                                    <td>
                                        <span class="badge {{ $movie->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $movie->is_active ? 'Active' : 'No Active' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $movie->is_hot ? 'bg-success' : 'bg-danger' }}">
                                            {{ $movie->is_hot ? 'Hot' : 'No Hot' }}
                                        </span>
                                    </td>
                                    <td>{{ $movie->duration }} phút</td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                ...
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('admin.movies.show', $movie->id) }}"
                                                        class="dropdown-item text-info">
                                                        <i class="fas fa-eye"></i> Xem
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.movies.edit', $movie->id) }}"
                                                        class="dropdown-item text-warning">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.movies.destroy', $movie->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                            <i class="fas fa-trash-alt"></i> Xóa
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $movies->appends($filters)->links() }}
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
