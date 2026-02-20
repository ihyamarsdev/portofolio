<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'DevOps', 'slug' => 'devops', 'color' => '#3b82f6'],
            ['name' => 'Cloud', 'slug' => 'cloud', 'color' => '#f59e0b'],
            ['name' => 'Docker', 'slug' => 'docker', 'color' => '#06b6d4'],
            ['name' => 'Kubernetes', 'slug' => 'kubernetes', 'color' => '#326ce5'],
            ['name' => 'CI/CD', 'slug' => 'ci-cd', 'color' => '#8b5cf6'],
            ['name' => 'Monitoring', 'slug' => 'monitoring', 'color' => '#ec4899'],
            ['name' => 'Infrastructure as Code', 'slug' => 'iac', 'color' => '#10b981'],
            ['name' => 'Networking', 'slug' => 'networking', 'color' => '#ef4444'],
            // Additional tech categories for stacks
            ['name' => 'Cloud Platforms', 'slug' => 'cloud-platforms', 'color' => '#0284c7'],
            ['name' => 'Containerization', 'slug' => 'containerization', 'color' => '#2563eb'],
            ['name' => 'Monitoring & Observability', 'slug' => 'monitoring-observability', 'color' => '#dc2626'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
