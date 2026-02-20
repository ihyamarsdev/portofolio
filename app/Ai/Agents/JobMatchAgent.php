<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class JobMatchAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $cvContent,
        public string $jobDescription
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a job matching expert. Compare this CV against the job description and provide a detailed match score and analysis.

CV Content:
'.$this->cvContent.'

Job Description:
'.$this->jobDescription;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'job_match_score' => $schema->integer()->min(0)->max(100)->required(),
            'job_title_targeted' => $schema->string()->required(),
            'keywords_found' => $schema->array()->items($schema->string())->required(),
            'keywords_missing' => $schema->array()->items($schema->string())->required(),
            'suggestions' => $schema->array()->items($schema->string())->required(),
            'summary' => $schema->string()->required(),
        ];
    }
}
