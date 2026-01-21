<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'image' => null,
            'link' => fake()->optional()->url(),
            'tags' => fake()->randomElements(['laravel', 'vue', 'php', 'javascript', 'web', 'api'], 3),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_archived' => false,
        ];
    }

    /**
     * Indicate that the project is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_archived' => true,
        ]);
    }

    /**
     * Indicate that the project has a link.
     */
    public function withLink(): static
    {
        return $this->state(fn (array $attributes) => [
            'link' => fake()->url(),
        ]);
    }
}





