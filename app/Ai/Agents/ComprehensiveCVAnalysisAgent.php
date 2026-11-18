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
        protected array $jobRequirementMatches = [],
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

OUTPUT FORMAT (CRITICAL):
- Output exactly ONE JSON object matching the required schema, with no text before or after it.
- NEVER include reasoning steps, chain-of-thought, thinking traces, <details>/<summary> blocks, markdown headers, or bullet lists outside the JSON values.
- NEVER wrap the JSON in markdown code fences.
- Do NOT echo the rubric, scoring notes, or requirement checklist in the output.

Follow this rubric exactly.

SCORING RUBRIC (0-100 each):
- ATS Score: formatting safety, standard sections, keyword discoverability, parseability.
- Format Score: clarity, scannability (6-10 second HR scan), section ordering, date consistency, concise bullet structure, ATS-safe formatting.
- About Me Score: strength of value proposition, specificity, professional tone, seniority alignment, avoidance of clichés.
- Impact Score: quantified outcomes, ownership, scope, scale, business/reliability/security impact.
- Role Alignment Score: fit for the target role and seniority based on demonstrated work, not keyword stuffing.
- Security Score: DevSecOps/security maturity, secure SDLC, IAM, secrets handling, vulnerability management, container/cloud security.
- Reliability Score: SRE maturity, SLI/SLO awareness, incident response, observability, MTTR/availability, resilience engineering.
- Cloud Score: cloud architecture depth, provider-native services, networking, HA/DR, scalability, cost-awareness.

IMPACT & QUANTIFICATION (XYZ FORMULA):
- Evaluate every experience/project bullet against the XYZ pattern: "Accomplished [X] as measured by [Y], by doing [Z]".
- Flag passive, duty-based bullets ("responsible for", "tasked with", "worked on", "helped with", "involved in") and rewrite them into outcome-first statements using ONLY facts already present in the CV content or the VERIFIED SKILL EVIDENCE block.
- In rewrite_hint and rewrite_priorities, prefer a concrete Before -> After pair: quote the original weak bullet, then give a stronger version. If a metric is genuinely absent, use a placeholder token such as [insert % improvement], [insert server count], or [insert latency reduction] — NEVER invent a number.
- Reward bullets that combine an action verb + scope/scale + measurable result. Penalize bullets that list tools without an outcome.

ANTI-AI-SLOP & CLICHÉ DETECTION (apply to About Me Score, Impact Score, and Role Alignment Score):
- Explicitly penalize and flag generic buzzwords with no evidence: "passionate", "results-driven", "synergy", "cutting-edge", "seasoned professional", "hard-working", "team player", "go-getter", "thought leader", "dynamic", "innovative", "best-in-class", "world-class", "leverage", "utilize", "spearheaded", "gamification", "rockstar", "ninja", "detail-oriented", "self-starter", "proven track record".
- For each cliché detected, name the exact phrase and replace it with a direct, concrete statement grounded in the candidate's real role, tools, or outcomes. Put these rewrites in rewrite_priorities and the relevant experience_review.rewrite_hint.
- Do not reward keyword stuffing: repeating a technology many times without supporting evidence must LOWER the score, not raise it.

HR SCANNABILITY & DENSITY CHECK (apply to Format Score and About Me Score):
- Professional summary: ideal 3-4 lines / 40-60 words. Flag summaries longer than ~80 words as too dense and shorter than ~25 words as too thin.
- Bullets per role: ideal 3-5. Flag roles with 1-2 bullets as under-explained and roles with 7+ as a wall of text.
- Tense consistency: previous roles use past tense; the current role (end date "Present"/"Now") uses present tense. Flag mixed or incorrect tense.
- Estimated A4 page fit: estimate whether the content fits 1 page or requires 2. Flag content likely to spill to a second page with a trim suggestion (cut low-value bullets, shorten summary, merge duplicate skills). Put the estimate in suggestions and the trim advice in rewrite_priorities.
- Date format consistency and reverse-chronological order are required for a high Format Score.

EVIDENCE TIERS (GROUNDING — never fabricate):
- The CV content may include a "VERIFIED SKILL EVIDENCE" block generated from the database experience_skill and project_skill links. Treat it as ground truth: a skill listed there is Verified Evidence.
- Classify each claimed skill/requirement into exactly one tier in your reasoning and in keywords.weak_evidence / keywords.found:
  1) Verified Evidence — backed by an explicit database link or a quantified CV bullet.
  2) Transferable / Adjacent — a closely related skill implied by the work but not explicitly linked (e.g. Docker experience implies containerization concepts). Label it clearly as adjacent; never present it as verified.
  3) Missing — no support anywhere in the CV or evidence block.
