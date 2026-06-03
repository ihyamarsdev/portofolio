<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::updateOrCreate(
            ['email' => 'admin@portfolio.dev'],
            [
                'name' => 'John Doe',
                'title' => 'DevOps Engineer',
                'biography' => "I'm an infrastructure engineer focused on DevOps Engineering. I design secure delivery pipelines, resilient Kubernetes platforms, observable systems, and DevOps infrastructure that can be operated confidently at scale.\n\nMy work emphasizes automation, infrastructure as code, CI/CD, GitOps, DevOps automation, vulnerability management, deployment metrics, incident response, and measurable reliability outcomes.",
                'location' => 'Indonesia',
                'is_primary' => true,
            ]
        );
    }
}
