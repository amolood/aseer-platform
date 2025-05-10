<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CarPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create car permissions
        $carPermissions = [
            'cars-create',
            'cars-read',
            'cars-update',
            'cars-delete',
            'cars-approve',
            'cars-report'
        ];

        // Create permissions if they don't exist with table value
        foreach ($carPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'table' => 'cars'
            ]);
        }

        // Assign permissions to roles
        $superadmin = Role::where('name', 'superadmin')->first();
        $admin = Role::where('name', 'admin')->first();
        $editor = Role::where('name', 'editor')->first();
        $customer_support = Role::where('name', 'customer_support')->first();
        $user = Role::where('name', 'user')->first();

        if ($superadmin) {
            $superadmin->givePermissionTo($carPermissions);
        }

        if ($admin) {
            $admin->givePermissionTo($carPermissions);
        }

        if ($editor) {
            $editor->givePermissionTo(['cars-read', 'cars-update']);
        }

        if ($customer_support) {
            $customer_support->givePermissionTo(['cars-read', 'cars-report']);
        }

        if ($user) {
            $user->givePermissionTo(['cars-create', 'cars-read', 'cars-report']);
        }
    }
}
