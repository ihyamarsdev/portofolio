<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 years', '-1 year');

        return [
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'employment_type' => fake()->randomElement(['Full-time', 'Part-time', 'Contract']),
            'location' => fake()->city(),
            'start_date' => $startDate,
            'end_date' => fake()->boolean(35) ? null : fake()->dateTimeBetween($startDate, 'now'),
            'description' => fake()->paragraph(),
            'achievements' => [
                fake()->sentence(),
                fake()->sentence(),
            ],
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
