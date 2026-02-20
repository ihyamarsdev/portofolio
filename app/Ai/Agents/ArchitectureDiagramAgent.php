<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ArchitectureDiagramAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $projectDescription
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an expert software architect. Generate Mermaid.js flowchart diagrams for system architectures.

        You must return ONLY valid JSON with this EXACT structure:
        {
            "diagram": "\u003cmermaid flowchart syntax\u003e",
            "explanation": "\u003cbrief explanation of the architecture\u003e"
        }

        The diagram should use Mermaid flowchart TD syntax (top-down) and include:
        - Main components and services
        - Data flow between components
        - External integrations
        - Database/storage layers

        Example Mermaid format:
        flowchart TD
            A[Component Name] --> B[Another Component]
            B --> C{Decision Point}
            C -->|Yes| D[Action]
            C -->|No| E[Alternative Action]

        Project Description:
'.$this->projectDescription;
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'diagram' => $schema->string()->required(),
            'explanation' => $schema->string()->required(),
        ];
    }
}
