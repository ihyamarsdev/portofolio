<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class SkillGapSuggestionsAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public array $missingSkills
    ) {
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a career development expert. Provide suggestions for how to gain experience or highlight these missing skills in a CV.

Missing Skills:
' . implode(', ', $this->missingSkills);
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'certifications_to_get' => $schema->array()->items($schema->string())->required(),
            'projects_to_showcase' => $schema->array()->items($schema->string())->required(),
            'skills_to_emphasize' => $schema->array()->items($schema->string())->required(),
            'training_resources' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
