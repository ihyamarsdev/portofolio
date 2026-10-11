<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class CVAnalysisSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::where('is_primary', true)->first();

        if (! $profile) {
            return;
        }

        $analyses = [
            [
                'ats_score' => 91,
                'format_score' => 88,
                'about_me_score' => 86,
                'impact_score' => 84,
                'role_alignment_score' => 93,
                'security_score' => 82,
                'reliability_score' => 90,
                'cloud_score' => 89,
                'keywords_found' => ['Kubernetes', 'Docker', 'Terraform', 'CI/CD', 'GitOps', 'AWS', 'Prometheus', 'Grafana', 'deployment metric', 'Incident Response'],
                'keywords_missing' => ['Vault', 'OPA', 'Trivy', 'OpenTelemetry', 'Rollback Metric'],
                'weak_evidence' => [
                    'Security impact is mentioned but not quantified with vulnerability reduction metrics.',
                    'Cloud cost optimization appears once but lacks before/after context.',
                ],
                'suggestions' => [
                    'Add 3 to 5 quantified outcomes for uptime, MTTR, deployment frequency, cost, and security findings.',
                    'Separate role alignment bullets for DevOps Engineer applications.',
                    'Add production incident examples with detection, mitigation, prevention, and postmortem follow-up.',
                    'Include DevOps automation tools such as Vault, OPA Gatekeeper, Trivy, Snyk, Falco, or IAM hardening.',
                ],
                'strengths' => [
                    'Strong Kubernetes, Terraform, CI/CD, and observability coverage.',
                    'Good evidence for DevOps and DevOps responsibilities.',
                    'Clear production-scale ownership signals.',
                ],
                'critical_gaps' => [
                    'DevOps evidence needs more security scanning and pipeline automation examples.',
                    'DevOps Engineer evidence needs more provider-specific services and architecture depth.',
                ],
                'missing_metrics' => ['MTTR', 'change failure rate', 'deployment frequency', 'vulnerability reduction', 'cloud cost reduction'],
                'rewrite_priorities' => [
                    'Rewrite experience bullets with action + tool + scale + result.',
                    'Move Kubernetes, Terraform, CI/CD, DevOps, and security keywords into the top third of the CV.',
                    'Add a dedicated production reliability section.',
                ],
                'experience_review' => [
                    'senior_role' => 'Good role scope; add incident and cost metrics.',
                    'security_role' => 'Add vulnerability scanning, secrets management, and compliance outcomes.',
                    'cloud_role' => 'Add AWS service names and architecture decisions.',
                ],
                'industry_match' => [
                    'DevOps Engineer' => 94,
                    'DevOps' => 90,
                    'DevOps Engineer' => 88,
                    'DevOps Engineer' => 82,
                ],
                'job_match_score' => 89,
                'job_title_targeted' => 'DevOps Engineer',
                'target_seniority' => 'Senior',
                'summary' => 'Strong infrastructure CV for DevOps, DevOps, and DevOps Engineering roles with solid Kubernetes, Terraform, CI/CD, and observability evidence. DevOps alignment improves when security tooling, pipeline automation, and vulnerability reduction outcomes are quantified.',
                'job_match_detail' => [
                    'formula_version' => '1.0',
                    'overall' => 89,
                    'band' => [
                        'label' => 'Strong Match',
                        'definition' => 'Comprehensive evidence across core and secondary requirements.',
                    ],
                    'must_have_coverage' => 92,
                    'nice_to_have_coverage' => 85,
                    'evidence_quality' => 90,
                    'counts' => [
                        'verified' => 8,
                        'claimed' => 3,
                        'missing' => 1,
                    ],
                    'requirements' => [
                        [
                            'requirement' => 'Kubernetes & Container Orchestration',
                            'type' => 'must_have',
                            'weight' => 5,
                            'status' => 'verified',
                            'evidence_quote' => 'Managed 50+ microservices on EKS with 99.99% uptime',
                            'reasoning' => 'Clear operational scale and measurable reliability.',
                        ],
                        [
                            'requirement' => 'Terraform Infrastructure as Code',
                            'type' => 'must_have',
                            'weight' => 5,
                            'status' => 'verified',
                            'evidence_quote' => 'Provisioned multi-region AWS environments using modular Terraform',
                            'reasoning' => 'Demonstrated IaC practices with state locking and CI pipelines.',
                        ],
                        [
                            'requirement' => 'CI/CD Pipeline Automation',
                            'type' => 'must_have',
                            'weight' => 4,
                            'status' => 'verified',
                            'evidence_quote' => 'Cut deployment time from 45m to 8m via GitHub Actions & ArgoCD',
                            'reasoning' => 'Quantified delivery speed improvement.',
                        ],
                        [
                            'requirement' => 'Observability & Incident Management',
                            'type' => 'must_have',
                            'weight' => 4,
                            'status' => 'verified',
                            'evidence_quote' => 'Prometheus & Grafana alerting with pager rotation',
                            'reasoning' => 'Direct production monitoring evidence.',
                        ],
                        [
                            'requirement' => 'Cloud Security & IAM Hardening',
                            'type' => 'nice_to_have',
                            'weight' => 3,
                            'status' => 'claimed',
                            'evidence_quote' => 'Configured AWS IAM least-privilege policies',
                            'reasoning' => 'Mentioned without vulnerability reduction or audit metrics.',
                        ],
                        [
                            'requirement' => 'Policy as Code (OPA / Kyverno)',
                            'type' => 'nice_to_have',
                            'weight' => 2,
                            'status' => 'missing',
                            'evidence_quote' => '',
                            'reasoning' => 'No admission controller policies or OPA rules found in CV.',
                        ],
                    ],
                ],
            ],
            [
                'ats_score' => 76,
                'format_score' => 82,
                'about_me_score' => 70,
                'impact_score' => 62,
                'role_alignment_score' => 74,
                'security_score' => 58,
                'reliability_score' => 68,
                'cloud_score' => 72,
                'keywords_found' => ['Linux', 'Docker', 'AWS', 'Nginx', 'Python', 'Monitoring'],
                'keywords_missing' => ['Kubernetes', 'Terraform', 'GitOps', 'deployment metric', 'Prometheus', 'Trivy', 'Vault', 'OPA'],
                'weak_evidence' => [
                    'Most bullets describe responsibilities instead of measurable outcomes.',
                    'No explicit security controls, deployment metrics, incident response, or cloud architecture examples.',
                ],
                'suggestions' => [
                    'Add Kubernetes, Terraform, CI/CD, observability, and DevOps automation examples.',
                    'Convert system administration tasks into reliability and automation outcomes.',
                    'Add measurable results for uptime, deployment time, recovery time, and cost optimization.',
                ],
                'strengths' => [
                    'Good Linux, Docker, and basic cloud foundation.',
                    'Useful automation background for junior DevOps transition.',
                ],
                'critical_gaps' => [
                    'Insufficient DevOps evidence.',
                    'Missing DevOps language such as deployment metric, rollback metric, MTTR, and incident response.',
                    'Limited infrastructure-as-code evidence.',
                ],
                'missing_metrics' => ['uptime', 'MTTR', 'deployment time', 'automation hours saved', 'cloud cost savings'],
                'rewrite_priorities' => [
                    'Add a targeted summary for DevOps, DevOps, DevOps, or DevOps Engineer role.',
                    'Add a skills section grouped by Cloud, IaC, CI/CD, Containers, Observability, and Security.',
                    'Use stronger bullets with quantified operational impact.',
                ],
                'experience_review' => [
                    'systems_role' => 'Good operations foundation; needs modern platform tooling.',
                    'automation_role' => 'Python and scripting should be tied to measurable time savings.',
                ],
                'industry_match' => [
                    'DevOps Engineer' => 74,
                    'DevOps Engineer' => 72,
                    'DevOps' => 66,
                    'DevOps Engineer' => 58,
                ],
                'job_match_score' => 70,
                'job_title_targeted' => 'Junior DevOps / DevOps Engineer',
                'target_seniority' => 'Junior to Mid',
                'summary' => 'Good baseline for a junior DevOps or DevOps Engineer role. The CV needs stronger Kubernetes, Terraform, CI/CD, DevOps, and DevOps evidence before targeting senior infra roles.',
                'job_match_detail' => [
                    'formula_version' => '1.0',
                    'overall' => 70,
                    'band' => [
                        'label' => 'Good Match',
                        'definition' => 'Satisfactory foundational requirements met.',
                    ],
                    'must_have_coverage' => 70,
                    'nice_to_have_coverage' => 60,
                    'evidence_quality' => 65,
                    'counts' => [
                        'verified' => 4,
                        'claimed' => 4,
                        'missing' => 3,
                    ],
                    'requirements' => [
                        [
                            'requirement' => 'Linux Administration',
                            'type' => 'must_have',
                            'weight' => 5,
                            'status' => 'verified',
                            'evidence_quote' => 'Managed Debian/Ubuntu servers',
                            'reasoning' => 'Clear operational work with Linux.',
                        ],
                        [
                            'requirement' => 'Docker & Containerization',
                            'type' => 'must_have',
                            'weight' => 4,
                            'status' => 'verified',
                            'evidence_quote' => 'Built container images for Python services',
                            'reasoning' => 'Practical Docker packaging evidence.',
                        ],
                        [
                            'requirement' => 'Kubernetes Orchestration',
                            'type' => 'must_have',
                            'weight' => 5,
                            'status' => 'claimed',
                            'evidence_quote' => 'Basic cluster operations',
                            'reasoning' => 'Lacks production scale metrics.',
                        ],
                        [
                            'requirement' => 'Terraform IaC',
                            'type' => 'must_have',
                            'weight' => 4,
                            'status' => 'missing',
                            'evidence_quote' => '',
                            'reasoning' => 'No Terraform evidence found.',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($analyses as $analysis) {
            $profile->cvAnalyses()->create($analysis);
        }
    }
}
