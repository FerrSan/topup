<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class CheckMaintenanceMode
{
    protected $excludedRoutes = [
        'admin.*',
        'login',
        'logout',
    ];

    public function handle(Request $request, Closure $next)
    {
        $maintenanceMode = Setting::get('maintenance_mode', false);
        
        if ($maintenanceMode) {
            // Check if current route is excluded
            foreach ($this->excludedRoutes as $pattern) {
                if ($request->routeIs($pattern)) {
                    return $next($request);
                }
            }
            
            // Check if user is admin
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                return $next($request);
            }
            
            $maintenanceMessage = Setting::get('maintenance_message', 'We are currently under maintenance. Please check back later.');
            
            if ($request->expectsJson()) {
                return response()->json(['message' => $maintenanceMessage], 503);
            }
            
            return response()->view('maintenance', ['message' => $maintenanceMessage], 503);
        }

        return $next($request);
    }
}