<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

         User::factory()->create([
            'rank' => 'Admin',
            'first_name' => 'Test',
            'middle_name' => 'A.',
            'last_name' => 'User',
            'qualifier' => null,
            'collection' => 'HQ',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
