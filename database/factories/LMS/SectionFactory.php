<?php

namespace Database\Factories\LMS;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LMS\Section>
 */
class SectionFactory extends Factory
{
    protected $order = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order' => $this->getOrder(),
            'title' => fake()->words(asText: true),
            'description' => fake()->paragraphs( asText: true)
        ];
    }

    protected function getOrder(): int
    {
        return $this->order++;
    }
}
