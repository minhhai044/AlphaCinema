<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserVoucherController;
use App\Http\Controllers\Admin\DashBoardController;
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

Route::resource('branches', BranchController::class);
Route::get('/admin/branches', [BranchController::class, 'index'])->name('admin.branches.index');

Route::resource('vouchers', VoucherController::class);

Route::resource('user-vouchers', UserVoucherController::class);

// Route::resource('users', [])
