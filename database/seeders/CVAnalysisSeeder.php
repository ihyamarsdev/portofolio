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
                'keywords_found' => ['Kubernetes', 'Docker', 'Terraform', 'CI/CD', 'GitOps', 'AWS', 'Prometheus', 'Grafana', 'SLO', 'Incident Response'],
                'keywords_missing' => ['Vault', 'OPA', 'Trivy', 'OpenTelemetry', 'Error Budget'],
                'weak_evidence' => [
                    'Security impact is mentioned but not quantified with vulnerability reduction metrics.',
                    'Cloud cost optimization appears once but lacks before/after context.',
                ],
                'suggestions' => [
                    'Add 3 to 5 quantified outcomes for uptime, MTTR, deployment frequency, cost, and security findings.',
                    'Separate role alignment bullets for DevOps, DevSecOps, SRE, and Cloud Engineer applications.',
                    'Add production incident examples with detection, mitigation, prevention, and postmortem follow-up.',
                    'Include cloud security tools such as Vault, OPA Gatekeeper, Trivy, Snyk, Falco, or IAM hardening.',
                ],
                'strengths' => [
                    'Strong Kubernetes, Terraform, CI/CD, and observability coverage.',
                    'Good evidence for SRE and DevOps responsibilities.',
                    'Clear production-scale ownership signals.',
                ],
                'critical_gaps' => [
                    'DevSecOps evidence needs more security scanning and policy-as-code examples.',
                    'Cloud Engineer evidence needs more provider-specific services and architecture depth.',
                ],
                'missing_metrics' => ['MTTR', 'change failure rate', 'deployment frequency', 'vulnerability reduction', 'cloud cost reduction'],
                'rewrite_priorities' => [
                    'Rewrite experience bullets with action + tool + scale + result.',
                    'Move Kubernetes, Terraform, CI/CD, SRE, and security keywords into the top third of the CV.',
                    'Add a dedicated production reliability section.',
                ],
                'experience_review' => [
                    'senior_role' => 'Good role scope; add incident and cost metrics.',
                    'security_role' => 'Add vulnerability scanning, secrets management, and compliance outcomes.',
                    'cloud_role' => 'Add AWS/GCP/Azure service names and architecture decisions.',
                ],
                'industry_match' => [
                    'DevOps Engineer' => 94,
                    'SRE' => 90,
                    'Cloud Engineer' => 88,
                    'DevSecOps Engineer' => 82,
                ],
                'job_match_score' => 89,
                'job_title_targeted' => 'DevOps / DevSecOps / SRE / Cloud Engineer',
                'target_seniority' => 'Senior',
                'summary' => 'Strong infrastructure CV for DevOps, SRE, and Cloud Engineering roles with solid Kubernetes, Terraform, CI/CD, and observability evidence. DevSecOps alignment improves when security tooling, policy-as-code, and vulnerability reduction outcomes are quantified.',
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
                'keywords_missing' => ['Kubernetes', 'Terraform', 'GitOps', 'SLO', 'Prometheus', 'Trivy', 'Vault', 'OPA'],
                'weak_evidence' => [
                    'Most bullets describe responsibilities instead of measurable outcomes.',
                    'No explicit security controls, SLOs, incident response, or cloud architecture examples.',
                ],
                'suggestions' => [
                    'Add Kubernetes, Terraform, CI/CD, observability, and cloud security examples.',
                    'Convert system administration tasks into reliability and automation outcomes.',
                    'Add measurable results for uptime, deployment time, recovery time, and cost optimization.',
                ],
                'strengths' => [
                    'Good Linux, Docker, and basic cloud foundation.',
                    'Useful automation background for junior DevOps transition.',
                ],
                'critical_gaps' => [
                    'Insufficient DevSecOps evidence.',
                    'Missing SRE language such as SLO, error budget, MTTR, and incident response.',
                    'Limited infrastructure-as-code evidence.',
                ],
                'missing_metrics' => ['uptime', 'MTTR', 'deployment time', 'automation hours saved', 'cloud cost savings'],
                'rewrite_priorities' => [
                    'Add a targeted summary for DevOps, DevSecOps, SRE, or Cloud Engineer role.',
                    'Add a skills section grouped by Cloud, IaC, CI/CD, Containers, Observability, and Security.',
                    'Use stronger bullets with quantified operational impact.',
                ],
                'experience_review' => [
                    'systems_role' => 'Good operations foundation; needs modern platform tooling.',
                    'automation_role' => 'Python and scripting should be tied to measurable time savings.',
                ],
                'industry_match' => [
                    'DevOps Engineer' => 74,
                    'Cloud Engineer' => 72,
                    'SRE' => 66,
                    'DevSecOps Engineer' => 58,
                ],
                'job_match_score' => 70,
                'job_title_targeted' => 'Junior DevOps / Cloud Engineer',
                'target_seniority' => 'Junior to Mid',
                'summary' => 'Good baseline for a junior DevOps or Cloud Engineer role. The CV needs stronger Kubernetes, Terraform, CI/CD, SRE, and DevSecOps evidence before targeting senior infra roles.',
            ],
        ];

        foreach ($analyses as $analysis) {
            $profile->cvAnalyses()->create($analysis);
        }
    }
}
