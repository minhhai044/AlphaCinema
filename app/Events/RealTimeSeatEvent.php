<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RealTimeSeatEvent implements ShouldBroadcast
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
        // Log::info("{$this->seat_id}, status: {$this->status}");
        // return new Channel('showtime');

        $start = microtime(true);
        Log::info("📢 Start Broadcasting");
        // return [new Channel('showtime')];

        $channel = new Channel('showtime');
        Log::info("✅ Finished Broadcasting in: " . (microtime(true) - $start) . " seconds");
        return $channel;
    }

    public function broadcastWith()
    {
        $start = microtime(true);

        $data =
            [
                'seat_id' => $this->seat_id,
                'status' => $this->status,
                'user_id' => $this->user_id,
            ];

        Log::info('Time to prepare Pusher data: ' . (microtime(true) - $start) . ' seconds');
        return $data;
    }
}
