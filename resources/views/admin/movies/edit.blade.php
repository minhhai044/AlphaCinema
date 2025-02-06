@extends('admin.layouts.master')
@section('content')
    <!-- start page title -->
    <div class="row">

        <h1 class="card-title">Sửa phim: {{ $movie->name }}</h1>
        <div class="col-12">
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
                                            <input type="text" name="name" value="{{ old('name', $movie->name) }}"
                                                class="form-control" >
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Đường dẫn phim</label>
                                            <input type="text" name="slug" value="{{ old('slug', $movie->slug) }}"
                                                class="form-control" >
                                            @error('slug')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Danh mục phim</label>
                                            <input type="text" name="category"
                                                value="{{ old('category', $movie->category) }}" class="form-control"
                                                >
                                            @error('category')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ảnh</label>
                                            <input type="file" name="img_thumbnail" class="form-control">
                                            @if ($movie->img_thumbnail)
                                                <img src="{{ asset('storage/' . $movie->img_thumbnail) }}" width="100"
                                                    class="mt-2">
                                            @endif
                                            @error('img_thumbnail')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phiên bản phim</label>
                                            <input type="text" name="movie_versions"
                                                value="{{ old('movie_versions', $movie->movie_versions) }}"
                                                class="form-control" >
                                            @error('movie_versions')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Mô tả</label>
                                            <textarea name="description" class="form-control" >{{ old('description', $movie->description) }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tác giả</label>
                                            <input type="text" name="director"
                                                value="{{ old('director', $movie->director) }}" class="form-control"
                                                >
                                            @error('director')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thời lượng phim</label>
                                            <input type="text" name="duration"
                                                value="{{ old('duration', $movie->duration) }}" class="form-control">
                                            @error('duration')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label>Ngày trình chiếu</label>
                                            <div class="input-group">
                                                <input type="datetime-local" class="form-control" name="release_date"
                                                    value="{{ old('release_date', $movie->release_date ? date('Y-m-d\TH:i', strtotime($movie->release_date)) : '') }}">
                                                @error('release_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                                <input type="datetime-local" class="form-control" name="end_date"
                                                    value="{{ old('end_date', $movie->end_date ? date('Y-m-d\TH:i', strtotime($movie->end_date)) : '') }}">
                                                @error('end_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phụ phí</label>
                                            <input type="number" name="surcharge" class="form-control"
                                                value="{{ old('surcharge', $movie->surcharge) }}" >
                                            @error('surcharge')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thể loại phim</label>
                                            <input type="text" name="movie_genres" class="form-control"
                                                value="{{ old('movie_genres', $movie->movie_genres) }}" >
                                            @error('movie_genres')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Đánh giá</label>
                                            <input type="number" name="rating" class="form-control"
                                                value="{{ old('rating', $movie->rating) }}" >
                                            @error('rating')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
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
                        <div class="d-flex flex-wrap gap-2">
                            <a href="">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Submit
                                </button>
                            </a>
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary mb-3">Quay lại</a>
                        </div>
                    </div>

                    <script>
                        document.querySelectorAll('.square-switch input[type="checkbox"]').forEach(function(checkbox) {
                            checkbox.addEventListener("change", function() {
                                let hiddenInput = document.getElementById(this.id.replace("square-switch_", ""));
                                hiddenInput.value = this.checked ? 1 : 0;
                            });
                        });
                    </script>


                </div>

            </form>
        </div>
    </div>
    <!-- end select2 -->

    </div>
@endsection
