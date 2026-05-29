<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class ProjectGenerationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $title,
        public string $generateType, // 'description', 'diagram', 'both'
        public ?string $techStack = null,
        public ?string $currentDescription = null,
        public ?string $diagramStyle = 'flowchart TD'
    ) {}

    public function instructions(): Stringable|string
    {
        $techStackInstruction = $this->techStack ? "Focus on the following technologies/skills in the project: {$this->techStack}." : "";

        $prompt = "You are an elite software architect and technical writer. Based on the project title '{$this->title}', you must generate high-quality project details.
        {$techStackInstruction}
        
        Depending on the requested generation type '{$this->generateType}', you MUST return ONLY a valid JSON payload matching the EXACT required structure. Do not wrap in markdown or add conversational intro/outro text.
        ";

        if ($this->generateType === 'description') {
            $prompt .= '
            Required JSON structure:
            {
                "description": "<comprehensive, highly detailed technical description of the project. Explain the architecture, components, problem solved, and key features. Use clean markdown formatting. Write at least 300-400 words.>"
            }';
        } elseif ($this->generateType === 'diagram') {
            $prompt .= '
            Required JSON structure:
            {
                "diagram": "<Mermaid flowchart syntax using ' . ($this->diagramStyle ?? 'flowchart TD') . ' style. Show main services, database, APIs, and data flow.>",
                "explanation": "<brief explanation of the diagram architecture>"
            }
            
            Based on this current project description:
            ' . ($this->currentDescription ?? '');
        } else { // both
            $prompt .= '
            Required JSON structure:
            {
                "description": "<comprehensive, highly detailed technical description of the project. Explain the architecture, components, problem solved, and key features. Use clean markdown formatting. Write at least 300-400 words.>",
                "diagram": "<Mermaid flowchart syntax using ' . ($this->diagramStyle ?? 'flowchart TD') . ' style. Show main services, database, APIs, and data flow.>",
                "explanation": "<brief explanation of the diagram architecture>"
            }';
        }

        return $prompt;
    }

    public function schema(JsonSchema $schema): array
    {
        $fields = [];
        if ($this->generateType === 'description' || $this->generateType === 'both') {
            $fields['description'] = $schema->string()->required();
        }
        if ($this->generateType === 'diagram' || $this->generateType === 'both') {
            $fields['diagram'] = $schema->string()->required();
            $fields['explanation'] = $schema->string()->required();
        }
        return $fields;
    }
}
