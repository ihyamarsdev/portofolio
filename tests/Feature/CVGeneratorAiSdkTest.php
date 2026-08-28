<?php

namespace Tests\Feature;

use App\Ai\Agents\ComprehensiveCVAnalysisAgent;
use App\Filament\Pages\CVGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Ai;
use ReflectionMethod;
use Tests\TestCase;

class CVGeneratorAiSdkTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_generator_uses_laravel_ai_structured_response(): void
    {
        config([
            'cv.provider' => 'cv',
            'cv.model' => 'cv-test-model',
        ]);

        Ai::fakeAgent(ComprehensiveCVAnalysisAgent::class, [[
            'ats_score' => 88,
            'format_score' => 91,
            'about_me_score' => 84,
            'impact_score' => 83,
            'role_alignment_score' => 87,
            'security_score' => 79,
            'reliability_score' => 85,
            'cloud_score' => 82,
            'keywords' => [
                'found' => ['Laravel', 'Docker'],
                'missing' => ['Kubernetes'],
                'weak_evidence' => ['AWS'],
            ],
            'suggestions' => ['Add Kubernetes experience'],
            'strengths' => ['Strong CI/CD ownership'],
            'critical_gaps' => ['Missing reliability metrics'],
            'missing_metrics' => ['MTTR', 'deployment frequency'],
            'rewrite_priorities' => ['Rewrite summary for target role alignment'],
            'experience_review' => [[
                'section' => 'Senior DevOps Engineer at Example',
                'strength' => 'Strong automation ownership',
                'gap' => 'Missing quantified reliability outcomes',
                'rewrite_hint' => 'Add uptime, MTTR, and deployment metrics',
            ]],
            'industry_match' => [
                'devops' => 86,
                'devsecops' => 76,
                'cloud_engineer' => 82,
                'sre' => 78,
            ],
            'summary' => 'Strong CV for DevOps and cloud roles with moderate security depth.',
            'job_match_verification' => [
                [
                    'requirement' => 'Docker',
                    'status' => 'verified',
                    'evidence_quote' => 'Built CI/CD pipelines with Docker',
                    'reasoning' => 'Docker usage is demonstrated with pipeline context.',
                ],
                [
                    'requirement' => 'Kubernetes',
                    'status' => 'missing',
                    'evidence_quote' => '',
                    'reasoning' => 'Not present in the CV.',
                ],
            ],
            'job_title_targeted' => 'DevOps Engineer',
            'target_seniority' => 'Mid-Senior',
        ]]);

        $agent = new ComprehensiveCVAnalysisAgent('CV content');
        $method = new ReflectionMethod(CVGenerator::class, 'analyzeWithLaravelAi');
        $method->setAccessible(true);

        $result = $method->invoke(new CVGenerator, $agent);

        $this->assertSame(88, $result['ats_score']);
        $this->assertSame(83, $result['impact_score']);
        $this->assertSame(79, $result['security_score']);
        $this->assertSame(82, $result['cloud_score']);
        $this->assertSame(['Laravel', 'Docker'], $result['keywords']['found']);
        $this->assertSame(['AWS'], $result['keywords']['weak_evidence']);
        $this->assertSame('Mid-Senior', $result['target_seniority']);
        $this->assertSame(76, $result['industry_match']['devsecops']);
        $this->assertArrayNotHasKey('job_match_score', $result);
        $this->assertCount(2, $result['job_match_verification']);

        Ai::assertAgentWasPrompted(
            ComprehensiveCVAnalysisAgent::class,
            fn ($prompt): bool => $prompt->provider->name() === 'cv'
                && $prompt->model === 'cv-test-model'
                && $prompt->timeout === 120
        );
    }

    public function test_comprehensive_cv_analysis_agent_instructions_use_professional_role_specific_rubric(): void
    {
        $agent = new ComprehensiveCVAnalysisAgent(
            'Built CI/CD pipelines, managed Kubernetes clusters, improved uptime.',
            'We are hiring a Senior DevSecOps Engineer with cloud security and SRE experience.'
        );

        $instructions = (string) $agent->instructions();

        $this->assertStringContainsString('DevOps', $instructions);
        $this->assertStringContainsString('DevSecOps', $instructions);
        $this->assertStringContainsString('SRE', $instructions);
        $this->assertStringContainsString('Cloud Engineer', $instructions);
        $this->assertStringContainsString('Impact Score', $instructions);
        $this->assertStringContainsString('Security Score', $instructions);
        $this->assertStringContainsString('Reliability Score', $instructions);
        $this->assertStringContainsString('Cloud Score', $instructions);
        $this->assertStringContainsString('weak_evidence', $instructions);
        $this->assertStringContainsString('experience_review', $instructions);
        $this->assertStringContainsString('target_seniority', $instructions);
        $this->assertStringContainsString('JOB MATCH SCORING POLICY', $instructions);
    }

    public function test_agent_includes_job_match_verification_instructions_when_requirements_present(): void
    {
        $agent = new ComprehensiveCVAnalysisAgent(
            'Deployed Kubernetes clusters with 99.9% uptime for 50 microservices.',
            'Hiring a Senior DevOps Engineer with Kubernetes and Terraform.',
            'DevOps Engineer',
            [
                [
                    'requirement' => 'Kubernetes',
                    'type' => 'must_have',
                    'weight' => 5,
                    'status' => 'verified',
                    'evidence_quote' => 'Deployed Kubernetes clusters',
                    'reasoning' => '',
                ],
                [
                    'requirement' => 'Terraform',
                    'type' => 'must_have',
                    'weight' => 4,
                    'status' => 'missing',
                    'evidence_quote' => '',
                    'reasoning' => '',
                ],
            ]
        );

        $instructions = (string) $agent->instructions();

        $this->assertStringContainsString('JOB MATCH VERIFICATION (EVIDENCE-BASED)', $instructions);
        $this->assertStringContainsString('REQUIREMENTS EXTRACTED FROM THE JOB DESCRIPTION', $instructions);
        $this->assertStringContainsString('[must_have] (weight 5) Kubernetes', $instructions);
        $this->assertStringContainsString('[must_have] (weight 4) Terraform', $instructions);
        $this->assertStringContainsString('evidence_quote copied VERBATIM', $instructions);
        $this->assertStringContainsString('Do NOT output any numeric match score', $instructions);
    }
}
