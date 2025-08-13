<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TopupService
{
    protected $providers = [
        'default' => DefaultTopupProvider::class,
        'vendor1' => Vendor1TopupProvider::class,
        'vendor2' => Vendor2TopupProvider::class,
    ];

    public function process(Order $order)
    {
        try {
            // Get the appropriate provider for this game/product
            $provider = $this->getProvider($order->game->slug);
            
            // Process the topup
            $result = $provider->topup([
                'product_code' => $order->product->nominal_code,
                'customer_id' => $order->player_uid,
                'server_id' => $order->player_server,
                'quantity' => $order->qty,
                'reference_id' => $order->invoice_no,
            ]);

            if ($result['success']) {
                $order->update([
                    'status' => Order::STATUS_SUCCESS,
                    'completed_at' => now(),
                    'vendor_response' => $result['data'],
                ]);

                $order->logEvent('TOPUP_SUCCESS', $result);
                
                return [
                    'success' => true,
                    'message' => 'Topup processed successfully',
                    'data' => $result['data'],
                ];
            } else {
                $order->increment('retry_count');
                
                if ($order->retry_count >= 5) {
                    $order->update([
                        'status' => Order::STATUS_FAILED,
                        'vendor_response' => $result['data'] ?? ['error' => $result['message']],
                    ]);
                    
                    $order->logEvent('TOPUP_FAILED', $result);
                    
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Topup failed after maximum retries',
                        'should_refund' => true,
                    ];
                } else {
                    $order->logEvent('TOPUP_RETRY', $result);
                    
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Topup failed, will retry',
                        'should_retry' => true,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Topup error for order {$order->invoice_no}: {$e->getMessage()}");
            
            $order->increment('retry_count');
            
            if ($order->retry_count >= 5) {
                $order->update(['status' => Order::STATUS_FAILED]);
                $order->logEvent('TOPUP_FAILED', ['error' => $e->getMessage()]);
                
                return [
                    'success' => false,
                    'message' => 'Topup failed due to system error',
                    'should_refund' => true,
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Temporary error, will retry',
                'should_retry' => true,
            ];
        }
    }

    protected function getProvider($gameSlug)
    {
        // Logic to determine which provider to use
        // For now, use default provider
        $providerClass = $this->providers['default'];
        
        return new $providerClass();
    }
}   