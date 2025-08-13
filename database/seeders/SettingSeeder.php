<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh isi (sesuaikan tabel & kolom)
        DB::table('settings')->updateOrInsert(
            ['key' => 'site_name'],
            ['value' => 'My App']
        );
    }
}
