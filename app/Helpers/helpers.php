<?php
// app/Helpers/helpers.php
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount, $currency = 'IDR')
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(\Illuminate\Support\Str::random(6));
        
        return "{$prefix}{$date}{$random}";
    }
}

if (!function_exists('getStatusColor')) {
    function getStatusColor($status)
    {
        return match($status) {
            'PENDING' => 'gray',
            'WAITING_PAYMENT' => 'yellow',
            'PAID' => 'blue',
            'PROCESSING' => 'indigo',
            'SUCCESS' => 'green',
            'FAILED' => 'red',
            'REFUNDED' => 'purple',
            'EXPIRED' => 'gray',
            'CANCELLED' => 'red',
            default => 'gray',
        };
    }
}

if (!function_exists('getPaymentIcon')) {
    function getPaymentIcon($method)
    {
        return match($method) {
            'qris' => 'qris.png',
            'gopay' => 'gopay.png',
            'ovo' => 'ovo.png',
            'dana' => 'dana.png',
            'shopeepay' => 'shopeepay.png',
            'bca_va' => 'bca.png',
            'bni_va' => 'bni.png',
            'bri_va' => 'bri.png',
            'mandiri_va' => 'mandiri.png',
            'permata_va' => 'permata.png',
            default => 'default.png',
        };
    }
}