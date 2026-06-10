<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $encoder = Role::where('name', 'Encoder')->first();
        $viewer = Role::where('name', 'Viewer')->first();

        if (!$superAdmin || !$admin || !$encoder || !$viewer) {
            return;
        }

        // Super Admin = lahat
        $superAdmin->syncPermissions(
            Permission::all()
        );

        // Admin
        $admin->syncPermissions([
            'users.view',
            'users.create',
            'users.update',
        ]);

        // Encoder
        $encoder->syncPermissions([
            'users.view',
            'users.create',
        ]);

        // Viewer
        $viewer->syncPermissions([
            'users.view',
        ]);
    }

    public function down(): void
    {
        $roles = ['Super Admin', 'Admin', 'Encoder', 'Viewer'];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $role->syncPermissions([]);
            }
        }
    }
};