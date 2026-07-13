<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WikiArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'extract' => fake()->paragraph(),
            'thumbnail_url' => fake()->imageUrl(),
            'page_url' => fake()->url(),
            'fetched_at' => now(),
        ];
    }
}