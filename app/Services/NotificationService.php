<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $whatsappApiUrl;
    protected $whatsappToken;
    protected $whatsappPhoneId;

    public function __construct()
    {
        $this->whatsappApiUrl = config('services.whatsapp.api_url');
        $this->whatsappToken = config('services.whatsapp.token');
        $this->whatsappPhoneId = config('services.whatsapp.phone_number_id');
    }

    public function notifyOrderStatus(Order $order)
    {
        // Send email notification
        if ($order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }

        // Send WhatsApp notification
        if ($order->user && $order->user->phone) {
            $this->sendWhatsApp($order->user->phone, $this->getOrderMessage($order));
        }

        // Send push notification if enabled
        $this->sendPushNotification($order);
    }

    protected function sendWhatsApp($phone, $message)
    {
        try {
            if (!$this->whatsappApiUrl || !$this->whatsappToken) {
                return false;
            }

            $response = Http::withToken($this->whatsappToken)
                ->post("{$this->whatsappApiUrl}/{$this->whatsappPhoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($phone),
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info("WhatsApp sent to {$phone}");
                return true;
            }

            Log::error("WhatsApp failed: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("WhatsApp error: {$e->getMessage()}");
            return false;
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present (assuming Indonesia)
        if (substr($phone, 0, 2) !== '62') {
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            } else {
                $phone = '62' . $phone;
            }
        }
        
        return $phone;
    }

    protected function getOrderMessage(Order $order)
    {
        $status = match($order->status) {
            Order::STATUS_PAID => 'Payment received',
            Order::STATUS_PROCESSING => 'Being processed',
            Order::STATUS_SUCCESS => 'Completed successfully',
            Order::STATUS_FAILED => 'Failed to process',
            Order::STATUS_REFUNDED => 'Has been refunded',
            default => 'Updated',
        };

        return "Hi! Your order {$order->invoice_no} for {$order->product->name} {$status}. " .
               "Check details at: " . route('invoice.show', $order->invoice_no);
    }

    protected function sendPushNotification(Order $order)
    {
        // Implement web push notifications if needed
        // This would use Laravel's notification channels
    }
}