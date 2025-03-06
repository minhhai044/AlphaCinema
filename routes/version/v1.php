<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ComboFoodController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\SeatTemplateController;
use App\Http\Controllers\Api\V1\ShowtimeController;
use App\Http\Controllers\Api\V1\SlideShowController;
use Illuminate\Support\Facades\Route;




Route::post('signin', [AuthController::class, 'signIn']);
Route::post('signup', [AuthController::class, 'signUp']);
Route::get('{id}/showtime', [ShowtimeController::class, 'getByDate']);
Route::get('{slug}/movieShowTimes', [ShowtimeController::class, 'movieShowTimes']);
Route::get('{branch}/listMovies/{cinema}', [ShowtimeController::class, 'listMovies']);
Route::get('{movie}/listShowtimes', [ShowtimeController::class, 'listShowtimes']);
Route::get('{slug}/showtimeDetail', [ShowtimeController::class, 'showtimeDetail']);

Route::get('/slideshows', [SlideShowController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::put('{id}/active-showtime',      [ShowtimeController::class, 'activeShowtime']);

    Route::post('{id}/changeSeatStatus',    [ShowtimeController::class, 'changeSeatStatus']);
    
    Route::put('{id}/active-seat-template', [SeatTemplateController::class, 'activeSeatTemplate']);
    
    Route::put('{id}/active-room',          [RoomController::class, 'activeRoom']);

    Route::put('{id}/resetSuccessSeat',     [ShowtimeController::class, 'resetSuccessSeat']);
});

Route::get('list_combo', [ComboFoodController::class, 'list_combo']);

// Route::get('{id}/showtime',             [ShowtimeController::class, 'getByDate']);
// Route::put('{id}/active-showtime',      [ShowtimeController::class, 'activeShowtime']);
// Route::get('/listMovies',               [ShowtimeController::class, 'listMovies']);
// Route::get('{movie}/listShowtimes',     [ShowtimeController::class, 'listShowtimes']);
// Route::get('{slug}/showtimeDetail',     [ShowtimeController::class, 'showtimeDetail']);
// Route::post('{id}/changeSeatStatus',    [ShowtimeController::class, 'changeSeatStatus']);

// Route::put('{id}/active-seat-template', [SeatTemplateController::class, 'activeSeatTemplate']);

// Route::put('{id}/active-room',          [RoomController::class, 'activeRoom']);


Route::post('/payment',[PaymentController::class,'payment']);
Route::get('/checkout',[PaymentController::class,'checkout']);