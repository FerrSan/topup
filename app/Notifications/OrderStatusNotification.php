<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = match($this->order->status) {
            Order::STATUS_PAID => 'Payment Received',
            Order::STATUS_PROCESSING => 'Being Processed',
            Order::STATUS_SUCCESS => 'Completed Successfully',
            Order::STATUS_FAILED => 'Failed to Process',
            Order::STATUS_REFUNDED => 'Has Been Refunded',
            default => 'Updated',
        };

        return (new MailMessage)
            ->subject("Order #{$this->order->invoice_no} - {$status}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your order #{$this->order->invoice_no} status: {$status}")
            ->line("Product: {$this->order->product->name}")
            ->line("Amount: " . formatCurrency($this->order->grand_total))
            ->action('View Invoice', route('invoice.show', $this->order->invoice_no))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'invoice_no' => $this->order->invoice_no,
            'status' => $this->order->status,
            'amount' => $this->order->grand_total,
        ];
    }
}