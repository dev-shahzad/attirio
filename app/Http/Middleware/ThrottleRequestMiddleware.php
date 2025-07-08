<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;


class ThrottleRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'user:' . Auth::id() ?? $request->ip();


        if (RateLimiter::tooManyAttempts($key, 2)) {
            return response()->json([
                'message' => 'Too many requests. Please wait a minute.',
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }


        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
