<?php

use App\Http\Controllers\Api\V1\ShowtimeController;
use Illuminate\Support\Facades\Route;


Route::get('/listMovies', [ShowtimeController::class, 'listMovies']);
Route::get('{movie}/listShowtimes', [ShowtimeController::class, 'listShowtimes']);
Route::get('{slug}/showtimeDetail', [ShowtimeController::class, 'showtimeDetail']);
Route::post('{id}/changeSeatStatus', [ShowtimeController::class, 'changeSeatStatus']);