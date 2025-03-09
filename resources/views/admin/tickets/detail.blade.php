@extends('admin.layouts.master')

@section('style')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Chỉ giữ các style tùy chỉnh cần thiết */
        .movie-thumbnail {
            width: 30%;
            height: auto;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .combo-img {
            max-width: 40%;
            margin-right: 10px;
            vertical-align: middle;
        }

        .price {
            color: #007bff;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid ticket-container d-flex gap-3">
        <!-- Movie Section (75%) -->
        <div class="movie-section col-md-9 bg-white p-3 border rounded">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary mb-3">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
            <table class="movie-table table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th><i class="bi bi-film me-1"></i> Phim</th>
                        <th><i class="bi bi-clock me-1"></i> Suất chiếu</th>
                        <th><i class="bi bi-chair me-1"></i> Ghế ngồi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="movie-header text-center">
                                <img src="{{ $ticketData['movie']['thumbnail'] ?? 'https://via.placeholder.com/60' }}"
                                    alt="Movie Thumbnail" class="movie-thumbnail">
                                <h5 class="movie-title mt-2 text-primary">
                                    {{ $ticketData['movie']['name'] ?? 'Mufasa: Vua Sư Tử' }}
                                </h5>
                            </div>
                        </td>
                        <td>
                            <p><strong>Địa điểm:</strong> {{ $ticketData['cinema']['name'] ?? 'Hạ Long' }} -
                                {{ $ticketData['cinema']['room'] ?? 'P201' }}
                            </p>
                            <p><strong>Lịch chiếu:</strong> {{ $ticketData['showtime']['start_time'] ?? '04:48' }} -
                                {{ $ticketData['showtime']['end_time'] ?? '07:07' }}
                                ({{ $ticketData['showtime']['date'] ?? '14/02/2025' }})
                            </p>
                            <p><strong>Thời lượng:</strong> {{ $ticketData['movie']['duration'] ?? '160 phút' }}</p>
                            <p><strong>Định dạng:</strong> {{ $ticketData['movie']['format'] ?? '2D Lồng Tiếng' }}</p>
                            <p><strong>Thể loại:</strong> {{ $ticketData['movie']['genre'] ?? 'Tâm lý' }}</p>
                        </td>
                        <td>
                            <div class="ticket-section">
                                @if (is_array($ticketData['seats']) && !empty($ticketData['seats']['details']))
                                    <ul class="list-unstyled">
                                        @foreach ($ticketData['seats']['details'] as $seat)
                                            <li>{{ $seat['name'] }} - {{ $seat['price'] }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>{{ $ticketData['seats']['names'] }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="combo-section mt-3">
                <h6><i class="bi bi-basket me-1"></i> Combo</h6>
                <table class="combo-table table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Combo</th>
                            <th>Chi tiết</th>
                            <th>Số lượng x Giá</th>
                            <th>Giá combo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticketData['combos'] as $combo)
                            <tr>
                                <td>
                                    <img src="{{ $combo['image'] }}" alt="{{ $combo['name'] }}" class="combo-img">
                                    {{ $combo['name'] }}
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($combo['foods'] as $food)
                                            <li>{{ $food }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center">{{ $combo['quantity'] }} x {{ $combo['price'] }}</td>
                                <td class="price text-center">{{ $combo['total_price'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Không có combo</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info Container (25%) -->
        <div class="info-container col-md-3 d-flex flex-column gap-3">
            <!-- Ticket Info Section -->
            <div class="ticket-info-section bg-white p-3 border rounded">
                <div class="section-header d-flex justify-content-between align-items-center mb-2">
                    <h6><i class="bi bi-person me-1"></i> Thông tin người đặt</h6>
                    <a href="#" class="detail-link text-primary text-decoration-none">Xem chi tiết</a>
                </div>
                <hr>
                <div class="info-text">
                    <p><strong>Tên khách hàng:</strong> {{ $ticketData['user']['name'] ?? 'PhungHuy' }}</p>
                    <p><strong>Email:</strong> {{ $ticketData['user']['email'] ?? 'huyphung@gmail.com' }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $ticketData['user']['phone'] ?? '0987654326' }}</p>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="payment-section bg-white p-3 border rounded">
                <h6><i class="bi bi-wallet me-1"></i> Thông tin thanh toán</h6>
                <hr>
                <div class="payment-info-text">
                    <p><strong>Thanh toán vào lúc:</strong> {{ $ticketData['payment_time'] ?? '21:31 - 05/03/2025' }}</p>
                    <p><strong>Phương thức thanh toán:</strong> {{ $ticketData['payment_name'] ?? 'ZaloPay' }}</p>
                    <p><strong>Tổng tiền:</strong> <span
                            class="price">{{ $ticketData['total_amount'] ?? '240666 VND' }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
@endsection
