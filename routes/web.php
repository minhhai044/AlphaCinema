<?php

use App\Http\Controllers\Admin\AuthController as AuthAdmin;
use App\Http\Controllers\Api\MovieController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\QrCodeController;
use App\Mail\SendQrCodeMail;

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

Route::get("/", function () {
    return redirect('/admin');
})->middleware('checkLogin');

Route::get("/login", function () {
    if (auth()->check()) {
        return redirect()->route('admin.index'); // Or another route
    }
    return view('admin.auth.index');
})->name('showFormLogin');

Route::post("/login", [AuthAdmin::class, "login"])->name('login');

Route::get("/formSendMail", [AuthAdmin::class, "formSendMail"])->name('formSendMail');
Route::post("/sendMailPassword", [AuthAdmin::class, "sendMailPassword"])->name('sendMailPassword');

// Route::post("/login", [AuthAdmin::class, "login"])->name('login');

Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);

Route::get('auth/google/callback', [AuthController::class, 'googleCallBack']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/movies', [MovieController::class, 'index'])->name('api.movies.index');
});
