@extends('admin.layouts.master')

@section('content')
    <div class="container my-4">
        <h1 class="mb-4 text-center">Chi tiết phim: {{ $movie->name }}</h1>
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <!-- Ảnh phim -->
                    <div class="col-md-4 text-center">
                        @if ($movie->img_thumbnail)
                            <img src="{{ asset('storage/' . $movie->img_thumbnail) }}" alt="{{ $movie->name }}"
                                class="img-fluid rounded">
                        @else
                            <img src="https://via.placeholder.com/300x400?text=No+Image" alt="No Image"
                                class="img-fluid rounded">
                        @endif
                    </div>
                    <!-- Thông tin cơ bản -->
                    <div class="col-md-8">
                        <h3>{{ $movie->name }}</h3>
                        <p><strong>Slug:</strong> {{ $movie->slug }}</p>
                        <p><strong>Danh mục:</strong> {{ $movie->category }}</p>
                        <p><strong>Tác giả:</strong> {{ $movie->director }}</p>
                        <p><strong>Trailer:</strong> <a href="{{ $movie->trailer_url }}"
                                target="_blank">{{ $movie->trailer_url }}</a></p>
                        <p><strong>Thời lượng:</strong> {{ $movie->duration }}</p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $movie->is_active ? 'success' : 'secondary' }}">
                                {{ $movie->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="badge bg-{{ $movie->is_hot ? 'danger' : 'secondary' }}">
                                {{ $movie->is_hot ? 'Hot' : 'Normal' }}
                            </span>
                            <span class="badge bg-{{ $movie->is_special ? 'info' : 'secondary' }}">
                                {{ $movie->is_special ? 'Special' : 'Standard' }}
                            </span>
                            <span class="badge bg-{{ $movie->is_publish ? 'primary' : 'secondary' }}">
                                {{ $movie->is_publish ? 'Published' : 'Unpublished' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Mô tả phim -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5>Mô tả phim:</h5>
                        <p>{{ $movie->description }}</p>
                    </div>
                </div>

                <!-- Thông tin chi tiết theo lưới -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Ngày trình chiếu:</strong> {{ $movie->release_date }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ngày kết thúc:</strong> {{ $movie->end_date }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Phụ phí:</strong> {{ $movie->surcharge }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Đánh giá:</strong> {{ $movie->rating }}</p>
                    </div>
                </div>

                <!-- Phiên bản phim -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5>Phiên bản phim:</h5>
                        @if(is_array($movie->movie_versions))
                            @foreach($movie->movie_versions as $version)
                                <span class="badge bg-info text-dark me-1">{{ $version }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-info text-dark">{{ $movie->movie_versions }}</span>
                        @endif
                    </div>
                </div>

                <!-- Thể loại phim -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h5>Thể loại phim:</h5>
                        @if(is_array($movie->movie_genres))
                            @foreach($movie->movie_genres as $genre)
                                <span class="badge bg-secondary me-1">{{ $genre }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">{{ $movie->movie_genres }}</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
@endsection
