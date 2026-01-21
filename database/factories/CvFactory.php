<?php

namespace Database\Factories;

use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cv>
 */
class CvFactory extends Factory
{
    protected $model = Cv::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileName = fake()->word() . '.pdf';
        
        return [
            'file_path' => 'cvs/' . $fileName,
            'file_name' => $fileName,
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(100000, 5000000), // 100KB to 5MB
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the CV is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the CV is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

