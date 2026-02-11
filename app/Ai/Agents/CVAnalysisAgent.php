<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class CVAnalysisAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $cvContent
    ) {
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an ATS (Applicant Tracking System) expert. Analyze this CV/Resume for ATS compatibility.

CV Content:
' . $this->cvContent;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ats_score' => $schema->integer()->min(0)->max(100)->required(),
            'keywords' => $schema->object([
                'found' => $schema->array()->items($schema->string()),
                'missing' => $schema->array()->items($schema->string()),
            ])->required(),
            'format_score' => $schema->integer()->min(0)->max(100)->required(),
            'suggestions' => $schema->array()->items($schema->string())->required(),
            'industry_match' => $schema->object([
                'devops' => $schema->integer()->min(0)->max(100),
                'cloud' => $schema->integer()->min(0)->max(100),
                'sre' => $schema->integer()->min(0)->max(100),
            ])->required(),
            'summary' => $schema->string()->required(),
        ];
    }
}
