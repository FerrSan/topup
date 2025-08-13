<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogOrderActivity
{
    public function handle($event)
    {
        $order = $event->order;
        $eventType = class_basename($event);
        
        Log::channel('orders')->info("Order Event: {$eventType}", [
            'invoice_no' => $order->invoice_no,
            'status' => $order->status,
            'user_id' => $order->user_id,
            'game_id' => $order->game_id,
            'product_id' => $order->product_id,
            'grand_total' => $order->grand_total,
        ]);
    }
}