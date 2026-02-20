<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ImprovementSuggestionsAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public array $analysisData
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a CV improvement expert. Based on the following CV analysis data, provide specific, actionable improvements categorized by priority.

Analysis Data:
'.json_encode($this->analysisData, JSON_PRETTY_PRINT);
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'critical_improvements' => $schema->array()->items($schema->string())->required(),
            'recommended_improvements' => $schema->array()->items($schema->string())->required(),
            'optional_improvements' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
