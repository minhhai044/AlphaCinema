<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RoleController;

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserVoucherController;
use App\Http\Controllers\Admin\DashBoardController;

// use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DayController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\MovieController;

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\RoomChatController;
use App\Http\Controllers\Admin\ShowtimeController;

use App\Http\Controllers\Admin\TyperoomController;
use App\Http\Controllers\Admin\TypeSeatController;

use App\Http\Controllers\Admin\SeatTemplateControler;

use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TicketController;

use App\Http\Controllers\Admin\StatisticalController;

use App\Http\Controllers\Admin\SlideShowController;

use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

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

Route::get('/',                             [DashBoardController::class, 'index'])->name('index');
Route::post("/logout",                      [AuthController::class, "logout"])->name("logout");

/**
 * Route cho Rạp
 * 
 */
Route::resource('cinemas',              CinemaController::class);
Route::post('/cinemas/{id}/toggle',         [CinemaController::class, 'toggleStatus'])->name('cinemas.toggle');
Route::get('/get-cinemas/{branch_id}',      [CinemaController::class, 'getCinemasByBranch']);

/**
 * Route cho Rank
 * 
 */
Route::resource('ranks',                RankController::class);

/**
 * Route cho Slide Show
 * 
 */
Route::resource('slideshows',          SlideShowController::class);

/**
 * Route cho Food
 * 
 */
Route::group([
    'prefix' => 'foods', 
    'as' => 'foods.',
], function () {
    Route::get('/',                         [FoodController::class, 'index'])->name('index');
    Route::get('create',                    [FoodController::class, 'create'])->name('create');
    Route::post('/',                        [FoodController::class, 'store'])->name('store');
    Route::get('{food}/edit',               [FoodController::class, 'edit'])->name('edit');
    Route::put('{food}',                    [FoodController::class, 'update'])->name('update');
    Route::delete('{food}/destroy',         [FoodController::class, 'destroy'])->name('destroy');
    Route::get('{food}',                    [FoodController::class, 'show'])->name('show');
    Route::post('change-active',            [FoodController::class, 'changeActive'])->name('change-active');
});

/**
 * Route cho Combo
 * 
 */
Route::group([
    'prefix' => 'combos',  
    'as' => 'combos.',    
], function () {
    Route::get('/',                         [ComboController::class, 'index'])->name('index');
    Route::get('create',                    [ComboController::class, 'create'])->name('create');
    Route::post('/',                        [ComboController::class, 'store'])->name('store');
    Route::get('{combo}/edit',              [ComboController::class, 'edit'])->name('edit');
    Route::put('{combo}',                   [ComboController::class, 'update'])->name('update');
    Route::delete('{combo}/destroy',        [ComboController::class, 'destroy'])->name('destroy');
    Route::get('{combo}',                   [ComboController::class, 'show'])->name('show');
});



/**
 * Route cho Chi nhánh
 * 
 */
Route::post('/branches/change-active',      [BranchController::class, 'changeActive'])->name('branches.change-active');
Route::resource('branches',             BranchController::class);
Route::post('/branches/{id}/toggle',        [BranchController::class, 'toggleStatus'])->name('branches.toggle');


/**
 * Route cho loại phòng
 * 
 */
Route::group([
    'prefix' => 'typerooms',
    'as' => 'typerooms.',
], function () {
    Route::get('/',                         [TyperoomController::class, 'index'])->name('index');
    Route::post('/',                        [TyperoomController::class, 'store'])->name('store');
    Route::put('{type_room}/update',        [TyperoomController::class, 'update'])->name('update');
    Route::delete('{type_room}/destroy',    [TyperoomController::class, 'destroy'])->name('destroy');
});

/**
 * Route cho Phòng chiếu
 * 
 */
Route::prefix('rooms')->as('rooms.')->group(function () {
    Route::get('/',                         [RoomController::class, 'index'])->name('index');
    Route::post('/',                        [RoomController::class, 'store'])->name('store');
    Route::get('{id}/show',                 [RoomController::class, 'show'])->name('show');
    Route::put('{id}/update',               [RoomController::class, 'update'])->name('update');
});
/**
 * Route cho Suất chiếu
 * 
 */
Route::prefix('showtimes')->as('showtimes.')->group(function () {
    Route::get('/',                         [ShowtimeController::class, 'index'])->name('index');
    Route::get('{id}/create',               [ShowtimeController::class, 'create'])->name('create');
    Route::post('/store',                   [ShowtimeController::class, 'store'])->name('store');
    Route::post('/delete',                  [ShowtimeController::class, 'delete'])->name('delete');
    Route::post('/copys',                   [ShowtimeController::class, 'copys'])->name('copys');
    Route::get('/copys',                    [ShowtimeController::class, 'getCopys'])->name('getCopys');
    Route::post('/storeCopies',             [ShowtimeController::class, 'storeCopies'])->name('storeCopies');
    Route::get('{id}/createList',           [ShowtimeController::class, 'createList'])->name('createList');
    Route::post('{id}/multipleSelect',      [ShowtimeController::class, 'multipleSelect'])->name('multipleSelect');
    Route::post('{id}/storePremium',        [ShowtimeController::class, 'storePremium'])->name('storePremium');
    Route::get('{id}/show',                 [ShowtimeController::class, 'show'])->name('show');
});

/**
 * Route cho Users
 * 
 */
Route::group([
    'prefix' => 'users',
    'as' => 'users.',
], function () {
    Route::get('/',                         [UserController::class, 'index'])->name('index');
    Route::get('create',                    [UserController::class, 'create'])->name('create');
    Route::post('/',                        [UserController::class, 'store'])->name('store');
    Route::get('edit/{user}',               [UserController::class, 'edit'])->name('edit');
    Route::put('{user}',                    [UserController::class, 'update'])->name('update');
    Route::delete('{user}',                 [UserController::class, 'softDestroy'])->name('softDestroy');
    Route::get('{user}',                    [UserController::class, 'show'])->name('show');
});

