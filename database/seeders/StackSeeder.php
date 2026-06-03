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
            ['name' => 'AWS', 'category' => 'Cloud Engineering', 'icon' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/amazonwebservices/amazonwebservices-original-wordmark.svg'],
            ['name' => 'GCP', 'category' => 'Cloud Engineering', 'icon' => 'https://cdn.simpleicons.org/googlecloud'],
            ['name' => 'Azure', 'category' => 'Cloud Engineering', 'icon' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/azure/azure-original.svg'],
            ['name' => 'DigitalOcean', 'category' => 'Cloud Engineering', 'icon' => 'https://cdn.simpleicons.org/digitalocean'],
            ['name' => 'Docker', 'category' => 'Container Orchestration', 'icon' => 'https://cdn.simpleicons.org/docker'],
            ['name' => 'Kubernetes', 'category' => 'Container Orchestration', 'icon' => 'https://cdn.simpleicons.org/kubernetes'],
            ['name' => 'Helm', 'category' => 'Container Orchestration', 'icon' => 'https://cdn.simpleicons.org/helm'],
            ['name' => 'ArgoCD', 'category' => 'CI/CD & GitOps', 'icon' => 'https://cdn.simpleicons.org/argo'],
            ['name' => 'GitHub Actions', 'category' => 'CI/CD & GitOps', 'icon' => 'https://cdn.simpleicons.org/githubactions'],
            ['name' => 'GitLab CI', 'category' => 'CI/CD & GitOps', 'icon' => 'https://cdn.simpleicons.org/gitlab'],
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code', 'icon' => 'https://cdn.simpleicons.org/terraform'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code', 'icon' => 'https://cdn.simpleicons.org/ansible'],
            ['name' => 'Pulumi', 'category' => 'Infrastructure as Code', 'icon' => 'https://cdn.simpleicons.org/pulumi'],
            ['name' => 'Prometheus', 'category' => 'Monitoring & Observability', 'icon' => 'https://cdn.simpleicons.org/prometheus'],
            ['name' => 'Grafana', 'category' => 'Monitoring & Observability', 'icon' => 'https://cdn.simpleicons.org/grafana'],
            ['name' => 'Alertmanager', 'category' => 'Monitoring & Observability', 'icon' => 'https://cdn.simpleicons.org/prometheus'],
            ['name' => 'OpenTelemetry', 'category' => 'Monitoring & Observability', 'icon' => 'https://cdn.simpleicons.org/opentelemetry'],
            ['name' => 'Trivy', 'category' => 'Cloud Security', 'icon' => 'https://cdn.simpleicons.org/trivy'],
            ['name' => 'Snyk', 'category' => 'Cloud Security', 'icon' => 'https://cdn.simpleicons.org/snyk'],
            ['name' => 'Falco', 'category' => 'Cloud Security', 'icon' => 'https://cdn.simpleicons.org/falco'],
            ['name' => 'Vault', 'category' => 'Cloud Security', 'icon' => 'https://cdn.simpleicons.org/vault'],
            ['name' => 'OPA Gatekeeper', 'category' => 'Cloud Security', 'icon' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/openapi/openapi-original.svg'],
            ['name' => 'Nginx', 'category' => 'Networking', 'icon' => 'https://cdn.simpleicons.org/nginx'],
            ['name' => 'Traefik', 'category' => 'Networking', 'icon' => 'https://cdn.simpleicons.org/traefikproxy'],
            ['name' => 'Cloudflare', 'category' => 'Networking', 'icon' => 'https://cdn.simpleicons.org/cloudflare'],
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
