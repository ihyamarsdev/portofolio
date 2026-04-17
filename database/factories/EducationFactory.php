<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    protected $model = Education::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-10 years', '-4 years');

        return [
            'institution' => fake()->company().' University',
            'degree' => fake()->randomElement(['Bachelor', 'Master', 'Diploma']),
            'field_of_study' => fake()->randomElement(['Computer Science', 'Information Technology', 'Software Engineering']),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, '-1 year'),
            'grade' => fake()->randomElement(['A', 'B+', '3.8/4.0', 'Cum Laude']),
            'activities' => fake()->sentence(),
            'order' => fake()->numberBetween(0, 10),
        ];
    }
}
