<?php
// app/Http/Middleware/VerifyWebhookSignature.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class VerifyWebhookSignature
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handle(Request $request, Closure $next, $provider)
    {
        $valid = false;

        switch ($provider) {
            case 'midtrans':
                $valid = $this->paymentService->verifyMidtransSignature($request);
                break;
            case 'xendit':
                $valid = $this->paymentService->verifyXenditSignature($request);
                break;
        }

        if (!$valid) {
            Log::warning("Invalid webhook signature from {$provider}", [
                'ip' => $request->ip(),
                'headers' => $request->headers->all(),
            ]);
            
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}