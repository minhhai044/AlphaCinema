<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RealTimeSeatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $seat_id;
    public $status;
    public $user_id;

    public function __construct($seat_id, $status, $user_id)
    {
        $this->seat_id = $seat_id;
        $this->status = $status;
        $this->user_id = $user_id;
    }

    public function broadcastOn()
    {
        Log::info("{$this->seat_id}, status: {$this->status}");
        return new Channel('showtime');

        // return ['showtime'];
    }

    public function broadcastWith()
    {
        return [
            'seat_id' => $this->seat_id,
            'status' => $this->status,
            'user_id' => $this->user_id,
        ];
    }
}
