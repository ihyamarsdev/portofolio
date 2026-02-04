<?php

namespace Database\Seeders;

use App\Models\HomeSettings;
use Illuminate\Database\Seeder;

class HomeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeSettings::firstOrCreate([], [
            'hero_greeting' => 'Hi, I\'m a',
            'hero_description' => 'I build and maintain scalable infrastructure using Kubernetes, Terraform, and Docker. Automating everything from CI/CD pipelines to cloud deployments.',
            'availability_status' => 'Available for new opportunities',
            'availability_active' => true,
            'terminal_commands' => [
                'kubectl apply -f infrastructure/',
                'terraform plan -out=tfplan',
                'docker-compose up -d',
                'ansible-playbook deploy.yml',
                'helm upgrade --install app ./chart',
                'gh workflow run deploy.yml',
            ],
            'tech_stack_title' => 'Tech Stack',
            'tech_stack_description' => 'Tools and technologies I work with daily',
            'featured_projects_title' => 'Featured Projects',
            'featured_projects_description' => 'Infrastructure and automation work I\'m proud of',
            'infrastructure_title' => 'Live Infrastructure',
            'infrastructure_description' => 'How this portfolio is deployed and monitored',
            'infrastructure_status_text' => 'All services running',
            'infrastructure_status' => 'operational',
            'cta_title' => 'Let\'s Build Something Together',
            'cta_description' => 'Looking for a DevOps engineer to help scale your infrastructure? Let\'s discuss how I can help automate and optimize your deployment pipeline.',
            'cta_primary_button_text' => 'Get In Touch',
            'cta_secondary_button_text' => 'Download CV',
            'site_brand' => '~/devops',
            'site_title' => 'DevOps Engineer',
            'meta_description' => 'Portfolio website of a DevOps Engineer specializing in cloud infrastructure, CI/CD, containerization, and automation.',
            'footer_text' => 'DevOps Portfolio. Built with Laravel & Filament.',
        ]);
    }
}
