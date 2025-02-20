@extends('admin.layouts.master')
@section('content')
    <h1>Quản lý xuất chiếu</h1>
    <!-- Button trigger modal -->
    {{-- <a href="{{route('admin.showtimes.create')}}"><button type="button" class="btn btn-primary float-end mb-3">
        Tạo xuất chiếu
    </button></a> --}}

    <!-- Modal -->
    


    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
        <thead>
            <tr>
                <th>Tên phim</th>
                <th>Ảnh</th>
                <th>Thể loại phim</th>
                <th>Phiên bản phim</th>
                <th>Hoạt động</th>
                <th>Nổi bật</th>
                <th>Thời lượng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movies as $movie)
                <tr>
                    <td>{{ $movie->name }}</td>
                    <td>
                        <img src="{{ Storage::url($movie->img_thumbnail) }}" alt="" width="100px">
                    </td>
                    <td>
                        {{ implode(', ', json_decode($movie->movie_versions, true) ?? []) }}
                    </td>
                    <td>
                        {{ implode(', ', json_decode($movie->movie_genres, true) ?? []) }}
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
                        <a href="{{route('admin.showtimes.create',$movie)}}"><button type="button" class="btn btn-info">
                            <i class="mdi mdi-plus-circle-outline"></i>
                        </button></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

   

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection
@section('script')
    

@endsection