<?php

use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\SeatTemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('{id}/active-seat-template',[SeatTemplateController::class,'activeSeatTemplate']);

// Route::get('/cinemas',[CinemaController::class,'index']);
// Route::get('{id}/showcinemas',[CinemaController::class,'show']);
// Route::post('/storecinemas',[CinemaController::class,'store']);
// Route::put('{id}/updatecinemas',[CinemaController::class,'update']);
// Route::delete('{id}/deletecinemas',[CinemaController::class,'delete']);
