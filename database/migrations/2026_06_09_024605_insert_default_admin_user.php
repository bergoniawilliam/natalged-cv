<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->insert([
            'rank' => 'Admin',
            'first_name' => 'Test',
            'middle_name' => 'A.',
            'last_name' => 'User',
            'qualifier' => null,
            'collection' => 'HQ',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🔥 GET USER
        $user = DB::table('users')
            ->where('email', 'test@example.com')
            ->first();

        // 🔥 ASSIGN ROLE (Super Admin)
        if ($user) {
            $role = Role::firstOrCreate([
                'name' => 'Super Admin',
                'guard_name' => 'web'
            ]);

            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => \App\Models\User::class,
                'model_id' => $user->id,
            ]);
        }
    }

    public function down(): void
    {
        $user = DB::table('users')
            ->where('email', 'test@example.com')
            ->first();

        if ($user) {
            DB::table('model_has_roles')
                ->where('model_type', \App\Models\User::class)
                ->where('model_id', $user->id)
                ->delete();
        }

        DB::table('users')
            ->where('email', 'test@example.com')
            ->delete();
    }
};