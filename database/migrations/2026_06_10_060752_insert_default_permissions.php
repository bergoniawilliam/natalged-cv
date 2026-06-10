<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $modules = [
            'uac',
            'dashboard',
            'admin',
            'users',
            'bridges',
            'roads',
            'evacuation',
            'relation',
            'affected-bridge',
            'barangay-affected',
        ];

        $actions = [
            'view',
            'create',
            'update',
            'delete',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {

                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);

            }
        }
    }

    public function down(): void
    {
        $modules = [
            'uac',
            'dashboard',
            'admin',
            'users',
            'bridges',
            'roads',
            'evacuation',
            'relation',
            'affected-bridge',
            'barangay-affected',
        ];

        $actions = [
            'view',
            'create',
            'update',
            'delete',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {

                Permission::where([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ])->delete();

            }
        }
    }
};