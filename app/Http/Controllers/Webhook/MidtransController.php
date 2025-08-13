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

class MidtransController extends Controller
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
            'provider' => 'midtrans',
            'event_type' => $request->input('transaction_status'),
            'reference_id' => $request->input('order_id'),
            'signature' => $request->header('X-Midtrans-Signature'),
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'ip_address' => $request->ip(),
        ]);

        try {
            // Verify signature
            if (!$this->paymentService->verifyMidtransSignature($request)) {
                throw new \Exception('Invalid signature');
            }

            $webhookLog->update(['verified' => true]);

            // Get order
            $order = Order::where('invoice_no', $request->input('order_id'))->first();
            
            if (!$order) {
                throw new \Exception('Order not found');
            }

            // Check idempotency
            if ($order->payment_ref === $request->input('transaction_id')) {
                $webhookLog->update(['processed' => true]);
                return response()->json(['status' => 'OK']);
            }

            DB::beginTransaction();

            $transactionStatus = $request->input('transaction_status');
            $fraudStatus = $request->input('fraud_status');

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->handlePaymentSuccess($order, $request);
                }
            } else if ($transactionStatus == 'settlement') {
                $this->handlePaymentSuccess($order, $request);
            } else if ($transactionStatus == 'pending') {
                $order->update([
                    'status' => Order::STATUS_WAITING_PAYMENT,
                    'payment_ref' => $request->input('transaction_id'),
                ]);
                $order->logEvent('WEBHOOK_PENDING', $request->all());
            } else if (in_array($transactionStatus, ['deny', 'cancel', 'failure'])) {
                $order->update([
                    'status' => Order::STATUS_FAILED,
                    'payment_ref' => $request->input('transaction_id'),
                ]);
                $order->logEvent('WEBHOOK_FAILED', $request->all());
            } else if ($transactionStatus == 'expire') {
                $order->update([
                    'status' => Order::STATUS_EXPIRED,
                    'payment_ref' => $request->input('transaction_id'),
                    'expired_at' => now(),
                ]);
                $order->logEvent('WEBHOOK_EXPIRED', $request->all());
            }

            $webhookLog->update(['processed' => true]);
            DB::commit();

            return response()->json(['status' => 'OK']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans webhook error: ' . $e->getMessage());
            
            $webhookLog->markAsFailed($e->getMessage());
            
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function handlePaymentSuccess($order, $request)
    {
        $order->update([
            'status' => Order::STATUS_PAID,
            'payment_ref' => $request->input('transaction_id'),
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
    }
}