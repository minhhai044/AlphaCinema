<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessengerChat;
use App\Models\RoomChat;
use Illuminate\Http\Request;

class RoomChatController extends Controller
{

    public function room(string $id)
    {
        
        $RoomChat = RoomChat::query()->findOrFail($id);

        return view('admin.chats.chat',compact('RoomChat'));
    }
}
