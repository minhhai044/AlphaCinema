<?php

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

Route::get('/', function () {
    // return redirect()->route('admin.index');
    return 'Hello from root';
});

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

Route::get('/test-session', function (Request $request) {
    Log::info("Cookies: ", $request->cookies->all());
    Log::info("Session: ", session()->all());

    return response()->json([
        'cookies' => $request->cookies->all(),
        'session' => session()->all(),
        'user' => Auth::user(),
    ]);
});

Route::get('/debug-user', function () {

    return response()->json([
        'session' => session()->all(),
        'auth_user' => Auth::user(), // Kiểm tra nếu Laravel lấy được user
    ]);
});

Route::get('/check-auth', function () {
    return response()->json(Auth::user());
});
