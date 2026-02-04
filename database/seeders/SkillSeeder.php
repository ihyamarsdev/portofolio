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
            ['name' => 'AWS EC2/ECS', 'category' => 'Cloud Platforms', 'proficiency' => 5, 'years_experience' => 4, 'stack_slug' => 'aws'],
            ['name' => 'AWS Lambda', 'category' => 'Cloud Platforms', 'proficiency' => 4, 'years_experience' => 3, 'stack_slug' => 'aws'],
            ['name' => 'GCP GKE', 'category' => 'Cloud Platforms', 'proficiency' => 4, 'years_experience' => 2, 'stack_slug' => 'gcp'],
            ['name' => 'DigitalOcean Droplets', 'category' => 'Cloud Platforms', 'proficiency' => 5, 'years_experience' => 3, 'stack_slug' => 'digitalocean'],

            // Container & Orchestration
            ['name' => 'Docker', 'category' => 'Container & Orchestration', 'proficiency' => 5, 'years_experience' => 5, 'stack_slug' => 'docker'],
            ['name' => 'Docker Compose', 'category' => 'Container & Orchestration', 'proficiency' => 5, 'years_experience' => 5, 'stack_slug' => 'docker'],
            ['name' => 'Kubernetes', 'category' => 'Container & Orchestration', 'proficiency' => 4, 'years_experience' => 3, 'stack_slug' => 'kubernetes'],
            ['name' => 'Helm Charts', 'category' => 'Container & Orchestration', 'proficiency' => 4, 'years_experience' => 2, 'stack_slug' => 'helm'],

            // CI/CD
            ['name' => 'GitHub Actions', 'category' => 'CI/CD', 'proficiency' => 5, 'years_experience' => 4, 'stack_slug' => 'github-actions'],
            ['name' => 'GitLab CI/CD', 'category' => 'CI/CD', 'proficiency' => 4, 'years_experience' => 3, 'stack_slug' => 'gitlab-ci'],
            ['name' => 'ArgoCD', 'category' => 'CI/CD', 'proficiency' => 4, 'years_experience' => 2, 'stack_slug' => 'argocd'],

            // Infrastructure as Code
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code', 'proficiency' => 5, 'years_experience' => 4, 'stack_slug' => 'terraform'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code', 'proficiency' => 4, 'years_experience' => 3, 'stack_slug' => 'ansible'],

            // Monitoring & Observability
            ['name' => 'Prometheus', 'category' => 'Monitoring & Observability', 'proficiency' => 4, 'years_experience' => 3, 'stack_slug' => 'prometheus'],
            ['name' => 'Grafana', 'category' => 'Monitoring & Observability', 'proficiency' => 5, 'years_experience' => 4, 'stack_slug' => 'grafana'],
            ['name' => 'Uptime Kuma', 'category' => 'Monitoring & Observability', 'proficiency' => 5, 'years_experience' => 2, 'stack_slug' => 'uptime-kuma'],
        ];

        foreach ($skills as $skillData) {
            $stack = Stack::where('slug', $skillData['stack_slug'])->first();

            Skill::updateOrCreate(
                ['name' => $skillData['name']],
                [
                    'category' => $skillData['category'],
                    'proficiency' => $skillData['proficiency'],
                    'years_experience' => $skillData['years_experience'],
                    'stack_id' => $stack?->id,
                ]
            );
        }
    }
}
