<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleCheckout
{
    public function handle(Request $request, Closure $next)
    {
        $key = 'checkout:' . ($request->user() ? $request->user()->id : $request->ip());
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'error' => "Too many checkout attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }
        
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}