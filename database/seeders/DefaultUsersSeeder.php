<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user using factory state
        User::factory()->admin()->create();

        // Create guest user using default factory
        User::factory()->create(
        [
            'name' => 'guest',
            'password' => Hash::make('guest123'), // override default if needed
        ]);
    }
}
