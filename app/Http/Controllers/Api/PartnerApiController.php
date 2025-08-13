<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\GameProduct;
use App\Services\TopupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PartnerApiController extends Controller
{
    protected $topupService;

    public function __construct(TopupService $topupService)
    {
        $this->topupService = $topupService;
    }

    public function topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string',
            'customer_id' => 'required|string',
            'server_id' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'reference_id' => 'required|string|unique:orders,invoice_no',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find product
            $product = GameProduct::where('nominal_code', $request->product_code)
                ->active()
                ->firstOrFail();

            // Create order
            $order = Order::create([
                'invoice_no' => $request->reference_id,
                'game_id' => $product->game_id,
                'product_id' => $product->id,
                'qty' => $request->quantity,
                'player_uid' => $request->customer_id,
                'player_server' => $request->server_id,
                'price' => $product->price * $request->quantity,
                'fee' => 0,
                'grand_total' => $product->price * $request->quantity,
                'status' => Order::STATUS_PAID,
                'payment_provider' => 'partner',
                'payment_method' => 'partner_api',
                'paid_at' => now(),
                'ip_address' => $request->ip(),
                'metadata' => [
                    'partner_api' => true,
                    'api_key' => substr($request->header('X-API-Key'), -4),
                ],
            ]);

            // Process topup
            $result = $this->topupService->process($order);

            DB::commit();

            return response()->json([
                'success' => $result['success'],
                'reference_id' => $order->invoice_no,
                'status' => $order->status,
                'message' => $result['message'] ?? 'Order processed',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function status($referenceId)
    {
        $order = Order::where('invoice_no', $referenceId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'reference_id' => $order->invoice_no,
            'status' => $order->status,
            'product' => $order->product->name,
            'customer_id' => $order->player_uid,
            'created_at' => $order->created_at->toIso8601String(),
            'completed_at' => $order->completed_at?->toIso8601String(),
        ]);
    }

    public function balance()
    {
        // This would connect to actual balance API
        // For now, return mock data
        return response()->json([
            'success' => true,
            'balance' => 10000000,
            'currency' => 'IDR',
        ]);
    }
}
