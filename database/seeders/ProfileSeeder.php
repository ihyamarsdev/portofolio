<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create([
            'name' => 'John Doe', // Placeholder name
            'title' => 'DevOps Engineer',
            'biography' => "I'm a DevOps Engineer passionate about automating everything and building reliable, scalable infrastructure.\nWith expertise in cloud platforms, container orchestration, and CI/CD pipelines, I help teams ship faster and more reliably.\n\nMy focus areas include infrastructure as code, Kubernetes orchestration, and building robust monitoring solutions.\nI believe in the philosophy of \"everything as code\" and continuously improving deployment processes.",
            'location' => 'Indonesia',
            'email' => 'admin@portfolio.dev',
            'is_primary' => true,
        ]);
    }
}
