<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->text(2000),
            'featured_image' => null,
            'category' => fake()->randomElement(['Laravel', 'Vue.js', 'PHP', 'JavaScript', 'Web Development', 'Tutorials']),
            'tags' => fake()->randomElements(['laravel', 'vue', 'php', 'javascript', 'tutorial', 'tips', 'best-practices'], 3),
            'published_at' => now(),
            'views' => fake()->numberBetween(0, 5000),
            'is_featured' => false,
            'series' => null,
            'series_order' => null,
        ];
    }

    /**
     * Indicate that the article is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    /**
     * Indicate that the article is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the article is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the article is part of a series.
     */
    public function inSeries(string $seriesName, int $order = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'series' => $seriesName,
            'series_order' => $order,
        ]);
    }
}








