<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-5 years', '-1 year');
        $endDate = fake()->optional()->dateTimeBetween($startDate, 'now');

        return [
            'role' => fake()->jobTitle(),
            'company' => fake()->company(),
            'period' => $endDate 
                ? $startDate->format('Y-m') . ' - ' . $endDate->format('Y-m')
                : $startDate->format('Y-m') . ' - Present',
            'description' => fake()->paragraph(3),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the experience is current (no end date).
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'period' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m') . ' - Present',
        ]);
    }
}








