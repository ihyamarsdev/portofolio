<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class JobDescriptionParserAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $jobDescription
    ) {
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a job description parser expert. Parse and extract key information from this job description in a structured format.

Job Description:
' . $this->jobDescription;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->required(),
            'required_skills' => $schema->array()->items($schema->string())->required(),
            'preferred_skills' => $schema->array()->items($schema->string())->required(),
            'experience_years' => $schema->string()->required(),
            'education_level' => $schema->string()->required(),
            'key_responsibilities' => $schema->array()->items($schema->string())->required(),
            'keywords' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
