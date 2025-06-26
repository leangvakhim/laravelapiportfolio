<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtCookieAuth
{
    public function handle($request, Closure $next)
    {
        try {
            if ($request->hasCookie('access_token')) {
                $token = $request->cookie('access_token');
                JWTAuth::setToken($token);
                $user = JWTAuth::authenticate();
                auth()->guard()->setUser($user);
            } else {
                return response()->json(['error' => 'Token not found'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
