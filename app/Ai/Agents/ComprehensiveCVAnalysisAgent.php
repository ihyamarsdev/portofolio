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
        public ?string $targetRole = null,
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
        $this->referenceKeywords = [
            'devops' => [
                'core' => array_slice($this->keywordService->getKeywordsByCategory('devops', 'core_concepts'), 0, 12),
                'tools' => array_merge(
                    array_slice($this->keywordService->getKeywordsByCategory('devops', 'containerization_orchestration'), 0, 8),
                    array_slice($this->keywordService->getKeywordsByCategory('devops', 'ci_cd_tools'), 0, 8)
                ),
            ],
            'devsecops' => [
                'core' => array_slice($this->keywordService->getKeywordsByCategory('devsecops', 'core_concepts'), 0, 10),
                'security_tooling' => array_slice($this->keywordService->getKeywordsByCategory('devsecops', 'security_tooling'), 0, 12),
            ],
            'sre' => [
                'core' => array_slice($this->keywordService->getKeywordsByCategory('sre', 'core_concepts'), 0, 10),
                'reliability' => array_slice($this->keywordService->getKeywordsByCategory('sre', 'reliability_engineering'), 0, 10),
                'observability' => array_slice($this->keywordService->getKeywordsByCategory('sre', 'observability'), 0, 10),
            ],
            'cloud' => [
                'platforms' => array_merge(
                    array_slice($this->keywordService->getKeywordsByCategory('cloud', 'aws_services'), 0, 10),
                    array_slice($this->keywordService->getKeywordsByCategory('cloud', 'azure_services'), 0, 8),
                    array_slice($this->keywordService->getKeywordsByCategory('cloud', 'gcp_services'), 0, 8)
                ),
                'security' => array_slice($this->keywordService->getKeywordsByCategory('cloud', 'security'), 0, 10),
            ],
        ];
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $keywordReference = $this->buildKeywordReference();

        $instructions = <<<'PROMPT'
You are a professional CV reviewer specializing in DevOps, DevSecOps, SRE, and Cloud Engineer roles.
Your analysis must reflect recruiter-level, hiring-manager-level, and ATS-level standards for infrastructure/platform candidates.

Return structured output only and follow this rubric exactly.

SCORING RUBRIC (0-100 each):
- ATS Score: formatting safety, standard sections, keyword discoverability, parseability.
- Format Score: clarity, scannability, section ordering, date consistency, concise bullet structure, ATS-safe formatting.
- About Me Score: strength of value proposition, specificity, professional tone, seniority alignment, avoidance of clichés.
- Impact Score: quantified outcomes, ownership, scope, scale, business/reliability/security impact.
- Role Alignment Score: fit for the target role and seniority based on demonstrated work, not keyword stuffing.
- Security Score: DevSecOps/security maturity, secure SDLC, IAM, secrets handling, vulnerability management, container/cloud security.
- Reliability Score: SRE maturity, SLI/SLO awareness, incident response, observability, MTTR/availability, resilience engineering.
- Cloud Score: cloud architecture depth, provider-native services, networking, HA/DR, scalability, cost-awareness.
- Job Match Score: explicit fit against the provided job description. If no job description exists, set to 0.

TARGET ROLES TO EVALUATE:
- DevOps
- DevSecOps
- SRE
- Cloud Engineer

ANALYSIS RULES:
1. Do not reward keyword stuffing without evidence.
2. Prefer achievements with scale, metrics, ownership, and outcomes.
3. Penalize vague claims like “experienced with Kubernetes” if no evidence, scope, or impact is shown.
4. Look for missing metrics such as uptime, MTTR, deployment frequency, cost savings, incident reduction, latency, cluster size, workload scale, compliance outcomes, or security remediation outcomes.
5. Assess whether experience bullets prove the claimed seniority.
6. If a skill appears but the proof is weak, include it in weak_evidence.
7. Suggestions must be practical, specific, and high-impact.
8. experience_review must review the most important experience sections individually.

