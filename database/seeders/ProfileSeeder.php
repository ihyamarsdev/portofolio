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
                'title' => 'DevOps / DevSecOps / SRE / Cloud Engineer',
                'biography' => "I'm an infrastructure engineer focused on DevOps, DevSecOps, SRE, and Cloud Engineering. I design secure delivery pipelines, resilient Kubernetes platforms, observable systems, and cloud infrastructure that can be operated confidently at scale.\n\nMy work emphasizes automation, infrastructure as code, CI/CD, GitOps, cloud security, vulnerability management, SLOs, incident response, and measurable reliability outcomes.",
                'location' => 'Indonesia',
                'is_primary' => true,
            ]
        );
    }
}
