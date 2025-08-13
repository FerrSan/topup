<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WebhookLog;
use App\Services\PaymentService;
use App\Jobs\ProcessTopupJob;
use App\Events\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handle(Request $request)
    {
        // Log webhook
        $webhookLog = WebhookLog::create([
            'provider' => 'xendit',
            'event_type' => $request->header('x-callback-event'),
            'reference_id' => $request->input('external_id'),
            'signature' => $request->header('x-callback-token'),
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'ip_address' => $request->ip(),
        ]);

        try {
            // Verify signature
            if (!$this->paymentService->verifyXenditSignature($request)) {
                throw new \Exception('Invalid signature');
            }

            $webhookLog->update(['verified' => true]);

            // Get order
            $order = Order::where('invoice_no', $request->input('external_id'))->first();
            
            if (!$order) {
                throw new \Exception('Order not found');
            }

            // Check idempotency
            if ($order->payment_ref === $request->input('id')) {
                $webhookLog->update(['processed' => true]);
                return response()->json(['status' => 'OK']);
            }

            DB::beginTransaction();

            $status = $request->input('status');

            if ($status === 'PAID' || $status === 'SETTLED') {
                $order->update([
                    'status' => Order::STATUS_PAID,
                    'payment_ref' => $request->input('id'),
                    'paid_at' => now(),
                    'payment_data' => array_merge(
                        $order->payment_data ?? [],
                        $request->all()
                    ),
                ]);

                $order->logEvent('WEBHOOK_PAID', $request->all());

                // Dispatch job to process topup
                ProcessTopupJob::dispatch($order)->delay(now()->addSeconds(5));

                // Fire event
                event(new OrderPaid($order));

            } elseif ($status === 'EXPIRED') {
                $order->update([
                    'status' => Order::STATUS_EXPIRED,
                    'payment_ref' => $request->input('id'),
                    'expired_at' => now(),
                ]);
                
                $order->logEvent('WEBHOOK_EXPIRED', $request->all());
                
            } elseif ($status === 'FAILED') {
                $order->update([
                    'status' => Order::STATUS_FAILED,
                    'payment_ref' => $request->input('id'),
                ]);
                
                $order->logEvent('WEBHOOK_FAILED', $request->all());
            }

            $webhookLog->update(['processed' => true]);
            DB::commit();

            return response()->json(['status' => 'OK']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit webhook error: ' . $e->getMessage());
            
            $webhookLog->markAsFailed($e->getMessage());
            
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}