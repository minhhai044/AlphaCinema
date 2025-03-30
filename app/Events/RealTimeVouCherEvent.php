<?php

namespace App\Events;

use App\Models\Voucher;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        // return new Channel('voucher');
        return collect($this->userIds)
            ->map(fn($id) => new PrivateChannel('voucher.' . $id))
            ->toArray();
    }
    public function broadcastWith()
    {
        // Lấy user đang nhận event
        $currentUserId = last(request()->route()->parameters()); // Lấy ID user từ channel

        // Lọc chỉ những voucher của user đó
        $filteredVouchers = collect($this->voucher)
            ->filter(fn($vou) => $vou->user_id == $currentUserId)
            ->map(fn($vou) => [
                'code' => $vou->voucher->code,
                'title' => $vou->voucher->title,
                'start_date_time' => $vou->voucher->start_date_time,
                'end_date_time' => $vou->voucher->end_date_time,
                'discount' => $vou->voucher->discount,
                'quantity' => $vou->voucher->quantity,
            ])->values()
            ->toArray();

        Log::info("📢 [RealTimeVoucher] User ID: $currentUserId nhận data:", [
            'vouchers' => $filteredVouchers
        ]);

        return [
            'vouchers' => $filteredVouchers,
        ];
    }
}
