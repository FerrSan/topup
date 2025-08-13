<?php

namespace Database\Seeders;

use App\Models\PaymentChannel;
use Illuminate\Database\Seeder;

class PaymentChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            [
                'provider' => 'midtrans',
                'code' => 'qris',
                'name' => 'QRIS',
                'type' => 'qris',
                'logo_url' => '/images/payments/qris.png',
                'fee_flat' => 750,
                'fee_percent' => 0.7,
                'min_amount' => 1000,
                'max_amount' => 10000000,
                'sort_order' => 1,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'gopay',
                'name' => 'GoPay',
                'type' => 'e-wallet',
                'logo_url' => '/images/payments/gopay.png',
                'fee_flat' => 1000,
                'fee_percent' => 2,
                'min_amount' => 1000,
                'max_amount' => 10000000,
                'sort_order' => 2,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'ovo',
                'name' => 'OVO',
                'type' => 'e-wallet',
                'logo_url' => '/images/payments/ovo.png',
                'fee_flat' => 1000,
                'fee_percent' => 2,
                'min_amount' => 10000,
                'max_amount' => 10000000,
                'sort_order' => 3,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'dana',
                'name' => 'DANA',
                'type' => 'e-wallet',
                'logo_url' => '/images/payments/dana.png',
                'fee_flat' => 1000,
                'fee_percent' => 2,
                'min_amount' => 1000,
                'max_amount' => 10000000,
                'sort_order' => 4,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'shopeepay',
                'name' => 'ShopeePay',
                'type' => 'e-wallet',
                'logo_url' => '/images/payments/shopeepay.png',
                'fee_flat' => 1000,
                'fee_percent' => 2,
                'min_amount' => 1000,
                'max_amount' => 10000000,
                'sort_order' => 5,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'bca_va',
                'name' => 'BCA Virtual Account',
                'type' => 'va',
                'logo_url' => '/images/payments/bca.png',
                'fee_flat' => 4000,
                'fee_percent' => 0,
                'min_amount' => 10000,
                'max_amount' => 999999999,
                'sort_order' => 6,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'bni_va',
                'name' => 'BNI Virtual Account',
                'type' => 'va',
                'logo_url' => '/images/payments/bni.png',
                'fee_flat' => 4000,
                'fee_percent' => 0,
                'min_amount' => 10000,
                'max_amount' => 999999999,
                'sort_order' => 7,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'bri_va',
                'name' => 'BRI Virtual Account',
                'type' => 'va',
                'logo_url' => '/images/payments/bri.png',
                'fee_flat' => 4000,
                'fee_percent' => 0,
                'min_amount' => 10000,
                'max_amount' => 999999999,
                'sort_order' => 8,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'mandiri_va',
                'name' => 'Mandiri Virtual Account',
                'type' => 'va',
                'logo_url' => '/images/payments/mandiri.png',
                'fee_flat' => 4000,
                'fee_percent' => 0,
                'min_amount' => 10000,
                'max_amount' => 999999999,
                'sort_order' => 9,
            ],
            [
                'provider' => 'midtrans',
                'code' => 'permata_va',
                'name' => 'Permata Virtual Account',
                'type' => 'va',
                'logo_url' => '/images/payments/permata.png',
                'fee_flat' => 4000,
                'fee_percent' => 0,
                'min_amount' => 10000,
                'max_amount' => 999999999,
                'sort_order' => 10,
            ],
        ];

        foreach ($channels as $channel) {
            PaymentChannel::updateOrCreate(
                ['code' => $channel['code']],
                $channel
            );
        }
    }
}
