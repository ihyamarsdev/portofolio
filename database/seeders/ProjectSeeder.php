<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Microservices Infrastructure',
                'slug' => 'microservices-infrastructure',
                'description' => 'Built a complete microservices infrastructure using Kubernetes, Helm, and ArgoCD for continuous deployment. Implemented service mesh with Istio for secure inter-service communication.',
                'repository_url' => 'https://github.com/username/microservices-infra',
                'live_url' => 'https://demo.microservices.dev',
                'architecture_diagram' => 'graph LR
    A[Client] --> B[Nginx Ingress]
    B --> C[Service A]
    B --> D[Service B]
    B --> E[Service C]
    C --> F[(Redis)]
    D --> G[(PostgreSQL)]
    E --> H[(MongoDB)]',
                'is_featured' => true,
                'order' => 1,
                'skills' => ['Kubernetes', 'Helm', 'ArgoCD', 'Istio', 'Docker'],
            ],
            [
                'title' => 'CI/CD Pipeline Automation',
                'slug' => 'ci-cd-pipeline-automation',
                'description' => 'Implemented comprehensive CI/CD pipelines using GitHub Actions. Includes automated testing, Docker image building, security scanning, and multi-environment deployments.',
                'repository_url' => 'https://github.com/username/cicd-pipeline',
                'live_url' => null,
                'architecture_diagram' => 'graph TD
    A[Push Code] --> B[GitHub Actions]
    B --> C[Run Tests]
    C --> D[Build Docker Image]
    D --> E[Security Scan]
    E --> F[Push to Registry]
    F --> G[Deploy to Staging]
    G --> H[Smoke Tests]
    H --> I[Deploy to Production]',
                'is_featured' => true,
                'order' => 2,
                'skills' => ['GitHub Actions', 'Docker', 'Terraform', 'Ansible'],
            ],
            [
                'title' => 'Monitoring & Alerting System',
                'slug' => 'monitoring-alerting-system',
                'description' => 'Deployed comprehensive monitoring stack with Prometheus, Grafana, and AlertManager. Implemented custom dashboards and alerting rules for proactive incident response.',
                'repository_url' => 'https://github.com/username/monitoring-stack',
                'live_url' => 'https://grafana.monitoring.dev',
                'architecture_diagram' => 'graph LR
    A[Applications] --> B[Prometheus]
    B --> C[Grafana]
    B --> D[AlertManager]
    D --> E[PagerDuty]
    D --> F[Slack]',
                'is_featured' => true,
                'order' => 3,
                'skills' => ['Prometheus', 'Grafana', 'AlertManager', 'Docker'],
            ],
            [
                'title' => 'AWS Serverless API',
                'slug' => 'aws-serverless-api',
                'description' => 'Built scalable serverless REST API using AWS Lambda, API Gateway, and DynamoDB. Implemented authentication via Cognito and automated deployments with Terraform.',
                'repository_url' => 'https://github.com/username/serverless-api',
                'live_url' => 'https://api.serverless.dev',
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 4,
                'skills' => ['AWS Lambda', 'AWS API Gateway', 'DynamoDB', 'Terraform', 'Cognito'],
            ],
            [
                'title' => 'Docker Swarm Cluster',
                'slug' => 'docker-swarm-cluster',
                'description' => 'Set up and managed Docker Swarm cluster for container orchestration. Implemented load balancing, rolling updates, and health checks for high availability.',
                'repository_url' => 'https://github.com/username/swarm-cluster',
                'live_url' => null,
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 5,
                'skills' => ['Docker', 'Docker Compose', 'Nginx', 'Traefik'],
            ],
            [
                'title' => 'Infrastructure as Code with Terraform',
                'slug' => 'terraform-infrastructure',
                'description' => 'Developed Terraform modules for reproducible infrastructure deployment across multiple environments. Implemented state management and remote backend configuration.',
                'repository_url' => 'https://github.com/username/terraform-modules',
                'live_url' => null,
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 6,
                'skills' => ['Terraform', 'AWS', 'Ansible'],
            ],
        ];

        foreach ($projects as $projectData) {
            $skills = $projectData['skills'];
            unset($projectData['skills']);

            $project = Project::updateOrCreate(
                ['slug' => $projectData['slug']],
                $projectData
            );

            // Attach skills
            foreach ($skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                if ($skill) {
                    $project->skills()->syncWithoutDetaching($skill->id);
                }
            }
        }
    }
}
