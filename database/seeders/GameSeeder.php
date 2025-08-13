<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $games = [
                [
                    'name' => 'Mobile Legends',
                    'slug' => 'mobile-legends',
                    'publisher' => 'Moonton',
                    'icon_url' => 'https://via.placeholder.com/200x200/9333ea/ffffff?text=ML',
                    'description' => 'Popular MOBA game with millions of players worldwide',
                    'category' => 'mobile',
                    'is_featured' => true,
                    'products' => [
                        ['name' => '86 Diamonds',  'nominal_code' => 'ML86',   'price' => 20000],
                        ['name' => '172 Diamonds', 'nominal_code' => 'ML172',  'price' => 40000],
                        ['name' => '257 Diamonds', 'nominal_code' => 'ML257',  'price' => 60000],
                        ['name' => '344 Diamonds', 'nominal_code' => 'ML344',  'price' => 80000],
                        ['name' => '429 Diamonds', 'nominal_code' => 'ML429',  'price' => 100000],
                        ['name' => '514 Diamonds', 'nominal_code' => 'ML514',  'price' => 120000],
                        ['name' => '706 Diamonds', 'nominal_code' => 'ML706',  'price' => 165000],
                        ['name' => '878 Diamonds', 'nominal_code' => 'ML878',  'price' => 205000],
                        ['name' => '1163 Diamonds','nominal_code' => 'ML1163', 'price' => 270000],
                        ['name' => '2010 Diamonds','nominal_code' => 'ML2010', 'price' => 465000],
                        ['name' => 'Weekly Pass',   'nominal_code' => 'MLWP',   'price' => 29000],
                        ['name' => 'Twilight Pass', 'nominal_code' => 'MLTP',   'price' => 149000],
                    ],
                ],
                [
                    'name' => 'Free Fire',
                    'slug' => 'free-fire',
                    'publisher' => 'Garena',
                    'icon_url' => 'https://via.placeholder.com/200x200/ef4444/ffffff?text=FF',
                    'description' => 'Battle royale game with fast-paced action',
                    'category' => 'mobile',
                    'is_featured' => true,
                    'products' => [
                        ['name' => '50 Diamonds',   'nominal_code' => 'FF50',   'price' => 7500],
                        ['name' => '70 Diamonds',   'nominal_code' => 'FF70',   'price' => 10000],
                        ['name' => '100 Diamonds',  'nominal_code' => 'FF100',  'price' => 14500],
                        ['name' => '140 Diamonds',  'nominal_code' => 'FF140',  'price' => 20000],
                        ['name' => '210 Diamonds',  'nominal_code' => 'FF210',  'price' => 30000],
                        ['name' => '280 Diamonds',  'nominal_code' => 'FF280',  'price' => 40000],
                        ['name' => '355 Diamonds',  'nominal_code' => 'FF355',  'price' => 50000],
                        ['name' => '500 Diamonds',  'nominal_code' => 'FF500',  'price' => 70000],
                        ['name' => '720 Diamonds',  'nominal_code' => 'FF720',  'price' => 100000],
                        ['name' => '1000 Diamonds', 'nominal_code' => 'FF1000', 'price' => 140000],
                        ['name' => 'Member Mingguan','nominal_code' => 'FFMM',  'price' => 30000],
                        ['name' => 'Member Bulanan', 'nominal_code' => 'FFMB',  'price' => 85000],
                    ],
                ],
                [
                    'name' => 'PUBG Mobile',
                    'slug' => 'pubg-mobile',
                    'publisher' => 'Tencent',
                    'icon_url' => 'https://via.placeholder.com/200x200/f59e0b/ffffff?text=PUBG',
                    'description' => 'The original battle royale experience on mobile',
                    'category' => 'mobile',
                    'is_featured' => true,
                    'products' => [
                        ['name' => '60 UC',   'nominal_code' => 'PUBG60',   'price' => 15000],
                        ['name' => '325 UC',  'nominal_code' => 'PUBG325',  'price' => 75000],
                        ['name' => '660 UC',  'nominal_code' => 'PUBG660',  'price' => 150000],
                        ['name' => '1800 UC', 'nominal_code' => 'PUBG1800', 'price' => 400000],
                        ['name' => '3850 UC', 'nominal_code' => 'PUBG3850', 'price' => 850000],
                        ['name' => '8100 UC', 'nominal_code' => 'PUBG8100', 'price' => 1700000],
                    ],
                ],
                [
                    'name' => 'Genshin Impact',
                    'slug' => 'genshin-impact',
                    'publisher' => 'miHoYo',
                    'icon_url' => 'https://via.placeholder.com/200x200/3b82f6/ffffff?text=GI',
                    'description' => 'Open-world action RPG with stunning visuals',
                    'category' => 'mobile',
                    'is_featured' => true,
                    'products' => [
                        ['name' => '60 Genesis Crystals',  'nominal_code' => 'GI60',   'price' => 16000],
                        ['name' => '330 Genesis Crystals', 'nominal_code' => 'GI330',  'price' => 79000],
                        ['name' => '1090 Genesis Crystals','nominal_code' => 'GI1090', 'price' => 259000],
                        ['name' => '2240 Genesis Crystals','nominal_code' => 'GI2240', 'price' => 519000],
                        ['name' => '3880 Genesis Crystals','nominal_code' => 'GI3880', 'price' => 829000],
                        ['name' => '8080 Genesis Crystals','nominal_code' => 'GI8080', 'price' => 1659000],
                        ['name' => 'Welkin Moon',          'nominal_code' => 'GIWM',   'price' => 79000],
                    ],
                ],
                [
                    'name' => 'Valorant',
                    'slug' => 'valorant',
                    'publisher' => 'Riot Games',
                    'icon_url' => 'https://via.placeholder.com/200x200/dc2626/ffffff?text=VAL',
                    'description' => 'Tactical first-person shooter',
                    'category' => 'pc',
                    'is_featured' => true,
                    'products' => [
                        ['name' => '420 Points',  'nominal_code' => 'VAL420',  'price' => 55000],
                        ['name' => '700 Points',  'nominal_code' => 'VAL700',  'price' => 90000],
                        ['name' => '1375 Points', 'nominal_code' => 'VAL1375', 'price' => 175000],
                        ['name' => '2400 Points', 'nominal_code' => 'VAL2400', 'price' => 300000],
                        ['name' => '4000 Points', 'nominal_code' => 'VAL4000', 'price' => 495000],
                        ['name' => '8150 Points', 'nominal_code' => 'VAL8150', 'price' => 990000],
                    ],
                ],
            ];

            foreach ($games as $g) {
                $products = $g['products'];
                $game = Game::updateOrCreate(
                    ['slug' => $g['slug']],                   // kunci unik
                    Arr::except($g, ['slug', 'products'])     // kolom yang diupdate
                );

                // Simpan/upgrade products
                $keepNominals = [];
                foreach ($products as $index => $p) {
                    $keepNominals[] = $p['nominal_code'];

                    GameProduct::updateOrCreate(
                        ['game_id' => $game->id, 'nominal_code' => $p['nominal_code']], // kunci unik komposit
                        [
                            'name'         => $p['name'],
                            'price'        => $p['price'],
                            'sort_order'   => $index,
                            'process_time' => 'Instant',
                            'is_hot'       => $index === 3, // item ke-4 hot
                        ]
                    );
                }

                // (Opsional) hapus produk yang tidak ada lagi di daftar
                GameProduct::where('game_id', $game->id)
                    ->whereNotIn('nominal_code', $keepNominals)
                    ->delete();
            }
        });
    }
}
