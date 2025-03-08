@extends('admin.layouts.master')

@section('style')
    <style>
        /* General Styles */
        .ticket-container {
            display: flex;
            width: 100%;
            gap: 20px;
        }

        /* Sections */
        .movie-section {
            flex: 0 0 75%;
            background: #fff;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .info-container {
            flex: 0 0 25%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ticket-info-section {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .payment-section {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Header with Title and Link */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .section-header h6 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .detail-link {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .detail-link:hover {
            text-decoration: underline;
        }

        /* Table Styles */
        .movie-table,
        .combo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .movie-table th,
        .combo-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 10px;
            text-align: left;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .movie-table td,
        .combo-table td {
            padding: 10px;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #eee;
        }

        .combo-table td {
            text-align: center;
            border: 1px solid #eee;
        }

        .combo-table td:first-child {
            text-align: left;
            display: flex;
            align-items: center;
        }

        /* Movie Header */
        .movie-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .movie-title {
            font-size: 18px;
            font-weight: 600;
            color: #007bff;
            margin: 0;
            text-align: center;
        }

        .movie-thumbnail {
            width: 30%;
            height: 30%;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        /* Combo Styles */
        .combo-section {
            margin-top: 20px;
        }

        .combo-img {
            max-width: 40%;
            margin-right: 40%;
            vertical-align: middle;
        }

        /* Text Styles */
        .movie-info-text p,
        .movie-table td p,
        .ticket-info-section p,
        .payment-info-text p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .movie-info-text strong,
        .movie-table td strong,
        .ticket-info-section strong,
        .payment-info-text strong {
            color: #333;
            font-weight: 600;
        }

        .price {
            color: #007bff;
            font-weight: bold;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .ticket-container {
                flex-direction: column;
            }

            .movie-section,
            .info-container,
            .ticket-info-section,
            .payment-section {
                flex: 0 0 100%;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="ticket-container">

        <!-- Movie Section (75%) -->
        <div class="movie-section">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary mb-3">Quay lại</a>
            <table class="movie-table">
                <thead>
                    <tr>
                        <th>Phim</th>
                        <th>Suất chiếu</th>
                        <th>Ghế ngồi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="movie-header">
                                <img src="{{ $ticketData['movie']['thumbnail'] ?? 'https://via.placeholder.com/60' }}"
                                    alt="Movie Thumbnail" class="movie-thumbnail">
                                <div class="movie-info-text">
                                    <h5 class="movie-title">
                                        {{ $ticketData['movie']['name'] ?? 'Mufasa: Vua Sư Tử' }}
                                    </h5>
                                </div>
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
                                    <ul>
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
        </div>

        <!-- Info Container (25%) -->
        <div class="info-container">
            <!-- Ticket Info Section -->
            <div class="ticket-info-section">
                <div class="section-header">
                    <h6>Thông tin người đặt</h6>
                    <a href="#" class="detail-link">Xem
                        chi tiết</a>
                </div>
                    <hr>

                <div class="info-text">
                    <p><strong>Tên khách hàng:</strong> {{ $ticketData['user']['name'] ?? 'PhungHuy' }}</p>
                    <p><strong>Email:</strong> {{ $ticketData['user']['email'] ?? 'huyphung@gmail.com' }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $ticketData['user']['phone'] ?? '0987654326' }}</p>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="payment-section">
                <h6>Thông tin thanh toán</h6>
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
@endsection
