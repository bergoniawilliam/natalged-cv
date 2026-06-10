<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Encoder',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Viewer',
            'guard_name' => 'web',
        ]);
    }

    public function down(): void
    {
        Role::where('name', 'Super Admin')->delete();
        Role::where('name', 'Admin')->delete();
        Role::where('name', 'Encoder')->delete();
        Role::where('name', 'Viewer')->delete();
    }
};