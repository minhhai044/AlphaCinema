<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class SendOtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    private $otp;
    private $user_name;
    /**
     * Create a new message instance.
     */
    public function __construct($otp, $user_name)
    {
        $this->otp = $otp;
        $this->user_name = $user_name;
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
            subject: 'AlphaCinema Thông Báo: Quên mật khẩu',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.sendMailOTP',
            with: [
                'otp' => $this->otp,
                'user_name' => $this->user_name
            ]
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
