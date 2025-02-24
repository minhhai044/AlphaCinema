<?php

use App\Http\Controllers\Api\ShowtimeController;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\SeatTemplateController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ComboController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\API\SiteSettingController;
use App\Http\Controllers\Api\UpdateActiveController;
use App\Http\Controllers\Api\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users',[AuthController::class,'signUp']);
Route::get('/users',[AuthController::class,'signIn']);

Route::put('{id}/active-seat-template',[SeatTemplateController::class,'activeSeatTemplate']);
Route::put('{id}/active-room',[RoomController::class,'activeRoom']);
Route::get('{id}/showtime',[ShowtimeController::class,'getByDate']);
Route::put('{id}/active-showtime',[ShowtimeController::class,'activeShowtime']);

Route::get('/cinemas',[CinemaController::class,'index']);
Route::get('{id}/showcinemas',[CinemaController::class,'show']);
Route::post('/storecinemas',[CinemaController::class,'store']);
Route::put('{id}/updatecinemas',[CinemaController::class,'update']);
Route::delete('{id}/deletecinemas',[CinemaController::class,'delete']);

// Route::prefix('movies')->group(function () {
//     Route::get('/', [MovieController::class, 'index'])->name('api.movies.index');
//     Route::get('/{movie}', [MovieController::class, 'show'])->name('api.movies.show');
//     Route::post('/', [MovieController::class, 'store'])->name('api.movies.store');
//     Route::put('/{movie}', [MovieController::class, 'update'])->name('api.movies.update');
//     Route::delete('/{movie}', [MovieController::class, 'delete'])->name('api.movies.delete');
// });

Route::prefix('movies')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('api.movies.index');
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.users.index');
    Route::post('signin', [AuthController::class, 'signIn'])->name('api.users.signin');
    Route::post('signup', [AuthController::class, 'signUp'])->name('api.users.signup');
});

Route::prefix('foods')->group(function () {
    Route::get('/', [FoodController::class, 'index'])->name('api.foods.index');
});
Route::prefix('combos')->group(function () {
    Route::get('/', [ComboController::class, 'index'])->name('api.combos.index');
});

// Change active
Route::post('food/change-active',       [UpdateActiveController::class, 'food'])->name('food.change-active');
Route::post('combos/change-active',     [UpdateActiveController::class, 'combo'])->name('combos.change-active');
// Route::prefix('admin/movies')->group(function () {
//     Route::get('/', [MovieController::class, 'index']);
//     Route::post('/', [MovieController::class, 'store']);
//     Route::put('/{id}', [MovieController::class, 'update']);
//     Route::delete('/{id}', [MovieController::class, 'destroy']);
// });
Route::get('/settings',[SiteSettingController::class,'index']);
