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
            background: #ff5733;
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
            width: 120px;
            height: 120px;
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
                <p><strong>🎬 Phim:</strong> Avengers: Endgame</p>
                <p><strong>📅 Ngày chiếu:</strong> 15/03/2025</p>
                <p><strong>⏰ Giờ chiếu:</strong> 19:30</p>
                <p><strong>🏢 Rạp:</strong> AlphaCinema - Chi Nhánh Hà Nội</p>
                <p><strong>🪑 Ghế:</strong> H12, H13</p>
                <p><strong>💰 Tổng tiền:</strong> 180.000 VNĐ</p>
            </div>

            <div class="qr-code">
                <p>📲 Vui lòng quét mã QR dưới đây để nhận vé:</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=123456789" alt="QR Code">
            </div>

            <p><a href="https://yourwebsite.com" class="button">Xem chi tiết vé</a></p>

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
