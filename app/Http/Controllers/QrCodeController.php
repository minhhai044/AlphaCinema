<?php

namespace App\Http\Controllers;

use App\Mail\SendQrCodeMail;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class QrCodeController extends Controller
{
    public function generateQrCode()
    {
        $qrCode = new QrCode('https://alphacinema.me/admin');
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Lưu mã QR vào thư mục public
        $imagePath = 'qr_codes/qrcode.png';
        $result->saveToFile(public_path($imagePath));

        return $imagePath;
    }

    public function sendQrCodeEmail()
    {
        // Tạo mã QR và lấy URL hình ảnh
        $imagePath = $this->generateQrCode();

        // Gửi email
        Mail::to(['dangvanson210297@gmail.com'])->send(new SendQrCodeMail($imagePath));

        return response()->json(['message' => 'Email sent successfully']);
    }
}
