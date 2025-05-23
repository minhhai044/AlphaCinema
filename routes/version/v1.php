<?php

use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\FoodController;
use App\Http\Controllers\Api\V1\ComboFoodController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\SeatTemplateController;
use App\Http\Controllers\Api\V1\ShowtimeController;
use App\Http\Controllers\Api\V1\SlideShowController;
use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('api.users.index');
});

/**
 * Route Api Auth
 *
 * signin : Đăng nhập
 *
 * signup : Đăng ký tài khoản
 */

Route::post('signin',               [AuthController::class, 'signIn']);
Route::post('checkUserResgister',   [AuthController::class, 'checkUserResgister']);
Route::post('signup',               [AuthController::class, 'signUp']);
/**
 *  Route Auth
 */

Route::post('send-otp',             [AuthController::class, 'sendOtp']);
Route::post('verify-otp',           [AuthController::class, 'verifyOtp']);
Route::post('reset-password',       [AuthController::class, 'resetPassword']);
Route::post('/verify-email',        [AuthController::class, 'verifyEmail']);
Route::post('/confirm-email',       [AuthController::class, 'confirmVerifyEmail']);
Route::post('roles/change-active',  [RoleController::class, 'changActive']);
Route::post('users/change-active',  [AdminUserController::class, 'changActiveStatus']);

/**
 * Route Api showtime
 *
 * showtime : Lấy danh sách suất chiếu theo ngày
 *
 * {slug}/movieShowTimes : Chi tiết showtime theo phim By Slug
 *
 * listMovies : Danh sách movie
 *
 * {slug}/showtimeDetail : Xem chi tiết suất chiếu
 */

Route::get('{id}/showtime',         [ShowtimeController::class, 'getByDate']);
Route::get('{slug}/movieShowTimes', [ShowtimeController::class, 'movieShowTimes']);
Route::get('/listMovies',           [ShowtimeController::class, 'listMovies']);
Route::get('{movie}/listShowtimes', [ShowtimeController::class, 'listShowtimes']);
Route::get('{slug}/showtimeDetail', [ShowtimeController::class, 'showtimeDetail']);
Route::put('{id}/active-showtime',  [ShowtimeController::class, 'activeShowtime']);


// xuất chiếu đặc biệt
Route::get('/movies-coming-soon',           [ShowtimeController::class, 'listComingSoon']);
Route::get('/movies-now-showing',           [ShowtimeController::class, 'listNowShowing']);
Route::get('/movies-special',           [ShowtimeController::class, 'listMoviesSpecial']);
//
Route::get('{slug}/moviesNowShowing',           [ShowtimeController::class, 'moviesNowShowing']);
Route::get('{slug}/moviesSpecialShowtimes',           [ShowtimeController::class, 'moviesSpecialShowtimes']);

/**
 * Api slideshows
 *
 * slideshows : Lấy image slide
 */
Route::get('/slideshows',           [SlideShowController::class, 'index']);


/**
 * Api branchs
 *
 * branchs : Lấy danh sách chi nhánh + rạp
 *
 * get-cinemas : Lấy danh sách rạp
 *
 */
Route::get('/branchs',              [BranchController::class, 'index']);
Route::get('/get-cinemas',          [TicketController::class, 'getCinemas']);
/**
 * Api rank
 *
 * ranksJson : Lấy rank
 *
 */
Route::get('/ranksJson',            [RankController::class, 'getRanksJson']);

Route::middleware('auth:sanctum')->group(function () {

    /**
     *  Route Auth
     * 
     */
    Route::post('/logout',                  [AuthController::class, 'logout']);
    Route::get('/getRank',                  [AuthController::class, 'getUserRank']);
    Route::post('{user}/update-profile',    [AuthController::class, 'updateProfile']);
    Route::post('/change-password',         [AuthController::class, 'changePassword']);
    Route::get('/pointhistory',             [AuthController::class, 'getUserPointHistory']);

    /**
     *  Route Ticket
     * 
     */
    Route::post('tickets',                  [TicketController::class, 'createTicket']);
    Route::get('/ticket-by-user',           [TicketController::class, 'getTicketByUser']);
    Route::get('/{code}/order',             [TicketController::class, 'findByCode']);

    /**
     *  Route Voucher
     * 
     */
    Route::get('/voucher',                  [AuthController::class, 'getUserVoucher']);
    /**
     *  Route Showtimes
     * 
     */
    Route::post('{id}/changeSeatStatus',    [ShowtimeController::class, 'changeSeatStatus']);
    Route::post('{id}/resetSuccessSeat',    [ShowtimeController::class, 'resetSuccessSeat']);
});

/**
 *  Route Settings
 * 
 */
Route::put('{id}/active-seat-template',     [SeatTemplateController::class, 'activeSeatTemplate']);
/**
 *  Route Room
 * 
 */
Route::put('{id}/active-room',              [RoomController::class, 'activeRoom']);
/**
 *  Route Food + Combo
 * 
 */
Route::get('/foods',                        [FoodController::class, 'index']);
Route::get('list_combo',                    [ComboFoodController::class, 'list_combo']);

/**
 *  Route Settings
 * 
 */
Route::get('/settings',                     [SiteSettingController::class, 'index']);

/**
 *  Route Payment
 * 
 */

Route::post('{payment}/payment',            [PaymentController::class, 'payment']);
Route::get('/checkout',                     [PaymentController::class, 'checkout']);
