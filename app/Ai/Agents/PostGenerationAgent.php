<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class PostGenerationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $title,
        public string $generateType, // 'content', 'seo', 'both'
        public ?string $tone = null,
        public ?string $keywords = null,
        public ?string $currentContent = null
    ) {}

    public function instructions(): Stringable|string
    {
        $toneInstruction = $this->tone ? "Write in the following tone/style: {$this->tone}." : "Write in a professional, engaging and clean SEO copywriter tone.";
        $keywordsInstruction = $this->keywords ? "Make sure to integrate the following target keywords naturally: {$this->keywords}." : "";

        $prompt = "You are a world-class SEO content writer and tech blogger. Based on the blog post title '{$this->title}', you must generate high-quality outputs.
        {$toneInstruction}
        {$keywordsInstruction}
        
        Depending on the requested generation type '{$this->generateType}', you MUST return ONLY a valid JSON payload matching the EXACT required structure. Do not wrap in markdown or add conversational intro/outro text.
        ";

        if ($this->generateType === 'content') {
            $prompt .= '
            Required JSON structure:
            {
                "content": "<comprehensive, highly detailed, beautifully formatted HTML article. Use semantic HTML tags like h2, h3, p, strong, em, pre, code, blockquote, ul, ol, li where appropriate. Write at least 600-800 words of deeply informative technical content.>"
            }';
        } elseif ($this->generateType === 'seo') {
            $prompt .= '
            Required JSON structure:
            {
                "excerpt": "<concise summary of the post, max 160 characters>",
                "meta_title": "<SEO-optimized meta title, max 60 characters>",
                "meta_description": "<compelling search engine description, max 160 characters>"
            }
            
            Based on this current content:
            ' . strip_tags($this->currentContent ?? '');
        } else { // both
            $prompt .= '
            Required JSON structure:
            {
                "content": "<comprehensive, highly detailed, beautifully formatted HTML article. Use semantic HTML tags like h2, h3, p, strong, em, pre, code, blockquote, ul, ol, li where appropriate. Write at least 600-800 words of deeply informative technical content.>",
                "excerpt": "<concise summary of the post, max 160 characters>",
                "meta_title": "<SEO-optimized meta title, max 60 characters>",
                "meta_description": "<compelling search engine description, max 160 characters>"
            }';
        }

        return $prompt;
    }

    public function schema(JsonSchema $schema): array
    {
        $fields = [];
        if ($this->generateType === 'content' || $this->generateType === 'both') {
            $fields['content'] = $schema->string()->required();
        }
        if ($this->generateType === 'seo' || $this->generateType === 'both') {
            $fields['excerpt'] = $schema->string()->required();
            $fields['meta_title'] = $schema->string()->required();
            $fields['meta_description'] = $schema->string()->required();
        }
        return $fields;
    }
}
