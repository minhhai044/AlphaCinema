<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\UserController;
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

Route::resource('accounts', UserController::class);
// Xóa mềm  (soft delete)
Route::delete('accounts', [UserController::class, 'solfDestroy'])->name('solfDestroy');
// Route::prefix('accounts')->name('accounts.')->group(function() {

// });

Route::group([
    'prefix' => 'users',  // Tiền tố URL cho tất cả route
    'as' => 'users.',     // Nhóm tên route (vd: users.index, users.store)
], function () {
    // Danh sách user
    Route::get('/', [UserController::class, 'index'])->name('index');

    // Hiển thị form tạo user mới
    Route::get('create', [UserController::class, 'create'])->name('create');

    // Lưu user mới vào database
    Route::post('/', [UserController::class, 'store'])->name('store');

    // Hiển thị form chỉnh sửa user
    Route::get('{users}/edit', [UserController::class, 'edit'])->name('edit');

    // Cập nhật user
    Route::put('{users}', [UserController::class, 'update'])->name('update');

    // Xóa mềm user (soft delete)
    Route::delete('{users}', [UserController::class, 'solfDestroy'])->name('solfDestroy');

    // Xóa vĩnh viễn user
    // Route::delete('{users}/forceDestroy', [UserController::class, 'forceDestroy'])->name('forceDestroy');

    // Khôi phục user đã xóa mềm
    // Route::get('{users}/restore', [UserController::class, 'restore'])->name('restore');

    // Hiển thị chi tiết user phải khai báo cuối cùng trong route
    Route::get('{users}', [UserController::class, 'show'])->name('show');
});

