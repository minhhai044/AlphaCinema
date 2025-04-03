<?php

namespace App\Events;

use App\Models\MessengerChat;
use App\Models\RoomChat;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RealTimeChatEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $roomChat;
    public $messengerChat;
    public function __construct(RoomChat $roomChat, MessengerChat $messengerChat)
    {
        $this->roomChat = $roomChat;
        $this->messengerChat = $messengerChat;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . $this->roomChat->id);
    }
    public function broadcastAs()
    {
        return 'real-time-chat';
    }
    public function broadcastWith()
    {
        return [
            'roomChat' => [
                'id' => $this->roomChat->id,
                'name' => $this->roomChat->name,
            ],
            'messengerChat' => [
                'id' => $this->messengerChat->id,
                'messenge' => $this->messengerChat->messenge,
                'user_id' => $this->messengerChat->user_id,
                'name' => $this->messengerChat->user->name ?? 'Ẩn danh',
                'created_at' => $this->messengerChat->created_at->toDateTimeString(),
                'avatar' => $this->messengerChat->user->avatar
                    ? Storage::url($this->messengerChat->user->avatar)
                    : 'https://graph.facebook.com/4/picture?type=small',
            ]
        ];
    }
}
