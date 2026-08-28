<?php

namespace Tests\Unit;

use App\Services\JobMatchService;
use App\Services\KeywordService;
use Tests\TestCase;

class JobMatchServiceTest extends TestCase
{
    protected JobMatchService $service;

    protected string $cvContent = <<<'CV'
DEVOPS ENGINEER
DevOps Engineer with 6 years of experience.

EXPERIENCE
Senior DevOps Engineer at Acme (2020-2024)
- Deployed and managed Kubernetes clusters serving 50 microservices with 99.9% uptime
- Built CI/CD pipelines with Docker and GitHub Actions, reducing deployment time by 40%
- Automated infrastructure provisioning with Terraform for 120 servers

SKILLS
Kubernetes, Docker, Terraform, AWS, CI/CD, Monitoring
CV;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new JobMatchService(app(KeywordService::class));
    }

    public function test_classifies_requirements_as_verified_claimed_or_missing(): void
    {
        $requirements = [
            ['requirement' => 'Kubernetes', 'type' => 'must_have', 'weight' => 5],
            ['requirement' => 'Terraform', 'type' => 'must_have', 'weight' => 4],
            ['requirement' => 'AWS', 'type' => 'must_have', 'weight' => 3],
            ['requirement' => 'Kafka', 'type' => 'nice_to_have', 'weight' => 2],
        ];

        $matches = $this->service->matchRequirements($requirements, $this->cvContent);

        $byRequirement = collect($matches)->keyBy('requirement');

        $this->assertSame('verified', $byRequirement['Kubernetes']['status']);
        $this->assertNotSame('', $byRequirement['Kubernetes']['evidence_quote']);

        $this->assertSame('verified', $byRequirement['Terraform']['status']);

        // Mentioned in the skills list without any measurable proof.
        $this->assertSame('claimed', $byRequirement['AWS']['status']);

        $this->assertSame('missing', $byRequirement['Kafka']['status']);
        $this->assertSame('', $byRequirement['Kafka']['evidence_quote']);
    }

    public function test_computes_objective_score_deterministically(): void
    {
        $matches = [
            ['requirement' => 'Kubernetes', 'type' => 'must_have', 'weight' => 5, 'status' => 'verified', 'evidence_quote' => 'x', 'reasoning' => ''],
            ['requirement' => 'Terraform', 'type' => 'must_have', 'weight' => 4, 'status' => 'verified', 'evidence_quote' => 'x', 'reasoning' => ''],
            ['requirement' => 'AWS', 'type' => 'must_have', 'weight' => 3, 'status' => 'claimed', 'evidence_quote' => 'x', 'reasoning' => ''],
            ['requirement' => 'Kafka', 'type' => 'must_have', 'weight' => 2, 'status' => 'missing', 'evidence_quote' => '', 'reasoning' => ''],
        ];

        $score = $this->service->computeScore($matches);

        // Weighted coverage: (5*1.0 + 4*1.0 + 3*0.4 + 2*0.0) / 14 = 0.7286
        // Evidence quality: 2 verified / 3 matched = 0.6667
        // overall = 100 * (0.8 * 0.7286 + 0.2 * 0.6667) = 71.6 -> 72
        $this->assertSame(72, $score['overall']);
        $this->assertSame(73, $score['must_have_coverage']);
        $this->assertSame(67, $score['evidence_quality']);
        $this->assertSame(0, $score['nice_to_have_coverage']);
        $this->assertSame('Good Match', $score['band']['label']);
        $this->assertSame(JobMatchService::FORMULA_VERSION, $score['formula_version']);
        $this->assertSame(2, $score['counts']['verified']);
        $this->assertSame(1, $score['counts']['claimed']);
        $this->assertSame(1, $score['counts']['missing']);
    }

    public function test_score_is_zero_when_no_requirements_matched(): void
    {
        $score = $this->service->computeScore([]);

        $this->assertSame(0, $score['overall']);
        $this->assertSame('Weak Match', $score['band']['label']);
    }

    public function test_reconcile_downgrades_verified_without_verifiable_quote(): void
    {
        $preliminary = [
            ['requirement' => 'Docker', 'type' => 'must_have', 'weight' => 4, 'status' => 'verified', 'evidence_quote' => 'Built CI/CD pipelines with Docker', 'reasoning' => ''],
            ['requirement' => 'Prometheus', 'type' => 'must_have', 'weight' => 3, 'status' => 'claimed', 'evidence_quote' => 'some line', 'reasoning' => ''],
        ];

        $verification = [
            [
                'requirement' => 'Docker',
                'status' => 'verified',
                'evidence_quote' => 'This quote was completely fabricated by the AI',
                'reasoning' => 'Claimed verified with a hallucinated quote.',
            ],
            [
                'requirement' => 'Prometheus',
                'status' => 'not-a-valid-status',
                'evidence_quote' => '',
                'reasoning' => '',
            ],
        ];

        $reconciled = $this->service->reconcile($preliminary, $verification, $this->cvContent);
        $byRequirement = collect($reconciled)->keyBy('requirement');

        // Verified without a verifiable quote must be downgraded to claimed.
        $this->assertSame('claimed', $byRequirement['Docker']['status']);
        $this->assertStringContainsString('downgraded to claimed', $byRequirement['Docker']['reasoning']);

        // Invalid statuses are rejected and the preliminary status is kept.
        $this->assertSame('claimed', $byRequirement['Prometheus']['status']);
    }

    public function test_reconcile_accepts_valid_ai_verification(): void
    {
        $preliminary = [
            ['requirement' => 'Docker', 'type' => 'must_have', 'weight' => 4, 'status' => 'claimed', 'evidence_quote' => '', 'reasoning' => ''],
        ];

        $verification = [
            [
                'requirement' => 'Docker',
                'status' => 'verified',
                'evidence_quote' => 'Built CI/CD pipelines with Docker and GitHub Actions, reducing deployment time by 40%',
                'reasoning' => 'Quantified outcome present.',
            ],
        ];

        $reconciled = $this->service->reconcile($preliminary, $verification, $this->cvContent);

        $this->assertSame('verified', $reconciled[0]['status']);
        $this->assertStringContainsString('reducing deployment time by 40%', $reconciled[0]['evidence_quote']);
    }

    public function test_deterministic_requirement_extraction_fallback(): void
    {
        $jd = 'We are hiring a DevOps engineer. You will work with Kubernetes and Docker daily. 5+ years experience required.';

        $extracted = $this->service->extractRequirementsDeterministic($jd, 'DevOps Engineer');

        $this->assertSame('deterministic', $extracted['source']);
        $this->assertNotEmpty($extracted['requirements']);
        $this->assertSame('DevOps Engineer', $extracted['inferred_title']);
        $this->assertSame('Mid-Senior', $extracted['inferred_seniority']);

        foreach ($extracted['requirements'] as $requirement) {
            $this->assertSame('must_have', $requirement['type']);
            $this->assertSame(3, $requirement['weight']);
        }
    }

    public function test_bands_are_anchored_to_expected_thresholds(): void
    {
        $this->assertSame('Strong Match', $this->service->getScoreBand(85)['label']);
        $this->assertSame('Strong Match', $this->service->getScoreBand(100)['label']);
        $this->assertSame('Good Match', $this->service->getScoreBand(70)['label']);
        $this->assertSame('Partial Match', $this->service->getScoreBand(50)['label']);
        $this->assertSame('Weak Match', $this->service->getScoreBand(49)['label']);
        $this->assertSame('Weak Match', $this->service->getScoreBand(0)['label']);
    }
}
