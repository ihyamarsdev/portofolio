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
                'title' => 'GitOps Kubernetes Platform',
                'slug' => 'gitops-kubernetes-platform',
                'description' => 'Production Kubernetes platform using Helm, ArgoCD, Terraform, Prometheus, Grafana, and deployment metric-based alerting. Built for safe rollout, observable operations, and fast incident response.',
                'repository_url' => 'https://github.com/username/gitops-kubernetes-platform',
                'live_url' => 'https://platform.example.com',
                'architecture_diagram' => 'graph LR
    A[Git Repository] --> B[ArgoCD]
    B --> C[Kubernetes Cluster]
    C --> D[Ingress]
    C --> E[Workloads]
    C --> F[Prometheus]
    F --> G[Grafana]
    F --> H[Alertmanager]',
                'is_featured' => true,
                'order' => 1,
                'skills' => ['Kubernetes', 'Helm', 'ArgoCD', 'Terraform', 'Prometheus', 'Grafana', 'deployment metric/deployment metric/Rollback Metrics'],
            ],
            [
                'title' => 'DevOps CI/CD Pipeline',
                'slug' => 'devsecops-cicd-pipeline',
                'description' => 'Secure delivery pipeline with SAST, DAST, dependency scanning, container image scanning, SBOM generation, secrets detection, and policy gates before production deploy.',
                'repository_url' => 'https://github.com/username/devsecops-pipeline',
                'live_url' => null,
                'architecture_diagram' => 'graph TD
    A[Pull Request] --> B[Unit Tests]
    B --> C[SAST]
    C --> D[Dependency Scan]
    D --> E[Trivy Image Scan]
    E --> F[Policy Gate]
    F --> G[Deploy]
    G --> H[Runtime Monitoring]',
                'is_featured' => true,
                'order' => 2,
                'skills' => ['GitHub Actions', 'Trivy Vulnerability Scanning', 'SAST/DAST Pipelines', 'Policy as Code', 'Runtime Security'],
            ],
            [
                'title' => 'DevOps Observability & Incident Platform',
                'slug' => 'sre-observability-incident-platform',
                'description' => 'Observability platform covering RED/USE metrics, traces, logs, burn-rate alerts, runbooks, and incident workflows to reduce MTTR and improve production reliability.',
                'repository_url' => 'https://github.com/username/sre-observability',
                'live_url' => 'https://grafana.example.com',
                'architecture_diagram' => 'graph LR
    A[Services] --> B[OpenTelemetry]
    B --> C[Prometheus]
    B --> D[Traces]
    C --> E[Grafana]
    C --> F[Alertmanager]
    F --> G[On-call]
    G --> H[Postmortem]',
                'is_featured' => true,
                'order' => 3,
                'skills' => ['Prometheus', 'Grafana', 'OpenTelemetry', 'Alertmanager', 'Incident Management', 'deployment metric/deployment metric/Rollback Metrics'],
            ],
            [
                'title' => 'DevOps Terraform Modules',
                'slug' => 'devops-terraform-modules',
                'description' => 'Reusable Terraform modules for AWS networking, compute, IAM, storage, state backends, and environment promotion workflows.',
                'repository_url' => 'https://github.com/username/terraform-cloud-modules',
                'live_url' => null,
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 4,
                'skills' => ['Terraform', 'AWS', 'Docker', 'GitHub Actions', 'Cloudflare'],
            ],
            [
                'title' => 'DevOps Release Automation Toolkit',
                'slug' => 'devops-release-automation-toolkit',
                'description' => 'Reusable scripts, pipeline templates, deployment commands, and operational runbooks for predictable DevOps release workflows.',
                'repository_url' => 'https://github.com/username/devops-release-automation-toolkit',
                'live_url' => null,
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 5,
                'skills' => ['GitHub Actions', 'Docker', 'Kubernetes', 'Helm', 'Ansible'],
            ],
        ];

        foreach ($projects as $projectData) {
            $skills = $projectData['skills'];
            unset($projectData['skills']);

            $project = Project::updateOrCreate(['slug' => $projectData['slug']], $projectData);
            $project->skills()->sync(Skill::whereIn('name', $skills)->pluck('id')->all());
        }
    }
}
