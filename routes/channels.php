<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('showtime', function () {
    return true;
});

Broadcast::channel('voucher.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    Log::info($user);
    Log::info($roomId);
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
    ];
});