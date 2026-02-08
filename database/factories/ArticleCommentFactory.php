<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleComment>
 */
class ArticleCommentFactory extends Factory
{
    protected $model = ArticleComment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'article_id' => Article::factory(),
            'author_name' => fake()->name(),
            'author_email' => fake()->safeEmail(),
            'content' => fake()->paragraph(),
            'is_approved' => true,
            'parent_id' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * Indicate that the comment is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the comment is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the comment is a reply to another comment.
     */
    public function reply(ArticleComment $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'article_id' => $parent->article_id,
        ]);
    }
}








