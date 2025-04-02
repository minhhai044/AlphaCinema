<?php

namespace App\Events;

use App\Models\RoomChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RealTimeChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $roomChat;
    public function __construct(RoomChat $roomChat)
    {
        $this->roomChat = $roomChat;
    }
    // public function broadcastVia()
    // {

    //     Log::info('✅ broadcastVia được gọi cho RealTimeSeatEvent');


    //     return app(\Illuminate\Contracts\Broadcasting\Factory::class)->driver('pusher');
    // }

    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . $this->roomChat->id);
    }
}
