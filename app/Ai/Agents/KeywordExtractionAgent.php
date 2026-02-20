<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class KeywordExtractionAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $text
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a keyword extraction expert for ATS matching. Extract and categorize key keywords from this text.

Text:
'.$this->text;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'technical_keywords' => $schema->array()->items($schema->string())->required(),
            'soft_skills' => $schema->array()->items($schema->string())->required(),
            'industry_terms' => $schema->array()->items($schema->string())->required(),
            'tools_and_technologies' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
