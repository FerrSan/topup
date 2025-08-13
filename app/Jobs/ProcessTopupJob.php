<?php
// app/Jobs/ProcessTopupJob.php
namespace App\Jobs;

use App\Models\Order;
use App\Services\TopupService;
use App\Services\NotificationService;
use App\Events\OrderProcessing;
use App\Events\OrderCompleted;
use App\Events\OrderFailed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessTopupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $tries = 5;
    public $backoff = [10, 30, 60, 120, 300]; // Exponential backoff

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(TopupService $topupService, NotificationService $notificationService)
    {
        try {
            // Update status to processing
            $this->order->update([
                'status' => Order::STATUS_PROCESSING,
                'processed_at' => now(),
            ]);
            
            $this->order->logEvent('TOPUP_STARTED');
            event(new OrderProcessing($this->order));

            // Process the topup
            $result = $topupService->process($this->order);

            if ($result['success']) {
                // Success
                event(new OrderCompleted($this->order));
                $notificationService->notifyOrderStatus($this->order);
                
                Log::info("Topup successful for order {$this->order->invoice_no}");
            } else {
                // Failed but may retry
                if ($result['should_retry'] ?? false) {
                    // Job will be retried automatically
                    $this->release($this->backoff[$this->attempts() - 1] ?? 300);
                } elseif ($result['should_refund'] ?? false) {
                    // Dispatch refund job
                    RefundJob::dispatch($this->order)->delay(now()->addMinutes(5));
                    event(new OrderFailed($this->order));
                    $notificationService->notifyOrderStatus($this->order);
                }
                
                Log::warning("Topup failed for order {$this->order->invoice_no}: {$result['message']}");
            }
        } catch (\Exception $e) {
            Log::error("ProcessTopupJob error for order {$this->order->invoice_no}: {$e->getMessage()}");
            
            if ($this->attempts() >= $this->tries) {
                $this->order->update(['status' => Order::STATUS_FAILED]);
                $this->order->logEvent('TOPUP_FAILED', ['error' => $e->getMessage()]);
                
                RefundJob::dispatch($this->order)->delay(now()->addMinutes(5));
                event(new OrderFailed($this->order));
                $notificationService->notifyOrderStatus($this->order);
            }
            
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("ProcessTopupJob failed completely for order {$this->order->invoice_no}: {$exception->getMessage()}");
        
        $this->order->update(['status' => Order::STATUS_FAILED]);
        $this->order->logEvent('TOPUP_FAILED', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
