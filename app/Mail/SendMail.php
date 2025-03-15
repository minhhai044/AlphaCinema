<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class SendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
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
        $food_name = []
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
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(

            from: new Address('tmhai2004@gmail.com', 'AlphaCinema'),
            replyTo: [
                new Address('tmhai2004@gmail.com', 'AlphaCinema')
            ],

            subject: "AlphaCinema thông báo : Đặt vé thành công !!!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
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
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
