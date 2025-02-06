
@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
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
                    <!-- end page title -->
                    <h4 class="card-title">Danh sách phim</h4>

                    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary mb-3">+ Thêm mới</a>
                    <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Tên phim</th>
                                <th>Ảnh </th>
                                <th>Hoạt động</th>
                                <th>Nổi bật</th>
                                <th>Thời lượng</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movies as $movie)
                                <tr>
                                    <td></td>
                                    <td>{{ $movie->name }}</td>
                                    <td>
                                        <img src="{{ Storage::url($movie->img_thumbnail) }}" alt="" width="100px">
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
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="d-flex justify-content-center">
                    {{ $movies->links() }}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
