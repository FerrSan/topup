<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBannedUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_banned) {
            Auth::logout();
            
            return redirect()->route('login')
                ->withErrors(['banned' => 'Your account has been suspended. Reason: ' . Auth::user()->ban_reason]);
        }

        return $next($request);
    }
}