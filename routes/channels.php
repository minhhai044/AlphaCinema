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

// Broadcast::channel('notification.branch.{branchId}.cinema.{cinemaId}', function ($user, $branchId, $cinemaId) {
//     return (int)$user->branch_id === (int)$branchId && (int)$user->cinema_id === (int)$cinemaId;
// });


Broadcast::channel('notification.all', function ($user) {
    return !$user->branch_id && !$user->cinema_id; // system admin
});

Broadcast::channel('notification.branch.{branchId}', function ($user, $branchId) {
    return $user->branch_id == $branchId && !$user->cinema_id; // quản lý chi nhánh
});

Broadcast::channel('notification.cinema.{cinemaId}', function ($user, $cinemaId) {
    return $user->cinema_id == $cinemaId && !$user->branch_id; // quản lý rạp
});

Broadcast::channel('voucher', function () {
    return true;
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
