<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class PostSeoAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $title,
        public string $content,
        public ?string $tone = null,
        public ?string $keywords = null
    ) {}

    public function instructions(): Stringable|string
    {
        $toneInstruction = $this->tone ? "Write in the following tone/style: {$this->tone}." : "Write in a professional, engaging and clean SEO copywriter tone.";
        $keywordsInstruction = $this->keywords ? "Make sure to integrate the following target keywords naturally in the meta title and description: {$this->keywords}." : "";

        return 'You are an SEO expert and content copywriter. Based on the blog post title and content provided, you must generate a structured SEO payload.

        You must return ONLY valid JSON with this EXACT structure:
        {
            "excerpt": "<concise summary of the post, max 160 characters>",
            "meta_title": "<SEO-optimized meta title, max 60 characters>",
            "meta_description": "<compelling search engine description, max 160 characters>"
        }

        Guidelines:
        - Excerpt: Plain text summary, engaging, captures the essence of the post. ' . $toneInstruction . '
        - Meta Title: Include primary keyword, be descriptive, avoid keyword stuffing.
        - Meta Description: Include a call-to-action, mention key benefits, use active voice. ' . $keywordsInstruction . '

        Post Title: '.$this->title.'

        Post Content:
        '.strip_tags($this->content);
    }

    /**
     * Get the agent\'s structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'excerpt' => $schema->string()->required(),
            'meta_title' => $schema->string()->required(),
            'meta_description' => $schema->string()->required(),
        ];
    }
}
