<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for Filament
        User::updateOrCreate(
            ['email' => 'admin@ihyamars.my.id'],
            [
                'name' => 'Admin',
                'email' => 'admin@ihyamars.my.id',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // Run other seeders in correct order
        $this->call([
            // Stack first (independent)
            StackSeeder::class,

            // Skill depends on Stack
            SkillSeeder::class,

            // Category (independent)
            CategorySeeder::class,

            // Core content
            ProfileSeeder::class,
            HomeSettingsSeeder::class,

            // Education & Experience (independent, use skills)
            EducationSeeder::class,
            ExperienceSeeder::class,

            // Projects (use skills)
            ProjectSeeder::class,

            // Posts (use categories)
            PostSeeder::class,

            // Certificates (independent)
            CertificateSeeder::class,

            // Service status (independent)
            ServiceStatusSeeder::class,

            // Inbox messages (independent)
            InboxSeeder::class,

            // CV Analyses (depends on Profile)
            CVAnalysisSeeder::class,
        ]);
    }
}
