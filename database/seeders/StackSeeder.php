<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Stack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StackSeeder extends Seeder
{
    public function run(): void
    {
        $stacks = [
            ['name' => 'AWS', 'category' => 'Cloud Engineering', 'icon' => '☁️'],
            ['name' => 'GCP', 'category' => 'Cloud Engineering', 'icon' => '🌐'],
            ['name' => 'Azure', 'category' => 'Cloud Engineering', 'icon' => '☁️'],
            ['name' => 'DigitalOcean', 'category' => 'Cloud Engineering', 'icon' => '🌊'],
            ['name' => 'Docker', 'category' => 'Container Orchestration', 'icon' => '🐳'],
            ['name' => 'Kubernetes', 'category' => 'Container Orchestration', 'icon' => '⎈'],
            ['name' => 'Helm', 'category' => 'Container Orchestration', 'icon' => '⛵'],
            ['name' => 'ArgoCD', 'category' => 'CI/CD & GitOps', 'icon' => '🚀'],
            ['name' => 'GitHub Actions', 'category' => 'CI/CD & GitOps', 'icon' => '⚙️'],
            ['name' => 'GitLab CI', 'category' => 'CI/CD & GitOps', 'icon' => '🦊'],
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code', 'icon' => '🏗️'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code', 'icon' => '📜'],
            ['name' => 'Pulumi', 'category' => 'Infrastructure as Code', 'icon' => '🎯'],
            ['name' => 'Prometheus', 'category' => 'Monitoring & Observability', 'icon' => '🔥'],
            ['name' => 'Grafana', 'category' => 'Monitoring & Observability', 'icon' => '📊'],
            ['name' => 'Alertmanager', 'category' => 'Monitoring & Observability', 'icon' => '🚨'],
            ['name' => 'OpenTelemetry', 'category' => 'Monitoring & Observability', 'icon' => '🔭'],
            ['name' => 'Trivy', 'category' => 'Cloud Security', 'icon' => '🛡️'],
            ['name' => 'Snyk', 'category' => 'Cloud Security', 'icon' => '🔐'],
            ['name' => 'Falco', 'category' => 'Cloud Security', 'icon' => '🦅'],
            ['name' => 'Vault', 'category' => 'Cloud Security', 'icon' => '🔑'],
            ['name' => 'OPA Gatekeeper', 'category' => 'Cloud Security', 'icon' => '🚪'],
            ['name' => 'Nginx', 'category' => 'Networking', 'icon' => '🌐'],
            ['name' => 'Traefik', 'category' => 'Networking', 'icon' => '🔀'],
            ['name' => 'Cloudflare', 'category' => 'Networking', 'icon' => '☁️'],
        ];

        foreach ($stacks as $stackData) {
            $categoryName = $stackData['category'];
            unset($stackData['category']);

            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => Str::slug($categoryName), 'color' => '#6366f1']
            );

            Stack::updateOrCreate(['name' => $stackData['name']], array_merge($stackData, ['category_id' => $category->id]));
        }
    }
}
