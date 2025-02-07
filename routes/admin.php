<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\RankController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashBoardController::class, 'index'])->name('index');

Route::resource('cinemas', CinemaController::class);
Route::resource('ranks', RankController::class);

// Route::resource('users', [])
