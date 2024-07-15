<?php

namespace Database\Factories\LMS;

use App\Models\LMS\Course;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LMS\Course>
 */
class CourseFactory extends Factory
{
    protected $courses = [
        'Introduction to islamic Studies',
        'The Art of Prayers',
        'Fasting in Perspective',
        'Growing your Spirit Being',
        'The Tenets of a wholesome life'
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'reference' => Str::random(),
            'name' => $course = fake()->unique()->randomElement($this->courses),
            'image' => null,
            'slug' => Str::slug($course),
            'all_lessons_paid' => fake()->randomElement([true, false]),
            'description' => fake()->paragraphs(asText:true),
            'price' => mt_rand(1000, 20000),
            'capacity' => 0,
            'duration' => 0,
            'age_group' => 0,
            'is_locked' => false
        ];
    }

    public function locked()
    {
        return $this->state(fn(array $attributes) => [
            'is_locked' => true
        ]);
    }
}
