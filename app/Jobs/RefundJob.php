<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\PaymentService;
use App\Services\NotificationService;
use App\Events\OrderRefunded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $tries = 3;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(PaymentService $paymentService, NotificationService $notificationService)
    {
        try {
            if (!$this->order->canBeRefunded()) {
                Log::warning("Order {$this->order->invoice_no} cannot be refunded");
                return;
            }

            $this->order->logEvent('REFUND_INITIATED');

            // Process refund through payment gateway
            $result = $paymentService->refund($this->order);

            if ($result['success']) {
                $this->order->update([
                    'status' => Order::STATUS_REFUNDED,
                    'refunded_at' => now(),
                ]);
                
                $this->order->logEvent('REFUND_COMPLETED', $result);
                
                // Return balance to user if logged in
                if ($this->order->user) {
                    $this->order->user->increment('balance', $this->order->grand_total);
                }
                
                event(new OrderRefunded($this->order));
                $notificationService->notifyOrderStatus($this->order);
                
                Log::info("Refund successful for order {$this->order->invoice_no}");
            } else {
                Log::error("Refund failed for order {$this->order->invoice_no}: {$result['message']}");
                
                if ($this->attempts() >= $this->tries) {
                    // Manual refund needed
                    $this->order->logEvent('REFUND_FAILED_MANUAL_NEEDED', $result);
                    // Notify admin
                }
            }
        } catch (\Exception $e) {
            Log::error("RefundJob error for order {$this->order->invoice_no}: {$e->getMessage()}");
            throw $e;
        }
    }
}
