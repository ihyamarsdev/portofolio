<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ATSFormatValidationAgent implements Agent, HasStructuredOutput
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
        return 'You are an ATS format validation expert. Validate this CV for ATS compatibility, checking format, structure, and readability.

    CV Content:
' . $this->cvContent;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'format_score' => $schema->integer()->min(0)->max(100)->required(),
            'issues' => $schema->array()->items($schema->string())->required(),
            'strengths' => $schema->array()->items($schema->string())->required(),
            'sections_present' => $schema->array()->items($schema->string())->required(),
            'format_checklist' => $schema->object([
                'single_column' => $schema->boolean(),
                'standard_fonts' => $schema->boolean(),
                'clear_headings' => $schema->boolean(),
                'no_tables' => $schema->boolean(),
            ])->required(),
        ];
    }
}
