@extends('admin.layouts.master')

@section('style')
    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> --}}
    <style>
        /* Tổng thể */
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        .ticket-container {
            margin-top: 30px;
            padding-bottom: 30px;
        }

        /* Movie Section */
        .movie-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 25px;
            transition: all 0.3s ease;
        }

        .movie-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .movie-thumbnail {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .movie-thumbnail:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .movie-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #007bff;
            margin-top: 15px;
        }

        /* Combo Section */
        .combo-section .card {
            border: none;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .combo-section .card:hover {
            transform: translateY(-5px);
        }

        .combo-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        /* Info Container */
        .info-container .ticket-info-section,
        .info-container .payment-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 20px;
            transition: all 0.3s ease;
        }

        .info-container .ticket-info-section:hover,
        .info-container .payment-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Typography */
        h6 {
            font-weight: 700;
            color: #343a40;
            margin-bottom: 15px;
        }

        .price {
            color: #28a745;
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Button */
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 91, 187, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {

            .movie-section,
            .info-container {
                margin-bottom: 20px;
            }

            .movie-thumbnail {
                height: 200px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Ticket</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Danh sách vé</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid ticket-container d-flex gap-3">

        <!-- Movie Section (75%) -->
        <div class="movie-section col-md-9">

            {{-- <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary mb-4">
                <i class="bi bi-arrow-left me-2"></i> Quay lại
            </a> --}}
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $ticketData['movie']['thumbnail'] ?? 'https://via.placeholder.com/150' }}"
                        alt="Movie Thumbnail" class="movie-thumbnail">
                    <h5 class="movie-title">{{ $ticketData['movie']['name'] ?? 'Mufasa: Vua Sư Tử' }}</h5>
                </div>
                <div class="col-md-5">
                    <p><strong>Địa điểm:</strong> {{ $ticketData['cinema']['name'] ?? 'Hạ Long' }} -
                        {{ $ticketData['cinema']['room'] ?? 'P201' }}</p>
                    <p><strong>Lịch chiếu:</strong> {{ $ticketData['showtime']['start_time'] ?? '04:48' }} -
                        {{ $ticketData['showtime']['end_time'] ?? '07:07' }}
                        ({{ $ticketData['showtime']['date'] ?? '14/02/2025' }})</p>
                    <p><strong>Thời lượng:</strong> {{ $ticketData['movie']['duration'] ?? '160 phút' }}</p>
                    <p><strong>Định dạng:</strong> {{ $ticketData['movie']['format'] ?? '2D Lồng Tiếng' }}</p>
                    <p><strong>Thể loại:</strong> {{ $ticketData['movie']['genre'] ?? 'Tâm lý' }}</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="bi bi-chair me-2"></i> Ghế ngồi</h6>
                    @if (is_array($ticketData['seats']) && !empty($ticketData['seats']['details']))
                        <ul class="list-unstyled">
                            @foreach ($ticketData['seats']['details'] as $seat)
                                <li>{{ $seat['name'] }} - <span class="price">{{ $seat['price'] }}</span></li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ $ticketData['seats']['names'] }}</p>
                    @endif
                </div>
            </div>

            <!-- Combo Section -->
            <div class="combo-section mt-5">
                <h6><i class="bi bi-basket me-2"></i> Combo</h6>
                <div class="row">
                    @forelse ($ticketData['combos'] as $combo)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body d-flex align-items-center">
                                    <img src="{{ $combo['image'] }}" alt="{{ $combo['name'] }}" class="combo-img">
                                    <div>
                                        <h6 class="card-title">{{ $combo['name'] }}</h6>
                                        <ul class="list-unstyled mb-2">
                                            @foreach ($combo['foods'] as $food)
                                                <li>{{ $food }}</li>
                                            @endforeach
                                        </ul>
                                        <p class="mb-0">{{ $combo['quantity'] }} x {{ $combo['price'] }} = <span
                                                class="price">{{ number_format((float) $combo['total_price'], 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-3">Không có combo</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Info Container (25%) -->
        <div class="info-container col-md-3 d-flex flex-column gap-4">
            <!-- Ticket Info Section -->
            <div class="ticket-info-section">
                <h6><i class="bi bi-person me-2"></i> Thông tin người đặt</h6>
                <hr class="my-2">
                <p><strong>Tên:</strong> {{ $ticketData['user']['name'] ?? 'PhungHuy' }}</p>
                <p><strong>Email:</strong> {{ $ticketData['user']['email'] ?? 'huyphung@gmail.com' }}</p>
                <p><strong>SĐT:</strong> {{ $ticketData['user']['phone'] ?? '0987654326' }}</p>
            </div>

            <!-- Payment Section -->
            <div class="payment-section">
                <h6><i class="bi bi-wallet me-2"></i> Thông tin thanh toán</h6>
                <hr class="my-2">
                <p><strong>Thời gian:</strong> {{ $ticketData['payment_time'] ?? '21:31 - 05/03/2025' }}</p>
                <p><strong>Phương thức:</strong> {{ $ticketData['payment_name'] ?? 'ZaloPay' }}</p>
                <p><strong>Tổng tiền:</strong> <span
                        class="price">{{ $ticketData['total_amount'] ?? '240666 VND' }}</span></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script> --}}
@endsection
