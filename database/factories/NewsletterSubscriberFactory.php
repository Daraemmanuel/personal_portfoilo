<?php

namespace Database\Factories;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsletterSubscriber>
 */
class NewsletterSubscriberFactory extends Factory
{
    protected $model = NewsletterSubscriber::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'name' => fake()->optional()->name(),
            'is_active' => true,
            'subscribed_at' => now()->subDays(fake()->numberBetween(1, 365)),
            'unsubscribed_at' => null,
        ];
    }

    /**
     * Indicate that the subscriber is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'unsubscribed_at' => null,
        ]);
    }

    /**
     * Indicate that the subscriber is inactive (unsubscribed).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'unsubscribed_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}








