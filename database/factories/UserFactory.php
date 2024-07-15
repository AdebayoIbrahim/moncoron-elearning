<?php

namespace Database\Factories;

use App\Traits\Auth\HasPermissions;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use HasPermissions;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ref'               => Str::random(),
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->unique()->phoneNumber(),
            'locale'            => 'en',
            'country'           => 'Nigeria',
            'state'             => fake()->randomElement(['Lagos', 'Abuja', 'Oyo']),
            'address'           => fake()->address(),
            'status'            => true,
            'email_verified_at' => now(),
            'dob'               => now()->subYears(random_int(8, 50)),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
            'role'              => fake()->randomElement($this->roles)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function deactivated()
    {
        return $this->state(fn (array $attributes) => [
            'status' => false
        ]);
    }
}
