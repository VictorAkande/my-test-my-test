<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if ($token) {
            $pat = PersonalAccessToken::findToken($token);
            if ($pat && $pat->expires_at && Carbon::parse($pat->expires_at)->isPast()) {
                return response()->json(['error' => 'Token expired.'], 401);
            }
        }

        return $next($request);
    }
}
