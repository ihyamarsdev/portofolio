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
            // Cloud Platforms
            ['name' => 'AWS', 'category' => 'Cloud Platforms', 'icon' => '☁️'],
            ['name' => 'GCP', 'category' => 'Cloud Platforms', 'icon' => '🌐'],
            ['name' => 'Azure', 'category' => 'Cloud Platforms', 'icon' => '☁️'],
            ['name' => 'DigitalOcean', 'category' => 'Cloud Platforms', 'icon' => '🌊'],

            // Containerization
            ['name' => 'Docker', 'category' => 'Containerization', 'icon' => '🐳'],
            ['name' => 'Kubernetes', 'category' => 'Containerization', 'icon' => '⎈'],
            ['name' => 'Podman', 'category' => 'Containerization', 'icon' => '🦭'],

            // CI/CD
            ['name' => 'GitHub Actions', 'category' => 'CI/CD', 'icon' => '⚙️'],
            ['name' => 'GitLab CI', 'category' => 'CI/CD', 'icon' => '🦊'],
            ['name' => 'Jenkins', 'category' => 'CI/CD', 'icon' => '🔧'],
            ['name' => 'ArgoCD', 'category' => 'CI/CD', 'icon' => '🚀'],

            // Infrastructure as Code
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code', 'icon' => '🏗️'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code', 'icon' => '📜'],
            ['name' => 'Pulumi', 'category' => 'Infrastructure as Code', 'icon' => '🎯'],
            ['name' => 'Helm', 'category' => 'Infrastructure as Code', 'icon' => '⛵'],

            // Monitoring
            ['name' => 'Prometheus', 'category' => 'Monitoring', 'icon' => '🔥'],
            ['name' => 'Grafana', 'category' => 'Monitoring', 'icon' => '📊'],
            ['name' => 'Datadog', 'category' => 'Monitoring', 'icon' => '🐕'],
            ['name' => 'Uptime Kuma', 'category' => 'Monitoring', 'icon' => '📈'],

            // Networking
            ['name' => 'Nginx', 'category' => 'Networking', 'icon' => '🌐'],
            ['name' => 'Traefik', 'category' => 'Networking', 'icon' => '🔀'],
            ['name' => 'Cloudflare', 'category' => 'Networking', 'icon' => '☁️'],
        ];

        foreach ($stacks as $stackData) {
            $categoryName = $stackData['category'];
            unset($stackData['category']);

            // Find or create category for stacks
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'slug' => Str::slug($categoryName),
                    'color' => '#6366f1',
                ]
            );

            Stack::updateOrCreate(
                ['name' => $stackData['name']],
                array_merge($stackData, ['category_id' => $category->id])
            );
        }
    }
}
