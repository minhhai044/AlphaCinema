<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VoucherExpiringSoon extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $voucher;

    /**
     * Tạo một phiên bản mới của thông điệp email.
     *
     * @param  $user
     * @param  $voucher
     * @return void
     */
    public function __construct($user, $voucher)
    {
        $this->user = $user;
        $this->voucher = $voucher;
    }

    /**
     * Xây dựng nội dung của email.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Voucher của bạn sắp hết hạn')
                    ->view('mail.sendMailVoucher');
    }
}
