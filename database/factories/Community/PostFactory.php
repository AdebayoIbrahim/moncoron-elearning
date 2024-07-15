<?php

namespace Database\Factories\Community;

use App\Enums\Community\Posts\PostStatus;
use App\Enums\Community\Posts\PostType;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Community\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence(4);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'image' => null,
            'content' => fake()->paragraphs(asText: true),
            'status' => PostStatus::PUBLISHED,
            'published_at' => now()->subDays(random_int(0, 60)),
            'scheduled_at' => null,
            'author_id' => User::all()->random(1)->first()->id,
            'type' => PostType::COMMUNITY
        ];
    }
}
