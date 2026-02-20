<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class SkillExtractionAgent implements Agent, HasStructuredOutput
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
        return 'You are a skills extraction expert. Extract and categorize technical skills from this text.

Text:
'.$this->text;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'technical_skills' => $schema->array()->items($schema->string())->required(),
            'programming_languages' => $schema->array()->items($schema->string())->required(),
            'frameworks' => $schema->array()->items($schema->string())->required(),
            'cloud_platforms' => $schema->array()->items($schema->string())->required(),
            'devops_tools' => $schema->array()->items($schema->string())->required(),
            'databases' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
