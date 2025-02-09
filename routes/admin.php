<?php

use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\SeatTemplateControler;
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
Route::prefix('seat-templates')->group(function () {
    Route::get('/', [SeatTemplateControler::class, 'index'])->name('index.seat_templates');
    Route::post('/store', [SeatTemplateControler::class, 'store'])->name('store.seat_templates');
    Route::get('{id}/edit', [SeatTemplateControler::class, 'edit'])->name('edit.seat_templates');
    Route::put('{id}/update', [SeatTemplateControler::class, 'update'])->name('update.seat_templates');
    Route::put('{id}/update_seat', [SeatTemplateControler::class, 'update_seat'])->name('update_seat.seat_templates');

});