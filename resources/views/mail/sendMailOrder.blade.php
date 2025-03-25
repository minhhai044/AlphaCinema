<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt vé - AlphaCinema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #ff5733;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
            text-align: left;
        }

        .ticket-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .ticket-info p {
            margin: 5px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            background: #5156be;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }

        .qr-code img {
            width: 180px;
            height: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .qr-code p {
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            🎟 Xác Nhận Đặt Vé - AlphaCinema
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $user_name }}</strong>,</p>
            <p>Cảm ơn bạn đã đặt vé tại <strong>AlphaCinema</strong>. Dưới đây là thông tin chi tiết về vé của bạn:</p>

            <div class="ticket-info">
                <p><strong>🎬 Phim:</strong> {{ $movie_name }}</p>
                <p><strong>📅 Ngày chiếu:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                <p><strong>⏰ Giờ chiếu:</strong> {{ $start_time }}</p>
                <p><strong>🏢 Rạp:</strong> {{ $cinema_name }} - Chi Nhánh {{ $branch_name }}</p>
                <p><strong>🪑 Ghế:</strong>
                    {{ implode(', ', $seat_name) }}
                </p>

                <p><strong>🛍 Combo:</strong>
                    {{ implode(', ', array_map(fn($combo) => "{$combo['name']} (x{$combo['quantity']})", $combo_name)) }}
                </p>

                <p><strong>🍿 Đồ ăn:</strong>
                    {{ implode(', ', array_map(fn($food) => "{$food['name']} (x{$food['quantity']})", $food_name)) }}
                </p>

                <p><strong>💰 Tổng tiền:</strong> {{ number_format($total_price) }} VNĐ</p>
            </div>

            <div class="qr-code">
                <p><strong>Mã vé của bạn:</strong></p>
                <img src="{{ $barcodeUrl }}" alt="Barcode vé của bạn">
                <p><code>{{ $code }}</code></p>
            </div>
            


            <p>Chúng tôi mong chờ được chào đón bạn tại rạp!</p>
            <p>Trân trọng,</p>
            <p><strong>Đội ngũ AlphaCinema</strong></p>
        </div>
        <div class="footer">
            &copy; 2025 AlphaCinema. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>

</html>
