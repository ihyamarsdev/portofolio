<?php

namespace Database\Seeders;

use App\Models\ServiceStatus;
use Illuminate\Database\Seeder;

class ServiceStatusSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Portfolio Website',
                'url' => 'https://portfolio.example.com',
                'status' => 'operational',
                'uptime_percentage' => 99.9,
                'response_time_ms' => 120,
                'notes' => 'Main portfolio website',
            ],
            [
                'name' => 'API Gateway',
                'url' => 'https://api.example.com',
                'status' => 'operational',
                'uptime_percentage' => 99.8,
                'response_time_ms' => 45,
                'notes' => 'REST API endpoints',
            ],
            [
                'name' => 'Database Cluster',
                'url' => null,
                'status' => 'operational',
                'uptime_percentage' => 99.99,
                'response_time_ms' => 5,
                'notes' => 'PostgreSQL primary + replica',
            ],
            [
                'name' => 'Object Storage',
                'url' => 'https://cdn.example.com',
                'status' => 'operational',
                'uptime_percentage' => 100,
                'response_time_ms' => 80,
                'notes' => 'S3-compatible storage',
            ],
            [
                'name' => 'Monitoring Stack',
                'url' => 'https://monitoring.example.com',
                'status' => 'operational',
                'uptime_percentage' => 99.5,
                'response_time_ms' => 200,
                'notes' => 'Prometheus + Grafana',
            ],
        ];

        foreach ($services as $service) {
            ServiceStatus::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
