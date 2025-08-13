<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission/role
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web'; // pastikan sesuai guard yang kamu pakai untuk user/admin

        // Daftar permissions
        $permissions = [
            'view admin panel',
            'manage games',
            'manage products',
            'manage orders',
            'manage users',
            'manage coupons',
            'manage testimonials',
            'manage settings',
            'view reports',
            'process refunds',
        ];

        // Buat permission hanya jika belum ada (idempotent) + set guard_name
        foreach ($permissions as $name) {
            Permission::findOrCreate($name, $guard);
        }

        // Buat role hanya jika belum ada (idempotent) + set guard_name
        $superAdmin = Role::findOrCreate('super-admin', $guard);
        $admin      = Role::findOrCreate('admin', $guard);
        $support    = Role::findOrCreate('support', $guard);
        $customer   = Role::findOrCreate('customer', $guard);

        // Ambil hanya permission untuk guard ini
        $allWebPermissions = Permission::where('guard_name', $guard)->pluck('name')->all();

        // Assign permissions (idempotent) pakai syncPermissions
        $superAdmin->syncPermissions($allWebPermissions);

        $admin->syncPermissions([
            'view admin panel',
            'manage games',
            'manage products',
            'manage orders',
            'manage coupons',
            'manage testimonials',
            'view reports',
        ]);

        $support->syncPermissions([
            'view admin panel',
            'manage orders',
            'view reports',
        ]);

        // customer tidak perlu permission khusus
        $customer->syncPermissions([]);

        // Reset cache lagi
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
