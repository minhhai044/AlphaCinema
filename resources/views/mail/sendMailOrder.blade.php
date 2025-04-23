<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Xác nhận đặt vé - AlphaCinema</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            padding: 30px;
            color: #333;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(to right, #5156be, #5156be);
            color: white;
            padding: 18px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border-radius: 12px 12px 0 0;
        }

        .content {
            padding: 25px 10px 10px 10px;
        }

        .ticket-info {
            background: #fafafa;
            padding: 18px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #eee;
        }

        .ticket-info p {
            margin: 6px 0;
            font-size: 15px;
        }

        .button {
            display: inline-block;
            padding: 12px 22px;
            margin-top: 25px;
            background: #4a60ff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            text-align: center;
            transition: background 0.3s;
        }

        .button:hover {
            background: #3c4ed9;
        }

        .footer {
            text-align: center;
            padding: 12px;
            font-size: 13px;
            color: #888;
            margin-top: 30px;
        }

        .qr-code {
            text-align: center;
            margin-top: 25px;
        }

        .qr-code img {
            width: 50%;
            height: auto;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .qr-code p code {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 15px;
            display: inline-block;
            margin-top: 5px;
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
                <p><strong>🛋️ Phòng chiếu:</strong> {{ $room }}</p>
                <p><strong>⏰ Giờ chiếu:</strong> {{ $start_time }}</p>
                <p><strong>🏢 Rạp:</strong> {{ $cinema_name }} - Chi Nhánh {{ $branch_name }}</p>
                <p><strong>🪑 Ghế:</strong> {{ implode(', ', $seat_name) }}</p>
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
                <img src="cid:qrcode.png" alt="Barcode vé của bạn">
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
