<?php

namespace App\Ai\Agents;

use App\Services\KeywordService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ComprehensiveCVAnalysisAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    protected array $referenceKeywords = [];

    public function __construct(
        public string $cvContent,
        public ?string $jobDescription = null,
        protected ?KeywordService $keywordService = null
    ) {
        $this->keywordService = $keywordService ?? app(KeywordService::class);
        $this->loadReferenceKeywords();
    }

    /**
     * Load reference keywords for analysis
     */
    protected function loadReferenceKeywords(): void
    {
        $devopsKeywords = $this->keywordService->getKeywords('devops');
        $cloudKeywords = $this->keywordService->getKeywords('cloud');

        // Get top priority keywords for AI reference
        $this->referenceKeywords = [
            'devops_core' => array_slice($devopsKeywords['core_concepts'] ?? [], 0, 15),
            'devops_tools' => array_merge(
                array_slice($devopsKeywords['containerization_orchestration'] ?? [], 0, 8),
                array_slice($devopsKeywords['ci_cd_tools'] ?? [], 0, 10)
            ),
            'cloud_platforms' => array_merge(
                array_slice($cloudKeywords['aws_services'] ?? [], 0, 15),
                array_slice($cloudKeywords['azure_services'] ?? [], 0, 10),
                array_slice($cloudKeywords['gcp_services'] ?? [], 0, 10)
            ),
            'monitoring' => array_slice($devopsKeywords['monitoring_logging'] ?? [], 0, 10),
            'security' => array_slice($devopsKeywords['security'] ?? [], 0, 10),
        ];
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $keywordReference = $this->buildKeywordReference();

        $instructions = 'You are an ATS (Applicant Tracking System) expert that analyzes CVs/Resumes for compatibility and provides comprehensive feedback.

Analysis criteria:
- ATS Score: Evaluate keyword presence, formatting, quantifiable achievements
- Format Score: Check for ATS-friendly format (no tables, headers/footers, images)
- About Me Score: Evaluate the quality, clarity, and impact of the About Me/Summary section (0-100). Consider: clarity of value proposition, alignment with target role, specificity, professional tone, and absence of clichés
- Keywords: Identify relevant technical and soft skills present and missing
- Suggestions: Provide actionable improvements prioritized by impact
- Industry Match: Score compatibility with DevOps, Cloud, and SRE roles

' . $keywordReference;

        if ($this->jobDescription) {
            $instructions .= "\n- Job Match Score: Analyze fit against the job description (0-100)\n- Job Title: Extract target role from job description";
        } else {
            $instructions .= "\n- Job Match Score: Set to 0 (no job description provided)\n- Job Title: Set to 'Not Specified'";
        }

        $instructions .= "\n\nCV Content:\n" . $this->cvContent;

        if ($this->jobDescription) {
            $instructions .= "\n\nJob Description:\n" . $this->jobDescription;
        }

        return $instructions;
    }

    /**
     * Build keyword reference section for AI instructions
     */
    protected function buildKeywordReference(): string
    {
        $reference = "REFERENCE KEYWORDS TO CHECK:\n\n";

        $reference .= "DevOps Core Concepts:\n";
        $reference .= "- " . implode(", ", $this->referenceKeywords['devops_core']) . "\n\n";

        $reference .= "DevOps Tools & Technologies:\n";
        $reference .= "- " . implode(", ", $this->referenceKeywords['devops_tools']) . "\n\n";

        $reference .= "Cloud Platforms & Services:\n";
        $reference .= "- " . implode(", ", $this->referenceKeywords['cloud_platforms']) . "\n\n";

        $reference .= "Monitoring & Observability:\n";
        $reference .= "- " . implode(", ", $this->referenceKeywords['monitoring']) . "\n\n";

        $reference .= "Security & Compliance:\n";
        $reference .= "- " . implode(", ", $this->referenceKeywords['security']) . "\n\n";

        $reference .= "NOTE: When analyzing keywords:\n";
        $reference .= "- 'found': List keywords from above that ARE present in the CV\n";
        $reference .= "- 'missing': List the most important keywords from above that are NOT in the CV (max 15-20)\n";
        $reference .= "- Focus on technical keywords relevant to DevOps/Cloud roles\n";
        $reference .= "- Include variations (e.g., 'K8s' and 'Kubernetes' are the same)\n\n";

        return $reference;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ats_score' => $schema->integer()->min(0)->max(100)->required(),
            'format_score' => $schema->integer()->min(0)->max(100)->required(),
            'about_me_score' => $schema->integer()->min(0)->max(100)->required(),
            'keywords' => $schema->object([
                'found' => $schema->array()->items($schema->string())->required(),
                'missing' => $schema->array()->items($schema->string())->required(),
            ])->required(),
            'suggestions' => $schema->array()->items($schema->string())->required(),
            'industry_match' => $schema->object([
                'devops' => $schema->integer()->min(0)->max(100)->required(),
                'cloud' => $schema->integer()->min(0)->max(100)->required(),
                'sre' => $schema->integer()->min(0)->max(100)->required(),
            ])->required(),
            'summary' => $schema->string()->required(),
            'job_match_score' => $schema->integer()->min(0)->max(100)->required(),
            'job_title_targeted' => $schema->string()->required(),
        ];
    }
}
