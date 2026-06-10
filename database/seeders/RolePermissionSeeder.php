<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::findByName('Super Admin');
        $admin = Role::findByName('Admin');
        $encoder = Role::findByName('Encoder');
        $viewer = Role::findByName('Viewer');

        // Super Admin = lahat
        $superAdmin->givePermissionTo(
            \Spatie\Permission\Models\Permission::all()
        );

        // Admin
        $admin->givePermissionTo([
            'users.view',
            'users.create',
            'users.update',
        ]);

        // Encoder
        $encoder->givePermissionTo([
            'users.view',
            'users.create',
        ]);

        // Viewer
        $viewer->givePermissionTo([
            'users.view',
        ]);
    }
}