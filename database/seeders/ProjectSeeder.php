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
                'description' => 'Production Kubernetes platform using Helm, ArgoCD, Terraform, Prometheus, Grafana, and SLO-based alerting. Built for safe rollout, observable operations, and fast incident response.',
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
                'skills' => ['Kubernetes', 'Helm', 'ArgoCD', 'Terraform', 'Prometheus', 'Grafana', 'SLO/SLI/Error Budgets'],
            ],
            [
                'title' => 'DevSecOps CI/CD Pipeline',
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
                'title' => 'SRE Observability & Incident Platform',
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
                'skills' => ['Prometheus', 'Grafana', 'OpenTelemetry', 'Alertmanager', 'Incident Management', 'SLO/SLI/Error Budgets'],
            ],
            [
                'title' => 'Multi-Cloud Terraform Modules',
                'slug' => 'multi-cloud-terraform-modules',
                'description' => 'Reusable Terraform modules for AWS, GCP, and Azure networking, compute, IAM, storage, state backends, and environment promotion workflows.',
                'repository_url' => 'https://github.com/username/terraform-cloud-modules',
                'live_url' => null,
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 4,
                'skills' => ['Terraform', 'AWS', 'GCP', 'Azure', 'Cloudflare'],
            ],
            [
                'title' => 'Secrets & Policy Management',
                'slug' => 'secrets-policy-management',
                'description' => 'Centralized secrets and policy-as-code implementation using Vault and OPA Gatekeeper for Kubernetes workloads and CI/CD pipelines.',
                'repository_url' => 'https://github.com/username/secrets-policy-management',
                'live_url' => null,
                'architecture_diagram' => null,
                'is_featured' => false,
                'order' => 5,
                'skills' => ['Secrets Management', 'Policy as Code', 'Vault', 'OPA Gatekeeper', 'Kubernetes'],
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
