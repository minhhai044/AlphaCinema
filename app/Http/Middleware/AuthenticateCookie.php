<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = Cookie::get('sanctum_token');

            if(!$token){
                $accessToken = PersonalAccessToken::findToken($token);

                if($accessToken && $accessToken->tokenable){
                    Auth::login($accessToken->tokenable);
                    Log::info("Người dùng được xác thực qua cookie: ". $accessToken->tokenable->email);
                }else{
                    Log::error("Cookie không đúng");
                }
            }else{
                Log::error("Mã token không hợp lệ");
            }
        } catch (\Exception $e) {
            Log::error("Error in AuthenticateCookie middleware: " . $e->getMessage());
        }
        return $next($request);
    }
}
