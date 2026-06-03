<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'position' => 'Senior DevOps / DevOps Engineer',
                'company' => 'TechCorp Indonesia',
                'location' => 'Jakarta, Indonesia',
                'employment_type' => 'Full-time',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'description' => 'Own Kubernetes platform reliability, GitOps delivery, observability, incident response, and infrastructure automation for production services serving 1M+ users.',
                'achievements' => [
                    'Improved deployment lead time from 2 hours to 10 minutes using GitOps and reusable pipelines',
                    'Maintained 99.99% uptime by defining deployment metrics, alert rules, and incident runbooks',
                    'Reduced MTTR by 60% with Prometheus, Grafana, Alertmanager, and structured postmortems',
                    'Standardized Terraform modules for multi-environment infrastructure provisioning',
                ],
                'order' => 1,
                'skills' => ['Kubernetes', 'Docker', 'Terraform', 'ArgoCD', 'Prometheus', 'Grafana', 'Incident Management', 'deployment metric/deployment metric/Rollback Metrics'],
            ],
            [
                'position' => 'DevOps Engineer',
                'company' => 'SecureCloud Labs',
                'location' => 'Bandung, Indonesia',
                'employment_type' => 'Full-time',
                'start_date' => '2021-06-01',
                'end_date' => '2022-12-31',
                'description' => 'Integrated security into CI/CD and DevOps infrastructure by adding vulnerability scanning, secrets management, pipeline automation, and container runtime controls.',
                'achievements' => [
                    'Added Trivy and Snyk scans to pipelines, blocking critical vulnerabilities before deployment',
                    'Implemented Vault-based secrets management and removed static secrets from repositories',
                    'Introduced OPA Gatekeeper policies for Kubernetes admission control',
                    'Reduced high-risk container findings by 45% through image hardening and base image standards',
                ],
                'order' => 2,
                'skills' => ['Trivy Vulnerability Scanning', 'SAST/DAST Pipelines', 'Secrets Management', 'Policy as Code', 'Runtime Security', 'GitHub Actions'],
            ],
            [
                'position' => 'DevOps Engineer',
                'company' => 'StartupXYZ',
                'location' => 'Remote, Indonesia',
                'employment_type' => 'Full-time',
                'start_date' => '2020-08-01',
                'end_date' => '2021-05-31',
                'description' => 'Built AWS infrastructure using Terraform, containerized services, managed networking, and improved DevOps deployment reliability.',
                'achievements' => [
                    'Provisioned AWS VPC, ECS, RDS, S3, IAM, and monitoring with Terraform modules',
                    'Migrated workloads to Kubernetes with zero-downtime rollout strategy',
                    'Reduced monthly cloud spend by 40% using rightsizing, schedules, and storage lifecycle policies',
                    'Automated backups and disaster recovery checks for critical services',
                ],
                'order' => 3,
                'skills' => ['AWS', 'Terraform', 'Docker', 'Kubernetes', 'Ansible', 'Cloudflare'],
            ],
        ];

        foreach ($experiences as $expData) {
            $skills = $expData['skills'];
            unset($expData['skills']);

            $experience = Experience::updateOrCreate(
                ['company' => $expData['company'], 'position' => $expData['position']],
                $expData
            );

            $experience->skills()->sync(
                Skill::whereIn('name', $skills)->pluck('id')->all()
            );
        }
    }
}
