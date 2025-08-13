<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TrackReferral
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref')) {
            Cookie::queue('referral_code', $request->get('ref'), 60 * 24 * 30); // 30 days
        }

        return $next($request);
    }
}