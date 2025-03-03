<?php

use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\FoodController;
use App\Http\Controllers\Api\V1\ComboFoodController;

use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\SeatTemplateController;
use App\Http\Controllers\Api\V1\ShowtimeController;

use App\Http\Controllers\Api\V1\SlideShowController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.users.index');
});

// Route::get('{branch}/listMovies/{cinema}', [ShowtimeController::class, 'listMovies']);


Route::post('signin', [AuthController::class, 'signIn']);
Route::post('signup', [AuthController::class, 'signUp']);
Route::get('{id}/showtime', [ShowtimeController::class, 'getByDate']);
Route::get('{slug}/movieShowTimes', [ShowtimeController::class, 'movieShowTimes']);
Route::get('/listMovies', [ShowtimeController::class, 'listMovies']);
Route::get('{movie}/listShowtimes', [ShowtimeController::class, 'listShowtimes']);
Route::get('{slug}/showtimeDetail', [ShowtimeController::class, 'showtimeDetail']);

Route::get('/slideshows', [SlideShowController::class, 'index']);

Route::prefix('branchs')->group(function () {
    Route::get('/', [BranchController::class, 'index']);
});

// Route::post('ticket', [TicketController::class, 'createTicket']);
Route::get('/get-cinemas', [TicketController::class, 'getCinemas']);
// Route::get('/get-movies', [TicketController::class, 'getMovies']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('tickets', [TicketController::class, 'createTicket']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);


    Route::post('{id}/changeSeatStatus',    [ShowtimeController::class, 'changeSeatStatus']);

    Route::put('{id}/resetSuccessSeat',     [ShowtimeController::class, 'resetSuccessSeat']);
});
Route::put('{id}/active-seat-template', [SeatTemplateController::class, 'activeSeatTemplate']);
Route::put('{id}/active-room',          [RoomController::class, 'activeRoom']);
Route::get('/foods', [FoodController::class, 'index']);
Route::get('list_combo', [ComboFoodController::class, 'list_combo']);
Route::put('{id}/active-showtime',      [ShowtimeController::class, 'activeShowtime']);
