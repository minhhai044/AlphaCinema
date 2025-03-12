<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::check() && Auth::user()->isAdmin()) {
        //     return $next($request);
        // }

        // abort(403);


        // $token = Cookie::get('admin_token');
        $token = $request->cookie('admin_token');

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Tìm token trong bảng `personal_access_tokens`
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Invalid token'], Response::HTTP_UNAUTHORIZED);
        }

        // Lấy user từ token
        $user = $accessToken->tokenable;

        // dd($user);

        if (!$user || $user->type_user != 1) { // Đảm bảo chỉ cho phép admin
            return response()->json(['message' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        // Xác thực user trong Auth
        Auth::login($user);

        return $next($request);
    }
}
