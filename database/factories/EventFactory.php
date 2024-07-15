<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => random_int(1,3),
            'creator_id' => random_int(1, 3),
            'title' => fake()->text(20),
            'description' => fake()->paragraph(),
            'has_reminder' => fake()->boolean(),
            'startAt' => fake()->dateTimeBetween(now(), now()->addMonths(2)),
            'endAt' => fake()->dateTimeBetween(now(), now()->addMonths(1)),
        ];
    }
}
