<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\SeatTemplateController;
use App\Http\Controllers\Api\V1\ShowtimeController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;




// new
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.users.index');
    Route::post('signin', [AuthController::class, 'signIn'])->name('api.users.signin');
    Route::post('signup', [AuthController::class, 'signUp'])->name('api.users.signup');
});

Route::post('ticket', [TicketController::class, 'createTicket']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('{slug}/movieShowTimes',     [ShowtimeController::class, 'movieShowTimes']);

    Route::get('{id}/showtime',             [ShowtimeController::class, 'getByDate']);
    Route::put('{id}/active-showtime',      [ShowtimeController::class, 'activeShowtime']);
    Route::get('/listMovies',               [ShowtimeController::class, 'listMovies']);
    Route::get('{movie}/listShowtimes',     [ShowtimeController::class, 'listShowtimes']);
    Route::get('{slug}/showtimeDetail',     [ShowtimeController::class, 'showtimeDetail']);
    Route::post('{id}/changeSeatStatus',    [ShowtimeController::class, 'changeSeatStatus']);

    Route::put('{id}/active-seat-template', [SeatTemplateController::class, 'activeSeatTemplate']);

    Route::put('{id}/active-room',          [RoomController::class, 'activeRoom']);
});
