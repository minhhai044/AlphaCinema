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
use App\Http\Controllers\Api\VoucherController;

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

// Route::post('/users',[AuthController::class,'signUp']);
// Route::get('/users',[AuthController::class,'signIn']);



Route::prefix('movies')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('api.movies.index');
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.users.index');
});

Route::prefix('foods')->group(function () {
    Route::get('/', [FoodController::class, 'index'])->name('api.foods.index');
});
Route::prefix('combos')->group(function () {
    Route::get('/', [ComboController::class, 'index'])->name('api.combos.index');
});
Route::post('/admin/combos/update-status', [ComboController::class, 'updateStatus'])
    ->name('admin.combos.updateStatus');

// Change active
Route::post('food/change-active',[UpdateActiveController::class, 'food'])->name('food.change-active');
Route::post('combos/change-active',[UpdateActiveController::class, 'combo'])->name('combos.change-active');

Route::get('/settings',[SiteSettingController::class,'index']);

Route::middleware('auth:api')->get('/vouchers', [VoucherController::class, 'index']);
