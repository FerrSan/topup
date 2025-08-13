<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;

class EnsureOrderBelongsToUser
{
    public function handle(Request $request, Closure $next)
    {
        $orderId = $request->route('order') ?? $request->route('orderId');
        
        if ($orderId) {
            $order = Order::find($orderId);
            
            if (!$order) {
                abort(404, 'Order not found');
            }
            
            if ($order->user_id && $order->user_id !== auth()->id()) {
                abort(403, 'You are not authorized to access this order');
            }
        }

        return $next($request);
    }
}