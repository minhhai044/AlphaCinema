<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Xác nhận quên mật khẩu - AlphaCinema</title>
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
            width: 120px;
            height: 120px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            🎟 Xác nhận quên mật khẩu - AlphaCinema
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $user_name }}</strong>,</p>

            <div class="ticket-info">
                <p><strong>Mã OTP:</strong> {{ $otp }}</p>
                <p><strong>❗Chú ý:</strong> Mã otp này sẽ hết hạn sau 5 phút</p>
                <p><strong>❗Chú ý:</strong> Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.</p>
            </div>

            <p>Trân trọng,</p>
            <p><strong>Đội ngũ AlphaCinema</strong></p>
        </div>
        <div class="footer">
            &copy; 2025 AlphaCinema. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>

</html>
