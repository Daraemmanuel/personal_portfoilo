<?php

namespace Database\Factories;

use App\Models\ArticleComment;
use App\Models\CommentReaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentReaction>
 */
class CommentReactionFactory extends Factory
{
    protected $model = CommentReaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment_id' => ArticleComment::factory(),
            'reaction_type' => fake()->randomElement(['like', 'helpful']),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * Indicate that the reaction is a like.
     */
    public function like(): static
    {
        return $this->state(fn (array $attributes) => [
            'reaction_type' => 'like',
        ]);
    }

    /**
     * Indicate that the reaction is helpful.
     */
    public function helpful(): static
    {
        return $this->state(fn (array $attributes) => [
            'reaction_type' => 'helpful',
        ]);
    }
}








