<?php

use App\Http\Controllers\Admin\DashBoardController;

use App\Http\Controllers\Admin\DayController;
use App\Http\Controllers\Admin\MovieController;
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

// Route::resource('users', [])

Route::resource('movies', MovieController::class)->names([
    'index' => 'movies.index',
    'create' => 'movies.create',
    'store' => 'movies.store',
    'show' => 'movies.show',
    'edit' => 'movies.edit',
    'update' => 'movies.update',
    'destroy' => 'movies.destroy',
]);

Route::resource('days', DayController::class)->names([
    'index' => 'days.index',
    'create' => 'days.create',
    'store' => 'days.store',
    'destroy' => 'days.destroy',
]);

Route::post('days/update/{id}', [DayController::class, 'update']);