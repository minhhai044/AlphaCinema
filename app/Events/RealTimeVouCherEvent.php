<?php

namespace App\Events;

use App\Models\Voucher;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeVouCherEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $voucher;
    public $userIds;
    public function __construct($voucher, $userIds)
    {
        $this->voucher = $voucher;
        $this->userIds = $userIds;
    }

    public function broadcastOn()
    {
        return new Channel('voucher');
    }

    public function broadcastWith()
    {
        return [
            'code' => $this->voucher->code,
            'title' => $this->voucher->title,
            'start_date_time' => $this->voucher->start_date_time,
            'end_date_time' => $this->voucher->end_date_time,
            'discount' => $this->voucher->discount,
            'quantity' => $this->voucher->quantity,
            'user_id' => $this->userIds
        ];
    }
}
