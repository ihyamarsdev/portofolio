<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    protected $model = Skill::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'category_id' => Category::factory(),
            'proficiency' => fake()->numberBetween(1, 5),
            'years_experience' => fake()->randomFloat(1, 0, 12),
        ];
    }
}
