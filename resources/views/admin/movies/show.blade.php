@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <h1 class="card-title">Xem chi tiết: {{ $movie->name }}</h1>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-primary mb-3">Quay lại</a>
            <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data"
                class="custom-validation">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Khối 9/12 -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tên phim</label>
                                            <input disabled type="text" name="name" value="{{ $movie->name }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Slug</label>
                                            <input disabled type="text" name="slug" value="{{ $movie->slug }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Danh mục phim</label>
                                            <input disabled type="text" name="category" value="{{ $movie->category }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ảnh </label>
                                            <input disabled type="file" name="img_thumbnail" class="form-control">
                                            @if ($movie->img_thumbnail)
                                                <img src="{{ asset('storage/' . $movie->img_thumbnail) }}" width="100"
                                                    class="mt-2">
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim </label>
                                            @php
                                                $selectedVersions = json_decode($movie->movie_versions, true) ?? [];
                                            @endphp
                                            <input type="checkbox" name="movie_versions[]" value="Action"
                                                {{ in_array('Action', $selectedVersions) ? 'checked' : '' }}>  Action

                                            <input type="checkbox" name="movie_versions[]" value="Horror"
                                                {{ in_array('Horror', $selectedVersions) ? 'checked' : '' }}> Horror

                                            <input type="checkbox" name="movie_versions[]" value="Comedy"
                                                {{ in_array('Comedy', $selectedVersions) ? 'checked' : '' }}> Comedy
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mô tả </label>
                                            <textarea disabled name="description" class="form-control" required>{{ old('description', $movie->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tác giả</label>
                                            <input disabled type="text" name="director" value="{{ $movie->director }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">URL phim</label>
                                            <input disabled type="text" name="trailer_url" value="{{ $movie->trailer_url }}"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thời lượng phim</label>
                                            <input disabled type="txt"name="duration" value="{{ $movie->duration }}"
                                                class="form-control">
                                        </div>
                                        <div class="mb-4">
                                            <label>Ngày trình chiếu</label>
                                            <div class="input-daterange input-group" id="datepicker6"
                                                data-date-format="dd M, yyyy" data-date-autoclose="true"
                                                data-provide="datepicker" data-date-container="#datepicker6">
                                                <input disabled type="date" class="form-control"
                                                    name="release_date"
                                                    value="{{ old('release_date', $movie->release_date ? date('Y-m-d', strtotime($movie->release_date)) : '') }}">

                                                <input disabled type="date" class="form-control" name="end_date"
                                                    value="{{ old('end_date', $movie->end_date ? date('Y-m-d', strtotime($movie->end_date)) : '') }}">

                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phụ phí</label>
                                            <input disabled type="number" name="surcharge" class="form-control"
                                                value="{{ $movie->surcharge }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thể loại phim</label>
                                            @php
                                                $selectedVersions = json_decode($movie->movie_genres, true) ?? [];
                                            @endphp
                                            <input type="checkbox" name="movie_genres[]" value="2D"
                                                {{ in_array('2D', $selectedVersions) ? 'checked' : '' }}> 2D

                                            <input type="checkbox" name="movie_genres[]" value="3D"
                                                {{ in_array('3D', $selectedVersions) ? 'checked' : '' }}> 3D

                                            <input type="checkbox" name="movie_genres[]" value="4D"
                                                {{ in_array('4D', $selectedVersions) ? 'checked' : '' }}> 4D
                                            @error('movie_genres')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá</label>
                                            <input disabled type="number" name="rating" class="form-control"
                                                value="{{ $movie->rating }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Khối 3/12 -->
                    <!-- Khối 3/12 -->
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                @php
                                    $statuses = [
                                        'is_active' => 'Active',
                                        'is_hot' => 'Hot',
                                        'is_special' => 'Special',
                                        'is_publish' => 'Publish',
                                    ];
                                @endphp

                                @foreach ($statuses as $key => $label)
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="square-switch">
                                            <input type="checkbox" id="square-switch_{{ $key }}" switch="bool"
                                                {{ $movie->$key ? 'checked' : '' }} />
                                            <label for="square-switch_{{ $key }}" data-on-label="Yes"
                                                data-off-label="No"></label>
                                        </div>
                                        <span>{{ $label }}</span>
                                    </div>
                                    <input type="hidden" name="{{ $key }}" id="{{ $key }}"
                                        value="{{ $movie->$key }}">
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
