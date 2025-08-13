<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\GameProduct;
use App\Models\Coupon;
use App\Models\PaymentChannel;
use App\Services\PaymentService;
use App\Services\ValidationService;
use App\Jobs\ProcessTopupJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends Controller
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

    public function index(Request $request)
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['game', 'product'])
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json($orders);
    }

    public function show($invoiceNo)
    {
        $order = Order::with(['game', 'product', 'events'])
            ->where('invoice_no', $invoiceNo)
            ->firstOrFail();

        // Check authorization
        if ($order->user_id && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($order);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'product_id' => 'required|exists:game_products,id',
            'player_uid' => 'required|string|max:100',
            'player_server' => 'nullable|string|max:100',
            'qty' => 'required|integer|min:1|max:10',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'payment_channel' => 'required|string|exists:payment_channels,code',
            'buyer_note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $product = GameProduct::with('game')
                ->active()
                ->findOrFail($request->product_id);

            // Validate player
            $playerValidation = $this->validationService->validatePlayer(
                $product->game->slug,
                $request->player_uid,
                $request->player_server
            );

            if (!$playerValidation['valid']) {
                return response()->json(['error' => $playerValidation['message']], 400);
            }

            // Calculate pricing
            $price = $product->price * $request->qty;
            $discount = 0;
            $couponId = null;

            // Apply coupon
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->active()
                    ->first();

                if ($coupon && $coupon->canBeUsedForGame($product->game_id)) {
                    $discount = $coupon->calculateDiscount($price);
                    $couponId = $coupon->id;
                }
            }

            // Get payment channel
            $paymentChannel = PaymentChannel::where('code', $request->payment_channel)
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
                'qty' => $request->qty,
                'buyer_note' => $request->buyer_note,
                'player_uid' => $request->player_uid,
                'player_server' => $request->player_server,
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
            ]);

            // Log event
            $order->logEvent('CREATED', [
                'source' => 'api',
                'product' => $product->name,
                'qty' => $request->qty,
            ]);

            // Create payment
            $paymentResult = $this->paymentService->createPayment($order, $paymentChannel);

            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message']);
            }

            // Update order
            $order->update([
                'status' => Order::STATUS_WAITING_PAYMENT,
                'payment_token' => $paymentResult['token'] ?? null,
                'payment_ref' => $paymentResult['reference'] ?? null,
                'payment_url' => $paymentResult['redirect_url'] ?? null,
                'payment_data' => $paymentResult['data'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'invoice_no' => $order->invoice_no,
                'payment_url' => $paymentResult['redirect_url'] ?? null,
                'order' => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Failed to create order',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkStatus($invoiceNo)
    {
        $order = Order::where('invoice_no', $invoiceNo)->firstOrFail();

        // Check authorization
        if ($order->user_id && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $paymentStatus = $this->paymentService->checkPaymentStatus($order);

        return response()->json([
            'order_status' => $order->status,
            'payment_status' => $paymentStatus,
        ]);
    }
}