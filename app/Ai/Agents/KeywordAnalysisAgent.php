<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class KeywordAnalysisAgent implements Agent, HasStructuredOutput
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
        return 'You are a keyword analysis expert for ATS optimization. Analyze the keywords in this CV and provide a detailed breakdown.

CV Content:
' . $this->cvContent;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'found' => $schema->array()->items($schema->string())->required(),
            'missing' => $schema->array()->items($schema->string())->required(),
            'density_analysis' => $schema->object([
                'skill_keywords' => $schema->integer()->min(0),
                'action_verbs' => $schema->integer()->min(0),
                'technical_terms' => $schema->integer()->min(0),
            ])->required(),
        ];
    }
}
