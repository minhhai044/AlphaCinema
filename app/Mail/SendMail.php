<?php

namespace App\Mail;

use Cloudinary\Cloudinary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Milon\Barcode\DNS1D;

class SendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user_name;
    private $total_price;
    private $movie_name;
    private $date;
    private $start_time;
    private $cinema_name;
    private $branch_name;
    private $seat_name;
    private $combo_name;
    private $food_name;
    private $code;

    public function __construct(
        $user_name,
        $total_price,
        $movie_name,
        $date,
        $start_time,
        $cinema_name,
        $branch_name,
        $seat_name = [],
        $combo_name = [],
        $food_name = [],
        $code
    ) {
        $this->user_name = $user_name;
        $this->total_price = $total_price;
        $this->movie_name = $movie_name;
        $this->date = $date;
        $this->start_time = $start_time;
        $this->cinema_name = $cinema_name;
        $this->branch_name = $branch_name;
        $this->seat_name = is_array($seat_name) ? $seat_name : [];
        $this->combo_name = is_array($combo_name) ? $combo_name : [];
        $this->food_name = is_array($food_name) ? $food_name : [];
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('tmhai2004@gmail.com', 'AlphaCinema'),
            replyTo: [new Address('tmhai2004@gmail.com', 'AlphaCinema')],
            subject: "AlphaCinema thông báo : Đặt vé thành công !!!",
            tags: ['transactional']
        );
    }

    public function content(): Content
    {
        // Tạo barcode base64
        $barcodeData = (new DNS1D())->getBarcodePNG($this->code, 'C128', 1.5, 50);

        // Upload trực tiếp barcode lên Cloudinary
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

        $uploadResult = $cloudinary->uploadApi()->upload('data:image/png;base64,' . $barcodeData, [
            'folder' => 'barcode'
        ]);

        $barcodeUrl = $uploadResult['secure_url'];

        return new Content(
            view: 'mail.sendMailOrder',
            with: [
                'user_name'   => $this->user_name,
                'total_price' => $this->total_price,
                'movie_name'  => $this->movie_name,
                'date'        => $this->date,
                'start_time'  => $this->start_time,
                'cinema_name' => $this->cinema_name,
                'branch_name' => $this->branch_name,
                'seat_name'   => $this->seat_name,
                'combo_name'  => $this->combo_name,
                'food_name'   => $this->food_name,
                'barcodeUrl'  => $barcodeUrl,
                'code'        => $this->code
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
