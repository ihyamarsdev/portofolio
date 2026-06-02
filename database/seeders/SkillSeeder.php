<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Skill;
use App\Models\Stack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'Kubernetes', 'category' => 'Container Orchestration', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Kubernetes'],
            ['name' => 'Docker', 'category' => 'Container Orchestration', 'proficiency' => 5, 'years_experience' => 5, 'stack_name' => 'Docker'],
            ['name' => 'Helm', 'category' => 'Container Orchestration', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Helm'],
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Terraform'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Ansible'],
            ['name' => 'GitHub Actions', 'category' => 'CI/CD & GitOps', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'GitHub Actions'],
            ['name' => 'GitLab CI/CD', 'category' => 'CI/CD & GitOps', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'GitLab CI'],
            ['name' => 'ArgoCD', 'category' => 'CI/CD & GitOps', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'ArgoCD'],
            ['name' => 'AWS', 'category' => 'Cloud Engineering', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'AWS'],
            ['name' => 'GCP', 'category' => 'Cloud Engineering', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'GCP'],
            ['name' => 'Azure', 'category' => 'Cloud Engineering', 'proficiency' => 3, 'years_experience' => 2, 'stack_name' => 'Azure'],
            ['name' => 'Prometheus', 'category' => 'Monitoring & Observability', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Prometheus'],
            ['name' => 'Grafana', 'category' => 'Monitoring & Observability', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Grafana'],
            ['name' => 'OpenTelemetry', 'category' => 'Monitoring & Observability', 'proficiency' => 4, 'years_experience' => 2, 'stack_name' => 'OpenTelemetry'],
            ['name' => 'Incident Management', 'category' => 'Site Reliability Engineering', 'proficiency' => 5, 'years_experience' => 4, 'stack_name' => 'Alertmanager'],
            ['name' => 'SLO/SLI/Error Budgets', 'category' => 'Site Reliability Engineering', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Prometheus'],
            ['name' => 'Trivy Vulnerability Scanning', 'category' => 'Cloud Security', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Trivy'],
            ['name' => 'SAST/DAST Pipelines', 'category' => 'DevSecOps', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Snyk'],
            ['name' => 'Runtime Security', 'category' => 'DevSecOps', 'proficiency' => 3, 'years_experience' => 2, 'stack_name' => 'Falco'],
            ['name' => 'Secrets Management', 'category' => 'Cloud Security', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Vault'],
            ['name' => 'Policy as Code', 'category' => 'Cloud Security', 'proficiency' => 4, 'years_experience' => 2, 'stack_name' => 'OPA Gatekeeper'],
            ['name' => 'Platform Engineering', 'category' => 'Platform Engineering', 'proficiency' => 4, 'years_experience' => 3, 'stack_name' => 'Kubernetes'],
        ];

        foreach ($skills as $skillData) {
            $stack = Stack::where('name', $skillData['stack_name'])->first();
            $category = Category::firstOrCreate(
                ['name' => $skillData['category']],
                ['slug' => Str::slug($skillData['category']), 'color' => '#6366f1']
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
