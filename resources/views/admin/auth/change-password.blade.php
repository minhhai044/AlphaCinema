@extends('admin.layouts.master')

@section('content')
<div class="container mt-4">
    <h3>Đổi mật khẩu</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.change-password') }}">
        @csrf

        <div class="form-group">
            <label>Mật khẩu hiện tại</label>
            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Mật khẩu mới</label>
            <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Xác nhận mật khẩu mới</label>
            <input type="password" name="new_password_confirmation" class="form-control @error('new_password_confirmation') is-invalid @enderror">
            @error('new_password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Cập nhật mật khẩu</button>
    </form>
</div>
@endsection
