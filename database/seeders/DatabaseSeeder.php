<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            GameSeeder::class,
            PaymentChannelSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            TestDataSeeder::class, // Only for development
        ]);
    }
}
