<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RealTimeNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $notification;
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn(): array
    {
        $channels = [];

        // Gửi tới quản lý rạp
        if (!empty($this->notification['cinema_id'])) {
            $channels[] = new Channel("notification.cinema." . $this->notification['cinema_id']);
        }

        // Gửi tới quản lý chi nhánh
        if (!empty($this->notification['branch_id'])) {
            $channels[] = new Channel("notification.branch." . $this->notification['branch_id']);
        }

        // Gửi tới system admin
        $channels[] = new Channel("notification.all");

        return $channels;
    }

    public function broadcastWith(): array
    {
        Log::info('📤 Đang broadcast sự kiện RealTimeNotificationEvent:', [
            'notification' => $this->notification
        ]);
        return [
            'notification' => $this->notification
        ];
    }
}
