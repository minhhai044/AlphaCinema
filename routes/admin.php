<?php


use App\Http\Controllers\Admin\RoleController;


use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserVoucherController;
use App\Http\Controllers\Admin\DashBoardController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DayController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\MovieController;


use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ShowtimeController;


use App\Http\Controllers\Admin\TyperoomController;
use App\Http\Controllers\Admin\TypeSeatController;


use App\Http\Controllers\Admin\SeatTemplateControler;

use App\Http\Controllers\Admin\SiteSettingController;

use App\Http\Controllers\Admin\StatisticalController;



use App\Http\Controllers\Admin\SlideShowController;

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

Route::resource('slideshows', SlideShowController::class);
// Route Food
// Đảm bảo rằng route được khai báo trong nhóm `foods` nếu muốn đặt tên cho route đúng cách.
Route::group([
    'prefix' => 'foods',  // Tiền tố URL cho tất cả route
    'as' => 'foods.', // Thêm `admin` vào nhóm tên route
], function () {
    // Các route cho `food`
    Route::get('/', [FoodController::class, 'index'])->name('index');
    Route::get('create', [FoodController::class, 'create'])->name('create');
    Route::post('/', [FoodController::class, 'store'])->name('store');
    Route::get('{food}/edit', [FoodController::class, 'edit'])->name('edit');
    Route::put('{food}', [FoodController::class, 'update'])->name('update');
    Route::delete('{food}/destroy', [FoodController::class, 'destroy'])->name('destroy');
    // Route::get('{food}/restore', [FoodController::class, 'restore'])->name('restore');
    Route::get('{food}', [FoodController::class, 'show'])->name('show');
    // Cập nhật trạng thái "active" cho food
    Route::post('change-active', [FoodController::class, 'changeActive'])->name('change-active');
});


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

    // Xóa vĩnh viễn
    Route::delete('{combo}/destroy', [ComboController::class, 'destroy'])->name('destroy');

    // Hiển thị chi tiết  phải khai báo cuối cùng trong route
    Route::get('{combo}', [ComboController::class, 'show'])->name('show');
});



Route::resource('branches', BranchController::class);
Route::get('/admin/branches', [BranchController::class, 'index'])->name('admin.branches.index');

Route::resource('vouchers', VoucherController::class);

Route::resource('user-vouchers', UserVoucherController::class);

// Route::resource('typerooms', TyperoomController::class);
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
    Route::post('/', [SeatTemplateControler::class, 'store'])->name('store.seat_templates');
    Route::get('{id}/edit', [SeatTemplateControler::class, 'edit'])->name('edit.seat_templates');
    Route::put('{id}/update', [SeatTemplateControler::class, 'update'])->name('update.seat_templates');
    Route::put('{id}/update_seat', [SeatTemplateControler::class, 'update_seat'])->name('update_seat.seat_templates');
});

Route::prefix('rooms')->as('rooms.')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('index');
    Route::post('/', [RoomController::class, 'store'])->name('store');
    Route::get('{id}/show', [RoomController::class, 'show'])->name('show');
    Route::put('{id}/update', [RoomController::class, 'update'])->name('update');

});

Route::prefix('showtimes')->as('showtimes.')->group(function () {
    Route::get('/', [ShowtimeController::class, 'index'])->name('index');
    Route::get('{id}/create', [ShowtimeController::class, 'create'])->name('create');
    Route::post('/store', [ShowtimeController::class, 'store'])->name('store');
    Route::post('/delete', [ShowtimeController::class, 'delete'])->name('delete');
    Route::post('/copys', [ShowtimeController::class, 'copys'])->name('copys');
    Route::get('/copys', [ShowtimeController::class, 'getCopys'])->name('getCopys');
    Route::post('/storeCopies', [ShowtimeController::class, 'storeCopies'])->name('storeCopies');
});


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


Route::resource("roles", RoleController::class );



Route::resource('vouchers', VoucherController::class);

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



Route::prefix('movies')->name('movies.')->group(function () {
    // Danh sách phim
    Route::get('/', [MovieController::class, 'index'])->name('index');

    // Form tạo phim mới
    Route::get('create', [MovieController::class, 'create'])->name('create');

    // Xử lý lưu phim mới
    Route::post('/', [MovieController::class, 'store'])->name('store');

    // Hiển thị chi tiết phim
    Route::get('{movie}', [MovieController::class, 'show'])->name('show');

    // Form chỉnh sửa phim
    Route::get('{movie}/edit', [MovieController::class, 'edit'])->name('edit');

    // Xử lý cập nhật phim
    Route::put('{movie}', [MovieController::class, 'update'])->name('update');

    // Xóa phim
    Route::delete('{movie}', [MovieController::class, 'destroy'])->name('destroy');
});

Route::resource('days', DayController::class)->names([
    'index' => 'days.index',
    'create' => 'days.create',
    'store' => 'days.store',
    'destroy' => 'days.destroy',
]);
Route::post('days/update/{id}', [DayController::class, 'update']);

Route::resource('type_seats', TypeSeatController::class);

Route::post('days/update/{id}', [DayController::class, 'update'])->name('days.update');


Route::group([
    'prefix' => 'settings',  // Tiền tố URL cho tất cả route
    'as' => 'settings.',
],function (){
Route::get('/',[SiteSettingController::class,'index'])->name('index');
Route::put('/update/{id}',[SiteSettingController::class,'update'])->name('update');
Route::post('/reset',[SiteSettingController::class,'resetToDefault'])->name('reset');
});



Route::get('/statistical/cinemaRevenue', [StatisticalController::class, 'cinemaRevenue'])->name('statistical.cinemaRevenue');
// Route::get('/admin/statistical/cinemaRevenue', [App\Http\Controllers\Admin\StatisticalController::class, 'cinemaRevenue'])->name('statistical.cinemaRevenue');



Route::get('/export/{table}', [ExportController::class, 'export'])->name('export');
