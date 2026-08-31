<?php

namespace Tests\Unit;

use App\Support\AiStructuredOutputSanitizer;
use PHPUnit\Framework\TestCase;

class AiStructuredOutputSanitizerTest extends TestCase
{
    /**
     * Real-world artifacts captured from a leaked reasoning response
     * stored in cv_analyses (run id 7, 2026-08-31).
     */
    public function test_clean_list_removes_reasoning_and_markdown_artifacts(): void
    {
        $dirty = [
            '**',
            '**Step 7: Final review against rubric requirements**',
            'Not rewarding keyword stuffing without evidence ✓',
            'Penalizing vague claims ✓',
            '</details>',
            '<details>',
            '<summary>📊 CV Analysis for DevOps Engineer</summary>',
            '**ATS Score: 82/100**',
            '**Format Score: 78/100**',
            'Add quantified metrics to experience bullets',
            '- Include deployment frequency and MTTR metrics',
            '**keywords.weak_evidence:**',
            'Good structure with standard sections and clear hierarchy.',
            'Add quantified metrics to experience bullets',
            '**',
        ];

        $clean = AiStructuredOutputSanitizer::cleanList($dirty);

        $this->assertSame([
            'Add quantified metrics to experience bullets',
            'Include deployment frequency and MTTR metrics',
            'Good structure with standard sections and clear hierarchy.',
        ], $clean);
    }

    public function test_clean_list_keeps_genuine_content_intact(): void
    {
        $items = [
            'Strong hands-on experience with core DevOps tooling (Terraform, Ansible, Docker, Kubernetes, GitLab CI/CD)',
            'Missing deployment frequency, MTTR, cost savings, cluster sizes',
        ];

        $this->assertSame($items, AiStructuredOutputSanitizer::cleanList($items));
    }

    public function test_clean_text_strips_details_blocks_and_reasoning_lines(): void
    {
        $dirty = ">🔍 Reasoning Process</summary>\n\n".
            "**Step 1: Validate the input data**\n".
            "- CV is well-structured with clear sections\n\n\n\n".
            "**ATS Score: 82/100**\n".
            'The CV demonstrates solid DevOps foundations with quantified outcomes.';

        $clean = AiStructuredOutputSanitizer::cleanText($dirty);

        $this->assertStringNotContainsString('Reasoning', $clean);
        $this->assertStringNotContainsString('Step 1', $clean);
        $this->assertStringNotContainsString('summary', $clean);
        $this->assertStringNotContainsString('**', $clean);
        $this->assertStringContainsString('The CV demonstrates solid DevOps foundations with quantified outcomes.', $clean);
    }

    public function test_strip_reasoning_blocks_removes_closed_and_dangling_details(): void
    {
        $closed = "<details>\n<summary>🔍 Reasoning Process</summary>\ninternal thinking\n</details>\n".
            '{"ats_score": 82}';

        $this->assertSame('{"ats_score": 82}', AiStructuredOutputSanitizer::stripReasoningBlocks($closed));

        $dangling = "<details>\n<summary>🔍 Reasoning Process</summary>\ninternal thinking";

        $this->assertSame('', AiStructuredOutputSanitizer::stripReasoningBlocks($dangling));
    }

    public function test_sanitize_processes_nested_structures(): void
    {
        $payload = [
            'summary' => "**Overview**\nSolid candidate profile.",
            'suggestions' => ['**', 'Add MTTR metrics', '</details>'],
            'experience_review' => [
                [
                    'section' => '**AI-Care Platform**',
                    'strength' => 'Designed production Kubernetes cluster',
                    'gap' => '**Step 2: Extract metrics**',
                    'rewrite_hint' => 'Add cluster size and deployment frequency',
                ],
            ],
            'industry_match' => ['devops' => 80, 'sre' => 70],
        ];

        $clean = AiStructuredOutputSanitizer::sanitize($payload);

        $this->assertSame("Overview\nSolid candidate profile.", $clean['summary']);
        $this->assertSame(['Add MTTR metrics'], $clean['suggestions']);
        $this->assertSame('AI-Care Platform', $clean['experience_review'][0]['section']);
        $this->assertSame('', $clean['experience_review'][0]['gap']);
        $this->assertSame(80, $clean['industry_match']['devops']);
    }
}