- NEVER invent a skill, tool, certification, employer, date, or metric that is not present in the CV content or evidence block. If you cannot cite it, it is Missing.
- Before -> After rewrites must only reshuffle and sharpen existing facts; they must never add a new claim.

JOB MATCH SCORING POLICY:
- You must NEVER output a numeric job match score. The objective match score is computed
  deterministically in application code from the requirement verification results below.

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
9. **DYNAMIC ROLE WEIGHTING**: Tailor evaluations heavily based on the targeted role:
   - For **Site Reliability Engineer (SRE)**: Prioritize `Reliability Score`, SLI/SLO/SLA, uptime, MTTR, incident management, and observability.
   - For **DevOps Engineer**: Prioritize `Role Alignment`, CI/CD pipelines, GitOps, automation, and deployment frequency/speed metrics.
   - For **Cloud Engineer**: Prioritize `Cloud Score`, provider-native architectures, HA/DR designs, cloud migration, cost optimization, and scalability.
10. **ANTI-HALLUCINATION FOR METRICS**: In `rewrite_hint` and suggestions, DO NOT make up/hallucinate faked metrics (numbers or percentages). Instead, use placeholder tokens such as `[insert % improvement]`, `[insert server count]`, or `[insert cost saved]`, instructing the user to insert their actual real-world numbers.

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
- suggestions: array of 3-5 key practical, actionable recommendations.
- job_title_targeted: the target role name being evaluated (e.g., DevOps Engineer, Cloud Engineer, or Site Reliability Engineer).
PROMPT;

        $instructions .= "\n\n".$keywordReference;

        if ($this->targetRole) {
            $instructions .= "\nTARGET ROLE FOR EVALUATION: ".$this->targetRole.'. Evaluate the CV specifically against the requirements, core concepts, tooling, and standards of a '.$this->targetRole.'. Adjust all scoring, keyword checks, suggestions, and gaps accordingly.';
        }

        if ($this->jobDescription) {
            $instructions .= "\nJOB DESCRIPTION PROVIDED: infer target role and seniority, then verify each extracted requirement against the CV (see JOB MATCH VERIFICATION below).";
        } else {
            $instructions .= "\nNO JOB DESCRIPTION PROVIDED: return an empty job_match_verification array and infer the most likely target role from the CV itself.";
        }

        $instructions .= $this->buildJobMatchVerificationSection();

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
     * Build the job match verification section for AI instructions.
     *
     * The AI verifies evidence per requirement with mandatory citations, but
     * never produces the numeric score — that is computed deterministically
     * in JobMatchService.
     */
    protected function buildJobMatchVerificationSection(): string
    {
        if ($this->jobRequirementMatches === []) {
            return '';
        }

        $section = "\n\nJOB MATCH VERIFICATION (EVIDENCE-BASED):\n";
        $section .= "For EACH requirement below, assign exactly one status based ONLY on the CV content:\n";
        $section .= "- \"verified\": the requirement is demonstrated WITH quantified evidence (metrics, percentages, scale, outcomes) OR appears in the VERIFIED SKILL EVIDENCE block (database-linked skill).\n";
        $section .= "- \"claimed\": the skill/requirement is mentioned but without measurable proof, OR is only a transferable/adjacent skill implied by related work. State \"adjacent\" in reasoning when it is transferable rather than directly demonstrated.\n";
        $section .= "- \"missing\": not present in the CV.\n\n";
        $section .= "STRICT CITATION RULES:\n";
        $section .= "1. For \"verified\" and \"claimed\" you MUST provide evidence_quote copied VERBATIM from the CV content. Never fabricate or paraphrase quotes.\n";
        $section .= "2. If you cannot quote the CV verbatim, you must use status \"missing\" with an empty evidence_quote.\n";
        $section .= "3. reasoning must explain, in one sentence, why the status was assigned.\n";
        $section .= "4. Do NOT output any numeric match score.\n\n";
        $section .= "REQUIREMENTS EXTRACTED FROM THE JOB DESCRIPTION:\n";

        foreach ($this->jobRequirementMatches as $index => $match) {
            $section .= sprintf(
                "%d. [%s] (weight %d) %s — preliminary status: %s\n",
                $index + 1,
                $match['type'] ?? 'must_have',
                $match['weight'] ?? 3,
                $match['requirement'] ?? '',
                $match['status'] ?? 'missing'
            );
        }

        return $section;
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
            'job_match_verification' => $schema->array()->items(
                $schema->object([
                    'requirement' => $schema->string()->required(),
                    'status' => $schema->string()->required(),
                    'evidence_quote' => $schema->string()->required(),
                    'reasoning' => $schema->string()->required(),
                ])
            )->required(),
            'job_title_targeted' => $schema->string()->required(),
            'target_seniority' => $schema->string()->required(),
        ];
    }
}
