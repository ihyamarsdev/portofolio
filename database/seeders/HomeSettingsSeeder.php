<?php

namespace Database\Seeders;

use App\Models\HomeSettings;
use Illuminate\Database\Seeder;

class HomeSettingsSeeder extends Seeder
{
    public function run(): void
    {
        HomeSettings::updateOrCreate([], [
            'hero_greeting' => 'Hi, I\'m an',
            'hero_description' => 'I build secure, reliable, and observable infrastructure for DevOps, DevSecOps, SRE, and Cloud Engineering roles. Kubernetes, Terraform, CI/CD, cloud security, incident response, and production reliability are my core focus areas.',
            'availability_status' => 'Available for DevOps, DevSecOps, SRE, and Cloud Engineering opportunities',
            'availability_active' => true,
            'terminal_commands' => [
                'terraform plan -out=tfplan',
                'kubectl rollout status deploy/api',
                'trivy image registry/app:latest',
                'promtool check rules alerts.yml',
                'argocd app sync production',
                'vault kv get secret/platform',
            ],
            'tech_stack_title' => 'Infrastructure Stack',
            'tech_stack_description' => 'Tools used across cloud, platform, security, reliability, and automation work',
            'featured_projects_title' => 'Infrastructure Case Studies',
            'featured_projects_description' => 'DevOps, DevSecOps, SRE, and Cloud Engineering projects with measurable outcomes',
            'infrastructure_title' => 'Production Reliability',
            'infrastructure_description' => 'Live systems, monitoring, uptime, and operational health',
            'infrastructure_status_text' => 'All services healthy',
            'infrastructure_status' => 'operational',
            'cta_title' => 'Need secure and reliable infrastructure?',
            'cta_description' => 'Let\'s discuss platform automation, cloud security, CI/CD, SLOs, observability, or production reliability improvements.',
            'cta_primary_button_text' => 'Get In Touch',
            'cta_secondary_button_text' => 'Analyze CV',
            'site_brand' => '~/infra',
            'site_title' => 'DevOps · DevSecOps · SRE · Cloud Engineer',
            'meta_description' => 'Portfolio for DevOps, DevSecOps, SRE, and Cloud Engineering with focus on Kubernetes, Terraform, CI/CD, cloud security, observability, and reliability.',
            'footer_text' => 'Infrastructure Engineering Portfolio. Built with Laravel & Filament.',
        ]);
    }
}
