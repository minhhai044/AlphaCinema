@extends('admin.layouts.master')

@section('title', 'Sửa loại ghe ')

@section('style')
    <style>
        .required {
            color: red;
            font-style: italic !important;
        }
    </style>
@endsection


@section('content')

        <!-- Modal Sửa -->
      
    

    <!-- Script để tự động mở modal khi vào trang -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        });
    </script>
@endsection

@section('content')
@endsection
