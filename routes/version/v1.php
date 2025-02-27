<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ShowtimeController;
use Illuminate\Support\Facades\Route;


Route::get('/listMovies', [ShowtimeController::class, 'listMovies']);
// Route::get('{movie}/listShowtimes', [ShowtimeController::class, 'listShowtimes']);
Route::get('{slug}/showtimeDetail', [ShowtimeController::class, 'showtimeDetail']);
Route::post('{id}/changeSeatStatus', [ShowtimeController::class, 'changeSeatStatus']);
Route::get('{slug}/movieShowTimes', [ShowtimeController::class, 'movieShowTimes']);
Route::put('{id}/resetSuccessSeat', [ShowtimeController::class, 'resetSuccessSeat']);
// new

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
