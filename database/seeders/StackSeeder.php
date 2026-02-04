<?php

namespace Database\Seeders;

use App\Models\Stack;
use Illuminate\Database\Seeder;

class StackSeeder extends Seeder
{
    public function run(): void
    {
        $stacks = [
            // Cloud Platforms
            ['name' => 'AWS', 'slug' => 'aws', 'category' => 'cloud', 'icon' => '☁️', 'color' => '#FF9900'],
            ['name' => 'GCP', 'slug' => 'gcp', 'category' => 'cloud', 'icon' => '🌐', 'color' => '#4285F4'],
            ['name' => 'Azure', 'slug' => 'azure', 'category' => 'cloud', 'icon' => '☁️', 'color' => '#0089D6'],
            ['name' => 'DigitalOcean', 'slug' => 'digitalocean', 'category' => 'cloud', 'icon' => '🌊', 'color' => '#0080FF'],

            // Containerization
            ['name' => 'Docker', 'slug' => 'docker', 'category' => 'containerization', 'icon' => '🐳', 'color' => '#2496ED'],
            ['name' => 'Kubernetes', 'slug' => 'kubernetes', 'category' => 'containerization', 'icon' => '⎈', 'color' => '#326CE5'],
            ['name' => 'Podman', 'slug' => 'podman', 'category' => 'containerization', 'icon' => '🦭', 'color' => '#892CA0'],

            // CI/CD
            ['name' => 'GitHub Actions', 'slug' => 'github-actions', 'category' => 'ci-cd', 'icon' => '⚙️', 'color' => '#2088FF'],
            ['name' => 'GitLab CI', 'slug' => 'gitlab-ci', 'category' => 'ci-cd', 'icon' => '🦊', 'color' => '#FC6D26'],
            ['name' => 'Jenkins', 'slug' => 'jenkins', 'category' => 'ci-cd', 'icon' => '🔧', 'color' => '#D24939'],
            ['name' => 'ArgoCD', 'slug' => 'argocd', 'category' => 'ci-cd', 'icon' => '🚀', 'color' => '#EF7B4D'],

            // Infrastructure as Code
            ['name' => 'Terraform', 'slug' => 'terraform', 'category' => 'iac', 'icon' => '🏗️', 'color' => '#7B42BC'],
            ['name' => 'Ansible', 'slug' => 'ansible', 'category' => 'iac', 'icon' => '📜', 'color' => '#EE0000'],
            ['name' => 'Pulumi', 'slug' => 'pulumi', 'category' => 'iac', 'icon' => '🎯', 'color' => '#8A3391'],
            ['name' => 'Helm', 'slug' => 'helm', 'category' => 'iac', 'icon' => '⛵', 'color' => '#0F1689'],

            // Monitoring
            ['name' => 'Prometheus', 'slug' => 'prometheus', 'category' => 'monitoring', 'icon' => '🔥', 'color' => '#E6522C'],
            ['name' => 'Grafana', 'slug' => 'grafana', 'category' => 'monitoring', 'icon' => '📊', 'color' => '#F46800'],
            ['name' => 'Datadog', 'slug' => 'datadog', 'category' => 'monitoring', 'icon' => '🐕', 'color' => '#632CA6'],
            ['name' => 'Uptime Kuma', 'slug' => 'uptime-kuma', 'category' => 'monitoring', 'icon' => '📈', 'color' => '#5CDD8B'],

            // Networking
            ['name' => 'Nginx', 'slug' => 'nginx', 'category' => 'networking', 'icon' => '🌐', 'color' => '#009639'],
            ['name' => 'Traefik', 'slug' => 'traefik', 'category' => 'networking', 'icon' => '🔀', 'color' => '#24A1C1'],
            ['name' => 'Cloudflare', 'slug' => 'cloudflare', 'category' => 'networking', 'icon' => '☁️', 'color' => '#F38020'],
        ];

        foreach ($stacks as $stack) {
            Stack::updateOrCreate(
                ['slug' => $stack['slug']],
                $stack
            );
        }
    }
}
