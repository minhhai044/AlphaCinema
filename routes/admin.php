<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\TyperoomController;
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
// Route::resource('typerooms', TyperoomController::class);

Route::group([
    'prefix' => 'typerooms',  // Tiền tố URL cho tất cả route
    'as' => 'typerooms.',     // Nhóm tên route (vd: foods.index, foods.store);
], function () {
    Route::get('/',[TyperoomController::class,'index'])->name('index');
    Route::post('/',[TyperoomController::class,'store'])->name('store');    
    Route::get('create',[TyperoomController::class,'create'])->name('create');    
    Route::get('{type_room}/edit',[TyperoomController::class,'edit'])->name('edit');    
    Route::put('{type_room}/update',[TyperoomController::class,'update'])->name('update');    
    Route::delete('{type_room}/destroy',[TyperoomController::class,'destroy'])->name('destroy');    
});
// Route::resource('users', [])
