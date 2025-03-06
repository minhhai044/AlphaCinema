@extends('admin.layouts.master')

@section('style')
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        .ticket-card {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #eee;
        }

        /* Movie Table */
        .movie-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .movie-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 10px;
            text-align: left;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .movie-table td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
            color: #555;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }

        .movie-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .movie-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0;
        }

        .movie-thumbnail {
            width: 30%;
            height: 30%;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .movie-info-text p,
        .movie-table td p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .movie-info-text strong,
        .movie-table td strong {
            color: #333;
            font-weight: 600;
        }

        /* Combo Table */
        .combo-section {
            margin-top: 20px;
        }

        .combo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .combo-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .combo-table td {
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #555;
            border: 1px solid #eee;
        }

        .combo-table td:first-child {
            text-align: left;
            display: flex;
            align-items: center;
        }

        .combo-img {
            max-width: 40%;
            margin-right: 40%;
            vertical-align: middle;
        }

        /* Payment Section */
        .payment-section {
            margin-top: 20px;
            padding: 10px;
            border-top: 1px solid #eee;
        }

        .payment-section h6 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .payment-info-text p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .payment-info-text strong {
            color: #333;
            font-weight: 600;
        }

        .price {
            color: #007bff;
            font-weight: bold;
        }

        @media (max-width: 768px) {

            .movie-table th,
            .movie-table td,
            .combo-table th,
            .combo-table td {
                display: block;
                width: 100%;
                text-align: left;
            }

            .movie-table thead,
            .combo-table thead {
                display: none;
            }

            .movie-table td,
            .combo-table td {
                border-bottom: none;
                padding: 5px 10px;
            }

            .movie-table tr,
            .combo-table tr {
                border-bottom: 1px solid #eee;
                margin-bottom: 10px;
                display: block;
            }
        }
    </style>
@endsection

@section('content')
    <div class="ticket-card">
        <!-- Movie Section -->
        <table class="movie-table">
            <thead>
                <tr>
                    <th>Phim</th>
                    <th>Suất chiếu</th>
                    <th>Ghế ngồi</th>
                    <th>Tổng tiền ghế</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- Phim -->
                    <td>
                        <div class="movie-header">
                            <img src="{{ $ticketData['movie']['thumbnail'] ?? 'https://via.placeholder.com/60' }}"
                                alt="Movie Thumbnail" class="movie-thumbnail">
                            <div class="movie-info-text">
                                <h5 class="movie-title">{{ $ticketData['movie']['name'] }}</h5>
                            </div>
                        </div>

                    </td>
                    <!-- Suất chiếu -->
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
                    <!-- Ghế ngồi -->
                    <td>
                        <p>{{ $ticketData['seats'] ?? 'E2' }}</p>
                    </td>
                    <!-- Tổng tiền ghế -->
                    <td>
                        <p class="price">{{ $ticketData['ticket_price'] ?? '112.000' }} VND</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Combo Section -->
        <div class="combo-section">
            <h6>Combo</h6>
            <table class="combo-table">
                <thead>
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
                                <ul style="list-style-type: disc; margin: 0; padding-left: 20px;">
                                    @foreach ($combo['foods'] as $food)
                                        <li>{{ $food }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $combo['quantity'] }} x {{ $combo['price'] }} VND</td>
                            <td class="price">{{ $combo['total_price'] }} VND</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Không có combo</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Payment Section -->
        <div class="payment-section">
            <h6>Thông tin đặt vé</h6>
            <div class="payment-info-text">
                <p><strong>Tên khách hàng:</strong> {{ $ticketData['user']['name']  }}</p>
                <p><strong>Email:</strong> {{ $ticketData['user']['email'] }}</p>
                <p><strong>Số điện thoại:</strong> {{ $ticketData['user']['phone']  }}</p>
            </div>
            <h6>Thông tin thanh toán</h6>
            <div class="payment-info-text">
                <p><strong>Thời gian thanh toán:</strong> {{ $ticketData['payment_time'] }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ $ticketData['payment_name']  }}</p>
                <p><strong>Tổng tiền:</strong> <span class="price">{{ $ticketData['total_amount'] }} VND</span>
                </p>
            </div>
        </div>
    </div>
@endsection
