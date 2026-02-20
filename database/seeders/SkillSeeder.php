<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Stack;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Cloud Platforms
            ['name' => 'AWS EC2/ECS', 'category' => 'Cloud Platforms', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'AWS'],
            ['name' => 'AWS Lambda', 'category' => 'Cloud Platforms', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'AWS'],
            ['name' => 'GCP GKE', 'category' => 'Cloud Platforms', 'proficiency' => 4, 'years_experience' => 2, 'stack_name' => 'GCP'],
            ['name' => 'DigitalOcean Droplets', 'category' => 'Cloud Platforms', 'proficiency' => 5, 'years_experience' => 3, 'stack_name' => 'DigitalOcean'],

            // Container & Orchestration
            ['name' => 'Docker', 'category' => 'Container & Orchestration', 'proficiency' => 5, 'years_experience' => 5, 'stack_name' => 'Docker'],
            ['name' => 'Docker Compose', 'category' => 'Container & Orchestration', 'proficiency' => 5, 'years_experience' => 5, 'stack_name' => 'Docker'],
            ['name' => 'Kubernetes', 'category' => 'Container & Orchestration', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Kubernetes'],
            ['name' => 'Helm Charts', 'category' => 'Container & Orchestration', 'proficiency' => 4, 'years_experience' => 2, 'stack_name' => 'Helm'],

            // CI/CD
            ['name' => 'GitHub Actions', 'category' => 'CI/CD', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'GitHub Actions'],
            ['name' => 'GitLab CI/CD', 'category' => 'CI/CD', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'GitLab CI'],
            ['name' => 'ArgoCD', 'category' => 'CI/CD', 'proficiency' => 4, 'years_experience' => 2, 'stack_name' => 'ArgoCD'],

            // Infrastructure as Code
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Terraform'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Ansible'],

            // Monitoring & Observability
            ['name' => 'Prometheus', 'category' => 'Monitoring & Observability', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Prometheus'],
            ['name' => 'Grafana', 'category' => 'Monitoring & Observability', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Grafana'],
            ['name' => 'Uptime Kuma', 'category' => 'Monitoring & Observability', 'proficiency' => 5, 'years_experience' => 2, 'stack_name' => 'Uptime Kuma'],
        ];

        foreach ($skills as $skillData) {
            $stack = Stack::where('name', $skillData['stack_name'])->first();

            // Find or create category for skill
            $category = \App\Models\Category::firstOrCreate(
                ['name' => $skillData['category']],
                [
                    'slug' => \Illuminate\Support\Str::slug($skillData['category']),
                    'color' => '#6366f1',
                ]
            );

            Skill::updateOrCreate(
                ['name' => $skillData['name']],
                [
                    'category_id' => $category->id,
                    'proficiency' => $skillData['proficiency'],
                    'years_experience' => $skillData['years_experience'],
                    'stack_id' => $stack?->id,
                ]
            );
        }
    }
}
