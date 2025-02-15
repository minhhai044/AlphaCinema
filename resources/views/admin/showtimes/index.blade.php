@extends('admin.layouts.master')
@section('content')
    <h1>Quản lý xuất chiếu</h1>
    <!-- Button trigger modal -->
    <a href="{{route('admin.showtimes.create')}}"><button type="button" class="btn btn-primary float-end mb-3">
        Tạo xuất chiếu
    </button></a>

    <!-- Modal -->
    


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên phòng</th>
                <th>Chi nhánh</th>
                <th>Rạp phim</th>
                <th>Loại phòng</th>
                <th>Hoạt động</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
         
        </tbody>
    </table>

   

    @php
        $appUrl = env('APP_URL');
    @endphp
@endsection
@section('script')
    

@endsection