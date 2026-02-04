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
            ['email' => 'admin@portfolio.dev'],
            [
                'name' => 'Admin',
                'email' => 'admin@portfolio.dev',
                'password' => Hash::make('password'),
            ]
        );

        // Run other seeders
        $this->call([
            StackSeeder::class,
            SkillSeeder::class,
            ServiceStatusSeeder::class,
            ProfileSeeder::class,
        ]);
    }
}
