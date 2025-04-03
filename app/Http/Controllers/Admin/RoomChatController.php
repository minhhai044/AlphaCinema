<?php

namespace App\Http\Controllers\Admin;

use App\Events\RealTimeChatEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoomChatRequest;
use App\Models\MessengerChat;
use App\Models\RoomChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class RoomChatController extends Controller
{

    public function room(string $id)
    {
        $RoomChat = RoomChat::with('messenges.user')->findOrFail($id);
        $RoomChats = RoomChat::query()->get();
        return view('admin.chats.chat', compact('RoomChat', 'RoomChats'));
    }

    public function messenger(RoomChatRequest $roomChatRequest, string $id)
    {
        try {
            $MessengerChat = MessengerChat::query()->create($roomChatRequest->validated());
            $roomChat = RoomChat::find($id);

            broadcast(new RealTimeChatEvent($roomChat, $MessengerChat))->toOthers();

            return response()->json([
                'data' => $MessengerChat
            ], 201);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
