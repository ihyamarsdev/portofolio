<?php

namespace App\Ai\Gateway;

use Generator;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Providers\TextProvider;
use Laravel\Ai\Exceptions\AiException;
use Laravel\Ai\Gateway\OpenAi\OpenAiGateway;
use Laravel\Ai\Gateway\TextGenerationOptions;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Providers\Provider;
use Laravel\Ai\Responses\Data\Meta;
use Laravel\Ai\Responses\Data\Usage;
use Laravel\Ai\Responses\StructuredTextResponse;
use Laravel\Ai\Responses\TextResponse;

class CvOpenAiCompatibleGateway extends OpenAiGateway
{
    public function generateText(
        TextProvider $provider,
        string $model,
        ?string $instructions,
        array $messages = [],
        array $tools = [],
        ?array $schema = null,
        ?TextGenerationOptions $options = null,
        ?int $timeout = null,
    ): TextResponse {
        if (filled($tools)) {
            throw new AiException('CV AI provider does not support tools.');
        }

        if (! $provider instanceof Provider) {
            throw new AiException('CV AI provider is misconfigured.');
        }

        $response = Http::baseUrl($this->chatCompletionsBaseUrl($provider))
            ->withToken($provider->providerCredentials()['key'])
            ->timeout($timeout ?? 60)
            ->throw()
            ->post('chat/completions', $this->buildChatCompletionsBody($model, $instructions, $messages, filled($schema), $options));

        $data = $this->parseJsonResponse($response->body());

        if (! is_array($data)) {
            throw new AiException('CV AI provider returned a non-JSON response.');
        }

        if (isset($data['error'])) {
            throw new AiException(sprintf(
                'CV AI provider error: [%s] %s',
                $data['error']['type'] ?? $data['error']['code'] ?? 'unknown',
                $data['error']['message'] ?? 'Unknown provider error.',
            ));
        }

        $content = $data['choices'][0]['message']['content'] ?? null;

        if (! is_string($content) || $content === '') {
            throw new AiException('CV AI provider returned an empty response.');
        }

        $usage = new Usage(
            promptTokens: (int) ($data['usage']['prompt_tokens'] ?? 0),
            completionTokens: (int) ($data['usage']['completion_tokens'] ?? 0),
        );

        $meta = new Meta($provider->name(), $data['model'] ?? $model);

        if (filled($schema)) {
            $structured = json_decode($content, true);

            if (! is_array($structured)) {
                throw new AiException('CV AI provider returned invalid structured JSON.');
            }

            return new StructuredTextResponse($structured, $content, $usage, $meta);
        }

        return new TextResponse($content, $usage, $meta);
    }

    public function streamText(
        string $invocationId,
        TextProvider $provider,
        string $model,
        ?string $instructions,
        array $messages = [],
        array $tools = [],
        ?array $schema = null,
        ?TextGenerationOptions $options = null,
        ?int $timeout = null,
    ): Generator {
        throw new AiException('CV AI provider does not support streaming.');
    }

    protected function parseJsonResponse(string $body): ?array
    {
        $data = json_decode($body, true);

        if (is_array($data)) {
            return $data;
        }

        $jsonEnd = strpos($body, "\ndata:");

        if ($jsonEnd === false) {
            return null;
        }

        $data = json_decode(substr($body, 0, $jsonEnd), true);

        return is_array($data) ? $data : null;
    }

    protected function buildChatCompletionsBody(
        string $model,
        ?string $instructions,
        array $messages,
        bool $structured,
        ?TextGenerationOptions $options,
    ): array {
        $body = [
            'model' => $model,
            'messages' => $this->mapMessages($instructions, $messages),
            'stream' => false,
        ];

        if ($structured) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        if (! is_null($options?->temperature)) {
            $body['temperature'] = $options->temperature;
        }

        if (! is_null($options?->topP)) {
            $body['top_p'] = $options->topP;
        }

        return $body;
    }

    protected function mapMessages(?string $instructions, array $messages): array
    {
        $mapped = [];

        if (filled($instructions)) {
            $mapped[] = [
                'role' => 'system',
                'content' => $instructions,
            ];
        }

        foreach ($messages as $message) {
            if (! $message instanceof Message) {
                continue;
            }

            $mapped[] = [
                'role' => $message->role->value === 'assistant' ? 'assistant' : 'user',
                'content' => $message->content ?? '',
            ];
        }

        return $mapped;
    }

    protected function chatCompletionsBaseUrl(Provider $provider): string
    {
        return rtrim($provider->additionalConfiguration()['url'] ?? 'https://api.openai.com/v1', '/');
    }
}