/**
 * Route cho Roles
 * 
 */

Route::resource("roles",                RoleController::class)->middleware("checkSystemAdmin");

/**
 * Route cho Vouchers
 * 
 */
Route::post('/vouchers/{id}/toggle',        [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle');
Route::resource('user-vouchers',        UserVoucherController::class);
Route::resource('vouchers',             VoucherController::class);

/**
 * Route cho Seat Template
 * 
 */

Route::prefix('seat-templates')->group(function () {
    Route::get('/',                         [SeatTemplateControler::class, 'index'])->name('index.seat_templates');
    Route::post('/store',                   [SeatTemplateControler::class, 'store'])->name('store.seat_templates');
    Route::get('{id}/edit',                 [SeatTemplateControler::class, 'edit'])->name('edit.seat_templates');
    Route::put('{id}/update',               [SeatTemplateControler::class, 'update'])->name('update.seat_templates');
    Route::put('{id}/update_seat',          [SeatTemplateControler::class, 'update_seat'])->name('update_seat.seat_templates');
});



/**
 * Route cho Movies
 * 
 */

Route::prefix('movies')->name('movies.')->group(function () {
    Route::get('/',                         [MovieController::class, 'index'])->name('index');
    Route::get('create',                    [MovieController::class, 'create'])->name('create');
    Route::post('/',                        [MovieController::class, 'store'])->name('store');
    Route::get('{movie}',                   [MovieController::class, 'show'])->name('show');
    Route::get('{movie}/edit',              [MovieController::class, 'edit'])->name('edit');
    Route::put('{movie}',                   [MovieController::class, 'update'])->name('update');
    Route::delete('{movie}',                [MovieController::class, 'destroy'])->name('destroy');
});


/**
 * Route cho Days
 * 
 */

Route::resource('days', DayController::class)->names([
    'index' => 'days.index',
    'create' => 'days.create',
    'store' => 'days.store',
    'destroy' => 'days.destroy',
]);
Route::post('days/update/{id}',             [DayController::class, 'update']);
Route::put('days/update/{id}',              [DayController::class, 'update'])->name('days.update');

/**
 * Route cho TypeSeat
 * 
 */
Route::resource('type_seats',           TypeSeatController::class);

/**
 * Route cho Settings
 * 
 */
Route::group([
    'prefix' => 'settings',
    'as' => 'settings.',
], function () {
    Route::get('/',                         [SiteSettingController::class, 'index'])->name('index');
    Route::put('/update/{id}',              [SiteSettingController::class, 'update'])->name('update');
    Route::post('/reset',                   [SiteSettingController::class, 'resetToDefault'])->name('reset');
});

/**
 * Route cho thống kê
 * 
 */
Route::group([
    'prefix' => 'statistical',
    'as' => 'statistical.',
], function () {
    Route::get('/cinemaRevenue',            [StatisticalController::class, 'cinemaRevenue'])->name('cinemaRevenue');
    Route::get('/comboRevenue',             [StatisticalController::class, 'comboRevenue'])->name('comboRevenue');
    Route::get('/foodRevenue',              [StatisticalController::class, 'foodRevenue'])->name('foodRevenue');
    Route::get('/ticketRevenue',            [StatisticalController::class, 'ticketRevenue'])->name('ticketRevenue');
});

/**
 * Route cho bộ lọc thống kê
 * 
 */
Route::get('/combo-revenue',                [StatisticalController::class, 'comboRevenue'])->name('combo.revenue');
Route::get('/food-revenue',                 [StatisticalController::class, 'foodRevenue'])->name('food.revenue');
Route::get('/ticket-revenue',               [StatisticalController::class, 'ticketRevenue'])->name('ticket.revenue');
Route::get('/ticket/revenuenew',            [StatisticalController::class, 'ticketRevenueNew'])->name('ticket.revenuenew');
Route::get('/statistical/cinemas',          [StatisticalController::class, 'getCinemasByBranch'])->name('statistical.cinemas');
/**
 * Route cho bộ lọc thống kê
 */
Route::get('/export/{table}',               [ExportController::class, 'export'])->name('export');

/**
 * Route Ticket
 * 
 */
Route::group([
    'prefix' => 'tickets',
    'as' => 'tickets.',
], function () {
    Route::get('/',                         [TicketController::class, 'index'])->name('index');
    Route::get('/test',                     [TicketController::class, 'print']);
});
Route::get('/tickets/{ticket}/detail',      [TicketController::class, 'show'])->name('tickets.show');
Route::get('print/tickets/{id}',            [TicketController::class, 'getTicketByID']);
Route::get('print/tickets/combo/{id}',      [TicketController::class, 'getComboFoodById']);
Route::post('/tickets/change-status',       [TicketController::class, 'changeStatus'])->name('admin.tickets.change-status');
Route::get('/tickets/{code}/check-exists',  [TicketController::class, 'checkExists']);
Route::get('/change-password',              [AuthController::class, 'showChangePasswordForm'])->name('change-password.form');
Route::post('/change-password',             [AuthController::class, 'changePassword'])->name('change-password');

/**
 * Route Realtime Phòng Chat
 * 
 */
Route::group([
    'prefix' => 'roomchats',
    'as' => 'roomchats.',
], function () {
    Route::get('{roomId}/',                 [RoomChatController::class, 'room'])->name('show');
    Route::post('{roomId}/',                [RoomChatController::class, 'messenger'])->name('messenger');
});
