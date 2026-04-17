<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'title' => fake()->jobTitle(),
            'biography' => fake()->paragraph(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city().', '.fake()->country(),
            'github_url' => fake()->url(),
            'linkedin_url' => fake()->url(),
            'twitter_url' => fake()->url(),
            'website_url' => fake()->url(),
            'is_primary' => true,
            'cv_template' => 'ats',
        ];
    }
}
