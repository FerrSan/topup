<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log only for authenticated users and important routes
        if (auth()->check() && $this->shouldLog($request)) {
            ActivityLog::create([
                'log_name' => 'web',
                'description' => $this->getDescription($request),
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => 'App\Models\User',
                'causer_id' => auth()->id(),
                'properties' => [
                    'route' => $request->route()->getName(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'parameters' => $request->except(['password', 'password_confirmation']),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }

    protected function shouldLog(Request $request)
    {
        $loggedRoutes = [
            'checkout.process',
            'profile.update',
            'order.cancel',
            'testimonial.store',
        ];

        return in_array($request->route()->getName(), $loggedRoutes);
    }

    protected function getDescription(Request $request)
    {
        $descriptions = [
            'checkout.process' => 'Processed checkout',
            'profile.update' => 'Updated profile',
            'order.cancel' => 'Cancelled order',
            'testimonial.store' => 'Submitted testimonial',
        ];

        return $descriptions[$request->route()->getName()] ?? 'Performed action';
    }
}