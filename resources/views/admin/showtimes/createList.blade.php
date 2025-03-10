@extends('admin.layouts.master')

@section('style')
    <!-- Nếu cần thêm CSS tùy chỉnh, bạn có thể thêm ở đây -->
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-sm-flex align-items-center justify-content-between text-center row">
            <div class="text-center col-lg-9">
                <h5 class="mb-2 text-secondary fw-semibold">
                    <i class="bi bi-film"></i> Phim :
                    <span class="text-light-emphasis">{{ $movie->name }}</span>
                </h5>
                <p class="text-muted fs-6">
                    <i class="bi bi-calendar-event"></i> Ngày tạo:
                    <span class="text-warning fw-semibold">
                        {{ \Carbon\Carbon::parse($movie->created_at)->format('d/m/Y') }}
                    </span>
                    | <i class="bi bi-calendar-check"></i> Ngày phát hành:
                    <span class="text-success fw-semibold">
                        {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                    </span>
                    | <i class="bi bi-calendar-x"></i> Ngày kết thúc:
                    <span class="text-danger fw-semibold">
                        {{ \Carbon\Carbon::parse($movie->end_date)->format('d/m/Y') }}
                    </span>
                </p>
            </div>

            <div class="page-title-right col-lg-3">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.showtimes.index') }}">Quản lý suất chiếu</a>
                    </li>
                    <li class="breadcrumb-item active">Tạo suất chiếu</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@php
    $appUrl = env('APP_URL');
@endphp
@endsection

@section('script')

@endsection
