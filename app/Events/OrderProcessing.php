<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class OrderProcessing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** Queue khusus untuk siaran (opsional) */
    public string $broadcastQueue = 'broadcasts';

    public function __construct(
        public Order $order
    ) {}

    /** Channel tempat event disiarkan */
    public function broadcastOn(): PrivateChannel
    {
        // contoh: private channel "orders.INV123"
        return new PrivateChannel('orders.' . $this->order->invoice_no);
    }

    /** Nama event di sisi klien */
    public function broadcastAs(): string
    {
        return 'order.processing';
    }

    /** Data yang dikirim ke klien */
    public function broadcastWith(): array
    {
        return [
            'invoice_no' => $this->order->invoice_no,
            'order_id'   => $this->order->id,
            'status'     => $this->order->status,   // harusnya "PROCESSING"
            'game_id'    => $this->order->game_id,
            'product_id' => $this->order->product_id,
            'paid_at'    => optional($this->order->paid_at)?->toISOString(),
            'message'    => 'Order is being processed',
        ];
    }

    /** (Opsional) hanya siarkan jika status memang PROCESSING */
    public function broadcastWhen(): bool
    {
        return $this->order->status === 'PROCESSING';
    }
}
