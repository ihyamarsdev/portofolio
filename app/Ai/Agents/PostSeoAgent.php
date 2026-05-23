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
        public string $content
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are an SEO expert and content writer. Based on the blog post title and content provided, generate:
1. A concise and engaging excerpt (max 160 characters) that summarizes the post
2. An SEO-optimized meta title (max 60 characters) that includes the main keyword
3. A compelling meta description (max 160 characters) that encourages clicks from search results

Guidelines:
- Excerpt: Plain text summary, engaging, captures the essence of the post
- Meta Title: Include primary keyword, be descriptive, avoid keyword stuffing
- Meta Description: Include a call-to-action, mention key benefits, use active voice

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
