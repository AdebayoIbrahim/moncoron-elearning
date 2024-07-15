<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guardian>
 */
class GuardianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ref'       => Str::random(),
            'name'      => fake()->name(),
            'email'     => fake()->unique()->safeEmail(),
            'status'    => true,
            'phone'     => fake()->unique()->phoneNumber(),
            'password'  => null,
            'occupation' => fake()->jobTitle(),
            'address'   => fake()->streetAddress()
        ];
    }
}
