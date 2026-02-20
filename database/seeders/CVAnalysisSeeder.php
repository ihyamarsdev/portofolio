<?php

namespace Database\Seeders;

use App\Models\CVAnalysis;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class CVAnalysisSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::where('is_primary', true)->first();

        if (!$profile) {
            return;
        }

        $analyses = [
            [
                'ats_score' => 85,
                'format_score' => 90,
                'keywords_found' => ['Kubernetes', 'Docker', 'Terraform', 'CI/CD', 'AWS', 'DevOps', 'Microservices', 'Monitoring'],
                'keywords_missing' => ['Serverless', 'GitLab CI', 'Azure'],
                'suggestions' => [
                    'Add more quantifiable achievements to your experience sections',
                    'Include specific tools and technologies in each project',
                    'Highlight leadership and mentoring experiences',
                    'Add certifications section prominently',
                    'Include links to portfolio and GitHub',
                ],
                'industry_match' => [
                    'Cloud Computing' => 92,
                    'DevOps' => 95,
                    'Software Development' => 78,
                    'SRE' => 88,
                ],
                'job_match_score' => 87,
                'job_title_targeted' => 'Senior DevOps Engineer',
                'summary' => 'Your CV demonstrates strong DevOps expertise with good coverage of modern infrastructure tools. Consider adding more specific achievements and expanding on soft skills to increase ATS compatibility.',
            ],
            [
                'ats_score' => 78,
                'format_score' => 85,
                'keywords_found' => ['Docker', 'AWS', 'Python', 'Linux', 'Nginx'],
                'keywords_missing' => ['Kubernetes', 'Terraform', 'Ansible', 'Prometheus', 'Grafana'],
                'suggestions' => [
                    'Add more cloud-native technologies',
                    'Expand on infrastructure as code experience',
                    'Include monitoring and observability tools',
                    'Highlight automation achievements',
                    'Add project outcomes and metrics',
                ],
                'industry_match' => [
                    'Cloud Computing' => 75,
                    'DevOps' => 80,
                    'System Administration' => 85,
                ],
                'job_match_score' => 72,
                'job_title_targeted' => 'DevOps Engineer',
                'summary' => 'Good foundation in system administration and basic DevOps tools. Focus on expanding knowledge in container orchestration and modern infrastructure practices.',
            ],
        ];

        foreach ($analyses as $analysis) {
            $profile->cvAnalyses()->create($analysis);
        }
    }
}
