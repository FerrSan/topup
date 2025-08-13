<?php
// app/Services/PaymentService.php
namespace App\Services;

use App\Models\Order;
use App\Models\PaymentChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $midtransServerKey;
    protected $midtransClientKey;
    protected $midtransIsProduction;
    protected $xenditSecretKey;

    public function __construct()
    {
        $this->midtransServerKey = config('payment.midtrans.server_key');
        $this->midtransClientKey = config('payment.midtrans.client_key');
        $this->midtransIsProduction = config('payment.midtrans.is_production');
        $this->xenditSecretKey = config('payment.xendit.secret_key');
    }

    public function createPayment(Order $order, PaymentChannel $channel)
    {
        switch ($channel->provider) {
            case 'midtrans':
                return $this->createMidtransPayment($order, $channel);
            case 'xendit':
                return $this->createXenditPayment($order, $channel);
            default:
                return [
                    'success' => false,
                    'message' => 'Payment provider not supported',
                ];
        }
    }

    protected function createMidtransPayment(Order $order, PaymentChannel $channel)
    {
        try {
            $baseUrl = $this->midtransIsProduction 
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $params = [
                'transaction_details' => [
                    'order_id' => $order->invoice_no,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'customer_details' => [
                    'first_name' => $order->user ? $order->user->name : 'Guest',
                    'email' => $order->user ? $order->user->email : 'guest@example.com',
                    'phone' => $order->user ? $order->user->phone : '',
                ],
                'item_details' => [
                    [
                        'id' => $order->product->id,
                        'price' => (int) $order->price / $order->qty,
                        'quantity' => $order->qty,
                        'name' => $order->product->name,
                    ],
                ],
                'enabled_payments' => $this->getMidtransEnabledPayments($channel),
                'callbacks' => [
                    'finish' => route('invoice.show', $order->invoice_no),
                ],
            ];

            if ($order->fee > 0) {
                $params['item_details'][] = [
                    'id' => 'FEE',
                    'price' => (int) $order->fee,
                    'quantity' => 1,
                    'name' => 'Payment Fee',
                ];
            }

            if ($order->discount > 0) {
                $params['item_details'][] = [
                    'id' => 'DISCOUNT',
                    'price' => -(int) $order->discount,
                    'quantity' => 1,
                    'name' => 'Discount',
                ];
            }

            $response = Http::withBasicAuth($this->midtransServerKey, '')
                ->post($baseUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'token' => $data['token'],
                    'redirect_url' => $data['redirect_url'],
                    'data' => $data,
                ];
            }

            throw new \Exception('Failed to create payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Midtrans payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function createXenditPayment(Order $order, PaymentChannel $channel)
    {
        try {
            $baseUrl = 'https://api.xendit.co/v2/invoices';

            $params = [
                'external_id' => $order->invoice_no,
                'amount' => (int) $order->grand_total,
                'description' => "Payment for {$order->product->name}",
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => $order->user ? $order->user->name : 'Guest',
                    'email' => $order->user ? $order->user->email : 'guest@example.com',
                    'mobile_number' => $order->user ? $order->user->phone : '',
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['email'],
                    'invoice_paid' => ['email'],
                ],
                'success_redirect_url' => route('invoice.show', $order->invoice_no),
                'failure_redirect_url' => route('invoice.show', $order->invoice_no),
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => $order->product->name,
                        'quantity' => $order->qty,
                        'price' => (int) $order->price / $order->qty,
                    ],
                ],
                'payment_methods' => $this->getXenditPaymentMethods($channel),
            ];

            $response = Http::withBasicAuth($this->xenditSecretKey, '')
                ->post($baseUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'reference' => $data['id'],
                    'redirect_url' => $data['invoice_url'],
                    'data' => $data,
                ];
            }

            throw new \Exception('Failed to create payment: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Xendit payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function getMidtransEnabledPayments($channel)
    {
        $mapping = [
            'qris' => ['other_qris'],
            'gopay' => ['gopay'],
            'ovo' => ['other_va'],
            'dana' => ['other_va'],
            'shopeepay' => ['shopeepay'],
            'bca_va' => ['BCA'],
            'bni_va' => ['BNI'],
            'bri_va' => ['BRI'],
            'mandiri_va' => ['MANDIRI'],
            'permata_va' => ['PERMATA'],
        ];

        return $mapping[$channel->code] ?? [];
    }

    public function verifyMidtransSignature($request)
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $serverKey = $this->midtransServerKey;
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = openssl_digest($input, 'sha512');
        
        return $signature === $request->header('X-Midtrans-Signature');
    }

    public function verifyXenditSignature($request)
    {
        $webhookId = $request->header('webhook-id');
        $webhookToken = config('payment.xendit.callback_token');
        $requestBody = $request->getContent();
        
        $signature = hash_hmac('sha256', $webhookId . $requestBody, $webhookToken);
        
        return $signature === $request->header('x-callback-token');
    }

    public function checkPaymentStatus(Order $order)
    {
        if ($order->payment_provider === 'midtrans') {
            return $this->checkMidtransStatus($order);
        } elseif ($order->payment_provider === 'xendit') {
            return $this->checkXenditStatus($order);
        }
        
        return null;
    }

    protected function checkMidtransStatus(Order $order)
    {
        $baseUrl = $this->midtransIsProduction
            ? "https://api.midtrans.com/v2/{$order->invoice_no}/status"
            : "https://api.sandbox.midtrans.com/v2/{$order->invoice_no}/status";

        $response = Http::withBasicAuth($this->midtransServerKey, '')
            ->get($baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    protected function checkXenditStatus(Order $order)
    {
        if (!$order->payment_ref) {
            return null;
        }

        $baseUrl = "https://api.xendit.co/v2/invoices/{$order->payment_ref}";

        $response = Http::withBasicAuth($this->xenditSecretKey, '')
            ->get($baseUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}