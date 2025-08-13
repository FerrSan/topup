<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckExpiredOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Find orders that should be expired
        $expiredOrders = Order::whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_WAITING_PAYMENT
            ])
            ->where('expired_at', '<=', now())
            ->get();

        foreach ($expiredOrders as $order) {
            $order->update(['status' => Order::STATUS_EXPIRED]);
            $order->logEvent('EXPIRED_BY_SYSTEM');
        }
    }
}