OUTPUT EXPECTATIONS:
- keywords.found: relevant matched keywords strongly supported by the CV.
- keywords.missing: important missing keywords for the likely target roles.
- keywords.weak_evidence: keywords/claims mentioned with weak or no proof.
- strengths: 3-6 concise strengths.
- critical_gaps: major blockers preventing a strong professional profile.
- missing_metrics: metrics or evidence absent but expected.
- rewrite_priorities: highest-value rewrite tasks.
- experience_review: array of objects with section, strength, gap, rewrite_hint.
- industry_match: integer scores for devops, devsecops, sre, cloud_engineer.
- target_seniority: infer likely level such as Junior, Mid, Mid-Senior, Senior, Staff, Lead, or Not Clear.
- summary: executive-level evaluation of professional readiness.
PROMPT;

        $instructions .= "\n\n".$keywordReference;

        if ($this->targetRole) {
            $instructions .= "\nTARGET ROLE FOR EVALUATION: ".$this->targetRole.'. Evaluate the CV specifically against the requirements, core concepts, tooling, and standards of a '.$this->targetRole.'. Adjust all scoring, keyword checks, suggestions, and gaps accordingly.';
        }

        if ($this->jobDescription) {
            $instructions .= "\nJOB DESCRIPTION PROVIDED: infer target role and seniority, then compute Job Match Score against the JD.";
        } else {
            $instructions .= "\nNO JOB DESCRIPTION PROVIDED: set job_match_score to 0 and infer the most likely target role from the CV itself.";
        }

        $instructions .= "\n\nCV Content:\n".$this->cvContent;

        if ($this->jobDescription) {
            $instructions .= "\n\nJob Description:\n".$this->jobDescription;
        }

        return $instructions;
    }

    /**
     * Build keyword reference section for AI instructions
     */
    protected function buildKeywordReference(): string
    {
        $reference = "REFERENCE KEYWORDS TO CHECK:\n\n";

        $reference .= "DevOps Core:\n- ".implode(', ', $this->referenceKeywords['devops']['core'])."\n\n";
        $reference .= "DevOps Tooling:\n- ".implode(', ', $this->referenceKeywords['devops']['tools'])."\n\n";

        $reference .= "DevSecOps Core:\n- ".implode(', ', $this->referenceKeywords['devsecops']['core'])."\n\n";
        $reference .= "DevSecOps Security Tooling:\n- ".implode(', ', $this->referenceKeywords['devsecops']['security_tooling'])."\n\n";

        $reference .= "SRE Core:\n- ".implode(', ', $this->referenceKeywords['sre']['core'])."\n\n";
        $reference .= "SRE Reliability:\n- ".implode(', ', $this->referenceKeywords['sre']['reliability'])."\n\n";
        $reference .= "SRE Observability:\n- ".implode(', ', $this->referenceKeywords['sre']['observability'])."\n\n";

        $reference .= "Cloud Engineer Platforms:\n- ".implode(', ', $this->referenceKeywords['cloud']['platforms'])."\n\n";
        $reference .= "Cloud Security:\n- ".implode(', ', $this->referenceKeywords['cloud']['security'])."\n\n";

        $reference .= "NOTES:\n";
        $reference .= "- Treat aliases as equivalent when appropriate (e.g. K8s = Kubernetes).\n";
        $reference .= "- Prioritize proof, scale, and outcomes over mere mention.\n";
        $reference .= "- Do not inflate scores without evidence.\n";

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
            'impact_score' => $schema->integer()->min(0)->max(100)->required(),
            'role_alignment_score' => $schema->integer()->min(0)->max(100)->required(),
            'security_score' => $schema->integer()->min(0)->max(100)->required(),
            'reliability_score' => $schema->integer()->min(0)->max(100)->required(),
            'cloud_score' => $schema->integer()->min(0)->max(100)->required(),
            'keywords' => $schema->object([
                'found' => $schema->array()->items($schema->string())->required(),
                'missing' => $schema->array()->items($schema->string())->required(),
                'weak_evidence' => $schema->array()->items($schema->string())->required(),
            ])->required(),
            'suggestions' => $schema->array()->items($schema->string())->required(),
            'strengths' => $schema->array()->items($schema->string())->required(),
            'critical_gaps' => $schema->array()->items($schema->string())->required(),
            'missing_metrics' => $schema->array()->items($schema->string())->required(),
            'rewrite_priorities' => $schema->array()->items($schema->string())->required(),
            'experience_review' => $schema->array()->items(
                $schema->object([
                    'section' => $schema->string()->required(),
                    'strength' => $schema->string()->required(),
                    'gap' => $schema->string()->required(),
                    'rewrite_hint' => $schema->string()->required(),
                ])
            )->required(),
            'industry_match' => $schema->object([
                'devops' => $schema->integer()->min(0)->max(100)->required(),
                'devsecops' => $schema->integer()->min(0)->max(100)->required(),
                'sre' => $schema->integer()->min(0)->max(100)->required(),
                'cloud_engineer' => $schema->integer()->min(0)->max(100)->required(),
            ])->required(),
            'summary' => $schema->string()->required(),
            'job_match_score' => $schema->integer()->min(0)->max(100)->required(),
            'job_title_targeted' => $schema->string()->required(),
            'target_seniority' => $schema->string()->required(),
        ];
    }
}
