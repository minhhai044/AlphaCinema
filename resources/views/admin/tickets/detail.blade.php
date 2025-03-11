@extends('admin.layouts.master')

@section('style')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
    /* Tùy chỉnh tổng thể */
    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .ticket-container {
        margin-top: 20px;
    }

    .movie-section, .info-container .bg-white {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: none;
        border-radius: 10px;
    }

    .movie-thumbnail {
        width: 100%;
        max-width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .movie-thumbnail:hover {
        transform: scale(1.05);
    }

    .combo-img {
        max-width: 100%;
        border-radius: 5px;
        margin-right: 15px;
    }

    .price {
        color: #28a745;
        font-weight: 600;
        font-size: 1.1rem;
    }

    h6 {
        font-weight: 600;
        color: #343a40;
    }

    .table th {
        background-color: #e9ecef;
        font-weight: 600;
        color: #495057;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid ticket-container d-flex gap-3">
        <!-- Movie Section (75%) -->
        <div class="movie-section col-md-9 p-4">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary mb-3">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $ticketData['movie']['thumbnail'] ?? 'https://via.placeholder.com/150' }}"
                         alt="Movie Thumbnail" class="movie-thumbnail">
                    <h5 class="movie-title mt-3 text-primary fw-bold">
                        {{ $ticketData['movie']['name'] ?? 'Mufasa: Vua Sư Tử' }}
                    </h5>
                </div>
                <div class="col-md-5">
                    <p><strong>Địa điểm:</strong> {{ $ticketData['cinema']['name'] ?? 'Hạ Long' }} - {{ $ticketData['cinema']['room'] ?? 'P201' }}</p>
                    <p><strong>Lịch chiếu:</strong> {{ $ticketData['showtime']['start_time'] ?? '04:48' }} - {{ $ticketData['showtime']['end_time'] ?? '07:07' }} ({{ $ticketData['showtime']['date'] ?? '14/02/2025' }})</p>
                    <p><strong>Thời lượng:</strong> {{ $ticketData['movie']['duration'] ?? '160 phút' }}</p>
                    <p><strong>Định dạng:</strong> {{ $ticketData['movie']['format'] ?? '2D Lồng Tiếng' }}</p>
                    <p><strong>Thể loại:</strong> {{ $ticketData['movie']['genre'] ?? 'Tâm lý' }}</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="bi bi-chair me-1"></i> Ghế ngồi</h6>
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
            <div class="combo-section mt-4">
                <h6><i class="bi bi-basket me-1"></i> Combo</h6>
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
                                        <p class="mb-0">{{ $combo['quantity'] }} x {{ $combo['price'] }} = <span class="price">{{ $combo['total_price'] }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">Không có combo</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Info Container (25%) -->
        <div class="info-container col-md-3 d-flex flex-column gap-3">
            <!-- Ticket Info Section -->
            <div class="ticket-info-section p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6><i class="bi bi-person me-1"></i> Thông tin người đặt</h6>
                    <a href="#" class="detail-link text-primary text-decoration-none">Xem chi tiết</a>
                </div>
                <hr>
                <p><strong>Tên:</strong> {{ $ticketData['user']['name'] ?? 'PhungHuy' }}</p>
                <p><strong>Email:</strong> {{ $ticketData['user']['email'] ?? 'huyphung@gmail.com' }}</p>
                <p><strong>SĐT:</strong> {{ $ticketData['user']['phone'] ?? '0987654326' }}</p>
            </div>

            <!-- Payment Section -->
            <div class="payment-section p-3">
                <h6><i class="bi bi-wallet me-1"></i> Thông tin thanh toán</h6>
                <hr>
                <p><strong>Thời gian:</strong> {{ $ticketData['payment_time'] ?? '21:31 - 05/03/2025' }}</p>
                <p><strong>Phương thức:</strong> {{ $ticketData['payment_name'] ?? 'ZaloPay' }}</p>
                <p><strong>Tổng tiền:</strong> <span class="price">{{ $ticketData['total_amount'] ?? '240666 VND' }}</span></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
@endsection
