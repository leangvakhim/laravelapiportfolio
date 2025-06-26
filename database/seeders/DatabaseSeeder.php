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
        // Test user
        User::factory()->create([
            'name' => 'Test User',
            'password' => Hash::make('test123'),
            'role' => 'guest',
            'permission' => 'read-only',
        ]);

        // Admin user
        User::factory()->admin()->create();

        // Guest user
        User::factory()->create([
            'name' => 'guest',
            'password' => Hash::make('guest123'),
            'role' => 'guest',
            'permission' => 'read-only',
        ]);
    }
}
