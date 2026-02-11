<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ActionVerbAnalysisAgent implements Agent, HasStructuredOutput
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
        return 'You are an action verb analysis expert. Analyze the action verbs used in this CV and categorize them as strong or weak, with suggestions for improvement.

CV Content:
' . $this->cvContent;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'strong_verbs' => $schema->array()->items($schema->string())->required(),
            'weak_verbs' => $schema->array()->items($schema->string())->required(),
            'suggestions' => $schema->array()->items($schema->string())->required(),
            'action_verb_score' => $schema->integer()->min(0)->max(100)->required(),
        ];
    }
}
