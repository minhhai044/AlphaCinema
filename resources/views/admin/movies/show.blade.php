@extends('admin.layouts.master')

@section('style')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .movie-container {
            margin-top: 30px;
            max-width: 1200px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .movie-img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .movie-img:hover {
            transform: scale(1.03);
        }

        h1 {
            font-weight: 700;
            color: #343a40;
            margin-bottom: 20px;
        }

        h3 {
            font-weight: 600;
            color: #007bff;
        }

        h5 {
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.5em 1em;
            margin-right: 5px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        p {
            margin-bottom: 10px;
            color: #495057;
        }

        strong {
            color: #343a40;
        }

        .trailer-link {
            color: #007bff;
            text-decoration: none;
        }

        .trailer-link:hover {
            text-decoration: underline;
            color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <div class="container movie-container my-4">
        <h3 class="mb-4 text-center">Chi tiết phim: {{ $movie->name }}</h3>
        <div class="card">
            <div class="card-body p-4">
                <div class="row mb-4 align-items-center">
                    <!-- Ảnh phim -->
                    <div class="col-md-4 text-center">
                        @if ($movie->img_thumbnail)
                            <img src="{{ asset('storage/' . $movie->img_thumbnail) }}" alt="{{ $movie->name }}"
                                class="movie-img img-fluid">
                        @else
                            <img src="https://via.placeholder.com/300x400?text=No+Image" alt="No Image"
                                class="movie-img img-fluid">
                        @endif
                    </div>
                    <!-- Thông tin cơ bản -->
                    <div class="col-md-8">
                        <h3>{{ $movie->name }}</h3>
                        <p><strong><i class="bi bi-link-45deg me-1"></i> Slug:</strong> {{ $movie->slug }}</p>
                        <p><strong><i class="bi bi-list-ul me-1"></i> Diễn viên:</strong> {{ $movie->category }}</p>
                        <p><strong><i class="bi bi-person-video me-1"></i> Tác giả:</strong> {{ $movie->director }}</p>
                        <p><strong><i class="bi bi-youtube me-1"></i> Trailer:</strong>
                            <a href="{{ $movie->trailer_url }}" target="_blank"
                                class="trailer-link">{{ $movie->trailer_url }}</a>
                        </p>
                        <p><strong><i class="bi bi-clock me-1"></i> Thời lượng:</strong> {{ $movie->duration }}</p>
                        <p>
                            <strong><i class="bi bi-info-circle me-1"></i> Trạng thái:</strong>
                            <span class="badge bg-{{ $movie->is_active ? 'success' : 'secondary' }}">
                                {{ $movie->is_active ? 'Hoạt động' : 'Không Hoạt Động' }}
                            </span>
                            <span class="badge bg-{{ $movie->is_hot ? 'danger' : 'secondary' }}">
                                {{ $movie->is_hot ? 'Nổi bật' : 'Bình thường' }}
                            </span>
                            <span class="badge bg-{{ $movie->is_special ? 'info' : 'secondary' }}">
                                {{ $movie->is_special ? 'Đặc biệt' : 'Tiêu chuẩn' }}
                            </span>
                            <span class="badge bg-{{ $movie->is_publish ? 'primary' : 'secondary' }}">
                                {{ $movie->is_publish ? 'Đã xuất bản' : 'Chưa xuất bản' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Mô tả phim -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5><i class="bi bi-chat-text me-1"></i> Mô tả phim:</h5>
                        <p class="border p-3 rounded bg-light">{{ strip_tags($movie->description) }}</p>
                    </div>
                </div>

                <!-- Thông tin chi tiết theo lưới -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-calendar-event me-1"></i> Ngày trình chiếu:</strong>
                            {{ $movie->release_date }}</p>
                        <p><strong><i class="bi bi-cash-stack me-1"></i> Phụ phí:</strong> {{ $movie->surcharge }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-calendar-x me-1"></i> Ngày kết thúc:</strong> {{ $movie->end_date }}</p>
                        <p>
                            <strong><i class="bi bi-star me-1"></i> Đánh giá:</strong>
                            <span class="star-display d-flex gap-1" style="font-size: 1.2rem;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $movie->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </span>
                            <span>({{ $movie->rating }}/5)</span>
                        </p>
                    </div>

                    <style>
                        .star-display .bi-star-fill {
                            color: #f1c40f;
                        }

                        .star-display .bi-star {
                            color: #ced4da;
                        }
                    </style>
                </div>

                <!-- Phiên bản phim -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5><i class="bi bi-film me-1"></i> Phiên bản phim:</h5>
                        @if (is_array($movie->movie_versions))
                            @foreach ($movie->movie_versions as $version)
                                <span class="badge bg-info text-dark me-1">{{ $version }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-info text-dark">{{ $movie->movie_versions }}</span>
                        @endif
                    </div>
                </div>

                <!-- Thể loại phim -->
                <!-- Thể loại phim -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5><i class="bi bi-tag me-1"></i> Thể loại phim:</h5>
                        @php
                            // Mảng ánh xạ thể loại từ tiếng Anh sang tiếng Việt
                            $genreTranslations = [
                                'Action' => 'Hành động',
                                'Horror' => 'Kinh dị',
                                'Comedy' => 'Hài hước',
                            ];
                        @endphp
                        @if (is_array($movie->movie_genres))
                            @foreach ($movie->movie_genres as $genre)
                                <span class="badge bg-secondary me-1">
                                    {{ $genreTranslations[$genre] ?? $genre }}
                                </span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">
                                {{ $genreTranslations[$movie->movie_genres] ?? $movie->movie_genres }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
@endsection
