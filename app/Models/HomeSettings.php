<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomeSettings extends Model
{
    use HasTranslations;

    public array $translatable = [
        'hero_greeting',
        'hero_description',
        'availability_status',
        'tech_stack_title',
        'tech_stack_description',
        'featured_projects_title',
        'featured_projects_description',
        'infrastructure_title',
        'infrastructure_description',
        'cta_title',
        'cta_description',
        'cta_primary_button_text',
        'cta_secondary_button_text',
        'site_brand',
        'site_title',
        'meta_description',
        'footer_text',
    ];

    protected $fillable = [
        'hero_greeting',
        'hero_description',
        'availability_status',
        'availability_active',
        'terminal_commands',
        'tech_stack_title',
        'tech_stack_description',
        'featured_projects_title',
        'featured_projects_description',
        'infrastructure_title',
        'infrastructure_description',
        'infrastructure_services',
        'infrastructure_status_text',
        'infrastructure_status',
        'cta_title',
        'cta_description',
        'cta_primary_button_text',
        'cta_secondary_button_text',
        'site_brand',
        'site_title',
        'meta_description',
        'footer_text',
    ];

    protected $casts = [
        'terminal_commands' => 'array',
        'infrastructure_services' => 'array',
        'availability_active' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::firstOrCreate([], [
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
            'footer_text' => 'DevOps Portfolio',
        ]);
    }
}
