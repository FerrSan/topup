<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

Broadcast::channel('orders.{invoiceNo}', function ($user, $invoiceNo) {
    $order = Order::where('invoice_no', $invoiceNo)->first();
    
    if (!$order) {
        return false;
    }
    
    // Allow if guest order or order belongs to user
    return !$order->user_id || $order->user_id === $user->id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
