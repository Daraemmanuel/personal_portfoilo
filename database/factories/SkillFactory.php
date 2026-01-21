<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    protected $model = Skill::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skillTypes = [
            ['name' => 'Backend Development', 'icon' => 'server', 'items' => ['Laravel', 'PHP', 'MySQL', 'REST APIs', 'Database Design']],
            ['name' => 'Frontend Development', 'icon' => 'code', 'items' => ['Vue.js', 'TypeScript', 'JavaScript', 'Tailwind CSS', 'Responsive Design']],
            ['name' => 'Tools & Technologies', 'icon' => 'wrench', 'items' => ['Git', 'Docker', 'Linux', 'CI/CD', 'Testing']],
            ['name' => 'Mobile Development', 'icon' => 'smartphone', 'items' => ['React Native', 'Flutter', 'iOS', 'Android']],
            ['name' => 'DevOps', 'icon' => 'cloud', 'items' => ['AWS', 'Docker', 'Kubernetes', 'CI/CD', 'Monitoring']],
        ];

        $skill = fake()->randomElement($skillTypes);

        return [
            'name' => $skill['name'],
            'icon' => $skill['icon'],
            'items' => $skill['items'],
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}





