<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserVoucherController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\SeatTemplateControler;

use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\DayController;
use App\Http\Controllers\Admin\TyperoomController;
use App\Http\Controllers\Admin\TypeSeatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\RoomController;

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



Route::get('/export/{table}', [ExportController::class, 'index'])->name('export');

// Route Food
Route::group([
    'prefix' => 'foods',  // Tiền tố URL cho tất cả route
    'as' => 'foods.',     // Nhóm tên route (vd: foods.index, foods.store)
], function () {
    // Danh sách món ăn
    Route::get('/', [FoodController::class, 'index'])->name('index');



    // Hiển thị form tạo món ăn mới
    Route::get('create', [FoodController::class, 'create'])->name('create');

    // Lưu món ăn mới vào database
    Route::post('/', [FoodController::class, 'store'])->name('store');

    // Hiển thị form chỉnh sửa món ăn
    Route::get('{food}/edit', [FoodController::class, 'edit'])->name('edit');

    // Cập nhật món ăn
    Route::put('{food}', [FoodController::class, 'update'])->name('update');

    // Xóa mềm món ăn (soft delete)
    Route::delete('{food}', [FoodController::class, 'solfDestroy'])->name('solfDestroy');

    // Xóa vĩnh viễn món ăn
    Route::delete('{food}/forceDestroy', [FoodController::class, 'forceDestroy'])->name('forceDestroy');

    // Khôi phục món ăn đã xóa mềm
    Route::get('{food}/restore', [FoodController::class, 'restore'])->name('restore');

    // Hiển thị chi tiết món ăn phải khai báo cuối cùng trong route
    Route::get('{food}', [FoodController::class, 'show'])->name('show');
});
// End Route Food

// Route Combo
Route::group([
    'prefix' => 'combos',  // Tiền tố URL cho tất cả route
    'as' => 'combos.',     // Nhóm tên route (vd: foods.index, foods.store)
], function () {
    // Danh sách
    Route::get('/', [ComboController::class, 'index'])->name('index');

    // Hiển thị form tạo  mới
    Route::get('create', [ComboController::class, 'create'])->name('create');

    // Lưu  mới vào database
    Route::post('/', [ComboController::class, 'store'])->name('store');

    // Hiển thị form chỉnh sửa
    Route::get('{combo}/edit', [ComboController::class, 'edit'])->name('edit');

    // Cập nhật
    Route::put('{combo}', [ComboController::class, 'update'])->name('update');

    // Xóa mềm  (soft delete)
    // Route::delete('{combo}', [ComboController::class, 'solfDestroy'])->name('solfDestroy');

    // Xóa vĩnh viễn
    Route::delete('{combo}/forceDestroy', [ComboController::class, 'forceDestroy'])->name('forceDestroy');

    // Khôi phục  đã xóa mềm
    Route::get('{combo}/restore', [ComboController::class, 'restore'])->name('restore');

    // Hiển thị chi tiết  phải khai báo cuối cùng trong route
    Route::get('{combo}', [ComboController::class, 'show'])->name('show');
});

Route::resource('branches', BranchController::class);
Route::get('/admin/branches', [BranchController::class, 'index'])->name('admin.branches.index');

Route::resource('vouchers', VoucherController::class);

Route::resource('user-vouchers', UserVoucherController::class);

// Route::resource('typerooms', TyperoomController::class);


Route::resource('branches', BranchController::class);
Route::get('/admin/branches', [BranchController::class, 'index'])->name('admin.branches.index');

Route::resource('vouchers', VoucherController::class);

Route::resource('user-vouchers', UserVoucherController::class);

Route::group([
    'prefix' => 'typerooms',  // Tiền tố URL cho tất cả route
    'as' => 'typerooms.',     // Nhóm tên route (vd: foods.index, foods.store);
], function () {
    Route::get('/',[TyperoomController::class,'index'])->name('index');
    Route::post('/',[TyperoomController::class,'store'])->name('store');        
    Route::put('{type_room}/update',[TyperoomController::class,'update'])->name('update');    
    Route::delete('{type_room}/destroy',[TyperoomController::class,'destroy'])->name('destroy');  
});
// Route::resource('users', [])
Route::prefix('seat-templates')->group(function () {
    Route::get('/', [SeatTemplateControler::class, 'index'])->name('index.seat_templates');
    Route::post('/store', [SeatTemplateControler::class, 'store'])->name('store.seat_templates');
    Route::get('{id}/edit', [SeatTemplateControler::class, 'edit'])->name('edit.seat_templates');
    Route::put('{id}/update', [SeatTemplateControler::class, 'update'])->name('update.seat_templates');
    Route::put('{id}/update_seat', [SeatTemplateControler::class, 'update_seat'])->name('update_seat.seat_templates');
});

Route::prefix('rooms')->as('rooms.')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('index');
    
});
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

Route::resource('type_seats', TypeSeatController::class);

