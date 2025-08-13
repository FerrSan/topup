<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameProduct;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\PaymentChannel;
use App\Services\PaymentService;
use App\Services\ValidationService;
use App\Jobs\ProcessTopupJob;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $paymentService;
    protected $validationService;

    public function __construct(
        PaymentService $paymentService,
        ValidationService $validationService
    ) {
        $this->paymentService = $paymentService;
        $this->validationService = $validationService;
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'product_id' => 'required|exists:game_products,id',
            'player_uid' => 'required|string|max:100',
            'player_server' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1|max:10',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'payment_channel' => 'required|string|exists:payment_channels,code',
            'buyer_note' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Get product and validate
            $product = GameProduct::with('game')
                ->active()
                ->findOrFail($validated['product_id']);

            if ($product->game_id != $validated['game_id']) {
                throw new \Exception('Product does not belong to selected game');
            }

            // Validate player account
            $playerValidation = $this->validationService->validatePlayer(
                $product->game->slug,
                $validated['player_uid'],
                $validated['player_server'] ?? null
            );

            if (!$playerValidation['valid']) {
                return back()->withErrors(['player_uid' => $playerValidation['message']]);
            }

            // Calculate pricing
            $price = $product->price * $validated['qty'];
            $discount = 0;
            $couponId = null;

            // Apply coupon if provided
            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])
                    ->active()
                    ->first();

                if ($coupon && $coupon->canBeUsedForGame($product->game_id)) {
                    $discount = $coupon->calculateDiscount($price);
                    $couponId = $coupon->id;
                }
            }

            // Get payment channel and calculate fee
            $paymentChannel = PaymentChannel::where('code', $validated['payment_channel'])
                ->active()
                ->firstOrFail();

            $fee = $paymentChannel->calculateFee($price - $discount);
            $grandTotal = $price - $discount + $fee;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'game_id' => $product->game_id,
                'product_id' => $product->id,
                'coupon_id' => $couponId,
                'qty' => $validated['qty'],
                'buyer_note' => $validated['buyer_note'] ?? null,
                'player_uid' => $validated['player_uid'],
                'player_server' => $validated['player_server'] ?? null,
                'player_name' => $playerValidation['player_name'] ?? null,
                'price' => $price,
                'discount' => $discount,
                'fee' => $fee,
                'grand_total' => $grandTotal,
                'status' => Order::STATUS_PENDING,
                'payment_provider' => $paymentChannel->provider,
                'payment_method' => $paymentChannel->code,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
            ]);

            // Log event
            $order->logEvent('CREATED', [
                'product' => $product->name,
                'qty' => $validated['qty'],
                'grand_total' => $grandTotal,
            ]);

            // Increment coupon usage
            if ($coupon) {
                $coupon->incrementUsage();
            }

            // Create payment
            $paymentResult = $this->paymentService->createPayment($order, $paymentChannel);

            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message']);
            }

            // Update order with payment data
            $order->update([
                'status' => Order::STATUS_WAITING_PAYMENT,
                'payment_token' => $paymentResult['token'] ?? null,
                'payment_ref' => $paymentResult['reference'] ?? null,
                'payment_url' => $paymentResult['redirect_url'] ?? null,
                'payment_data' => $paymentResult['data'] ?? null,
            ]);

            DB::commit();

            // Fire event
            event(new OrderCreated($order));

            // Redirect to payment
            if ($paymentResult['redirect_url']) {
                return redirect($paymentResult['redirect_url']);
            }

            return redirect()->route('invoice.show', $order->invoice_no);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to process order. Please try again.']);
        }
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'game_id' => 'nullable|exists:games,id',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired coupon code',
            ]);
        }

        if ($request->game_id && !$coupon->canBeUsedForGame($request->game_id)) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon cannot be used for selected game',
            ]);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount,
            'message' => 'Coupon applied successfully',
        ]);
    }
}