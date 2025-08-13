<?php

namespace App\Services\Providers;

class DefaultTopupProvider
{
    protected $apiUrl;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->apiUrl = config('topup.providers.default.api_url');
        $this->apiKey = config('topup.providers.default.api_key');
        $this->apiSecret = config('topup.providers.default.api_secret');
    }

    public function topup(array $params)
    {
        // Simulate API call to topup provider
        // In production, this would make actual HTTP request
        
        try {
            // Generate signature
            $signature = $this->generateSignature($params);
            
            // Make API request
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'X-Signature' => $signature,
            ])->post($this->apiUrl . '/topup', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    return [
                        'success' => true,
                        'data' => $data,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $data['message'] ?? 'Topup failed',
                        'data' => $data,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to connect to topup provider',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function generateSignature($params)
    {
        ksort($params);
        $string = http_build_query($params) . $this->apiSecret;
        return hash('sha256', $string);
    }

    public function checkStatus($referenceId)
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get($this->apiUrl . '/status/' . $referenceId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Failed to check topup status: {$e->getMessage()}");
            return null;
        }
    }

    public function getBalance()
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get($this->apiUrl . '/balance');

            if ($response->successful()) {
                $data = $response->json();
                return $data['balance'] ?? 0;
            }

            return 0;
        } catch (\Exception $e) {
            Log::error("Failed to get balance: {$e->getMessage()}");
            return 0;
        }
    }
}