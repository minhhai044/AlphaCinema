<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy token từ cookie
        $token = $request->cookie('user_token');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken && $accessToken->tokenable) {
                Auth::login($accessToken->tokenable);
            }
        }

        return $next($request);
    }
}
