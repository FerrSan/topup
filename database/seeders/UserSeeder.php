<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // pastikan role 'super-admin' sudah ada (dari RolePermissionSeeder)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        // data user default (ubah sesuai kebutuhan)
        $data = [
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // ganti di env nyata
        ];

        // idempotent berdasarkan email
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            $data
        );

        // assign role (idempotent)
        if (!$user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
        }
    }
}
