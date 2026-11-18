<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * Parses a raw job description into a structured, objective list of requirements.
 *
 * The output of this agent is consumed deterministically by JobMatchService,
 * so the agent must only classify information that is explicitly present in
 * the job description — it must never invent requirements or scores.
 */
class JobRequirementExtractionAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $jobDescription,
        public string $targetRole = '',
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $instructions = <<<'PROMPT'
You are a technical recruiter assistant that parses job descriptions into structured hiring requirements for DevOps, DevSecOps, SRE, and Cloud Engineer roles.

Your task is EXTRACTION only, not evaluation. Follow these rules strictly:

1. Extract each distinct skill, technology, certification, or experience requirement stated in the job description.
2. Classify each requirement:
   - "must_have": listed under required qualifications, responsibilities, or mandatory skills.
   - "nice_to_have": listed under preferred/nice-to-have/bonus/plus qualifications.
3. Assign a weight from 1-5 based on emphasis and repetition in the job description:
   - 5: core daily responsibility or explicitly mandatory.
   - 4: strongly emphasized requirement.
   - 3: standard requirement.
   - 2: minor requirement or mentioned once without emphasis.
   - 1: incidental mention.
4. Keep each requirement concise (one skill or one competency per item, e.g. "Kubernetes", "5+ years AWS experience", "Terraform").
5. Do NOT invent requirements that are not in the job description. If a requirement is only implied, omit it — extraction is literal, never creative.
6. Do NOT evaluate the candidate, do NOT produce any score, and do NOT assume the candidate has any of these skills. You only parse the job description.
7. seniority_signals: extract objective signals such as required years of experience, team leadership scope, on-call ownership, or compliance exposure.
8. inferred_title: the job title from the description (or the closest match). inferred_seniority: Junior, Mid, Mid-Senior, Senior, Staff, Lead, or Not Clear based on the signals.
9. EVIDENCE GROUNDING: this output is consumed by a deterministic matcher that will verify each requirement against the candidate's real database records (experience_skill / project_skill links) and CV text. Never manufacture requirements to "help" the match — a fabricated requirement can only create a false gap. Extract only what the job description literally states.

PROMPT;

        if ($this->targetRole !== '') {
            $instructions .= "\n\nTARGET ROLE CONTEXT: ".$this->targetRole.'. Use it to disambiguate terminology, but still extract only what the job description states.';
        }

        $instructions .= "\n\nJob Description:\n".$this->jobDescription;

        return $instructions;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'requirements' => $schema->array()->items(
                $schema->object([
                    'requirement' => $schema->string()->required(),
                    'type' => $schema->string()->required(),
                    'weight' => $schema->integer()->min(1)->max(5)->required(),
                ])
            )->required(),
            'seniority_signals' => $schema->array()->items($schema->string())->required(),
            'inferred_title' => $schema->string()->required(),
            'inferred_seniority' => $schema->string()->required(),
        ];
    }
}
