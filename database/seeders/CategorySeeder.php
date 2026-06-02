<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'DevOps', 'slug' => 'devops', 'color' => '#0ea5e9'],
            ['name' => 'DevSecOps', 'slug' => 'devsecops', 'color' => '#ef4444'],
            ['name' => 'Site Reliability Engineering', 'slug' => 'sre', 'color' => '#10b981'],
            ['name' => 'Cloud Engineering', 'slug' => 'cloud-engineering', 'color' => '#f59e0b'],
            ['name' => 'Container Orchestration', 'slug' => 'container-orchestration', 'color' => '#326ce5'],
            ['name' => 'CI/CD & GitOps', 'slug' => 'ci-cd-gitops', 'color' => '#8b5cf6'],
            ['name' => 'Infrastructure as Code', 'slug' => 'iac', 'color' => '#14b8a6'],
            ['name' => 'Monitoring & Observability', 'slug' => 'monitoring-observability', 'color' => '#dc2626'],
            ['name' => 'Cloud Security', 'slug' => 'cloud-security', 'color' => '#f97316'],
            ['name' => 'Platform Engineering', 'slug' => 'platform-engineering', 'color' => '#6366f1'],
            ['name' => 'Networking', 'slug' => 'networking', 'color' => '#06b6d4'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
