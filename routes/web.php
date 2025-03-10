<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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

Route::get('auth/google/redirect', function (Request $request) {
    return Socialite::driver('google')->redirect();
});

Route::get('auth/google/callback', function (Request $request) {

    $googleUser = Socialite::driver('google')->user();

    $user = User::updateOrCreate(
        ['google_id' => $googleUser->id],
        [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => Str::password(12),
            'avatar' => $googleUser->avatar,
            'type_user' => 0,
        ]
    );

    $token = $user->createToken('authToken')->plainTextToken;

    // $authData = json_encode([
    //     'user' => $user->only(['id', 'name', 'email', 'avatar']), // Lọc bớt trường
    //     'token' => $token,
    //     'isLogin' => true
    // ]);

    // return redirect('http://localhost:3000')
    //     ->withCookie(cookie('auth_google', $authData, 60 * 24, '/', '.alphacinema.me', false, false));

    $authData = json_encode([
        'user' => $user->only(['id', 'name', 'email', 'avatar']), // Lọc bớt trường
        'token' => $token,
        'isLogin' => true
    ]);

    $authDataEncoded = base64_encode(json_encode($authData));

    // $encodedData = base64_encode($authData);

    // Log::info('AuthData size: ' . strlen($encodedData) . ' bytes');

    // return redirect('http://localhost:3000')->header('Auth-Info', $encodedData);

    // return redirect('http://localhost:3000')
    //     ->withCookie(cookie('auth_google', $authData, 60 * 24, '/', 'localhost', false, false));


    return redirect()->to('http://localhost:3000/auth/callback?data=' . urlencode($authDataEncoded));
});
