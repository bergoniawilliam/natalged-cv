<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'test@example.com')->delete();
    }
};