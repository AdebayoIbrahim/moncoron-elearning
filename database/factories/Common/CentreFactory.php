<?php

namespace Database\Factories\Common;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Common\Centre>
 */
class CentreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'capacity' => mt_rand(1, 100),
            'country' => fake()->randomElement(['Nigeria', 'Guinea']),
            'state_province' => null,
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'contact_person' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'contact_email' => fake()->email(),
            'status' => 'open'
        ];
    }


    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed'
        ]);
    }
}
