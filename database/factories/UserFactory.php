<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->userName(),
            'password' => Hash::make('guest123'),
            'role' => 'guest',
            'permission' => 'read-only',
        ];
    }
    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin-portfolio',
            'password' => Hash::make('QAZqazWSXwsx12()'),
            'role' => 'admin',
            'permission' => 'full-control',
        ]);
    }
}
