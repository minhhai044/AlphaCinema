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

    $authData = json_encode([
        'user' => $user->only(['id', 'name', 'email', 'avatar']),
        'token' => $token,
        'isLogin' => true
    ]);

    $authDataEncoded = base64_encode(json_encode($authData));

    return redirect()->to('http://localhost:3000/auth/callback?data=' . urlencode($authDataEncoded));
});
