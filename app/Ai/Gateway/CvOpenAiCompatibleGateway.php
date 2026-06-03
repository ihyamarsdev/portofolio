<?php

namespace App\Ai\Gateway;

use App\Support\AiNotification;
use Generator;
use Illuminate\Http\Client\ConnectionException;
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

        try {
            $response = Http::baseUrl($this->chatCompletionsBaseUrl($provider))
                ->withToken($provider->providerCredentials()['key'])
                ->timeout($timeout ?? 60)
                ->post('chat/completions', $this->buildChatCompletionsBody($model, $instructions, $messages, filled($schema), $options));
        } catch (ConnectionException $exception) {
            AiNotification::sendFailure(
                'AI provider connection failed. Please check the provider/network and try again.',
                'AI Provider'
            );

            return $this->failedResponse($provider, $model, filled($schema));
        }

        if ($response->failed()) {
            $message = $response->json('error.message') ?? $response->body();

            AiNotification::sendFailure(sprintf(
                'AI provider unavailable. Status: %s. %s',
                $response->status(),
                $message,
            ), 'AI Provider');

            return $this->failedResponse($provider, $model, filled($schema));
        }

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
            $cleanContent = trim($content);
            if (str_starts_with($cleanContent, '```')) {
                $cleanContent = preg_replace('/^```(?:json)?\n?/i', '', $cleanContent);
                $cleanContent = preg_replace('/```$/', '', $cleanContent);
                $cleanContent = trim($cleanContent);
            }

            $structured = json_decode($cleanContent, true);

            // Fallback 1: Extract JSON between the first '{' and last '}'
            if (! is_array($structured)) {
                $firstBrace = strpos($cleanContent, '{');
                $lastBrace = strrpos($cleanContent, '}');
                if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
                    $jsonCandidate = substr($cleanContent, $firstBrace, $lastBrace - $firstBrace + 1);
                    $structured = json_decode($jsonCandidate, true);
                }
            }

            // Fallback 2: Regex key-value segment extraction for plaintext answers
            if (! is_array($structured)) {
                $structured = [];
                $keys = array_keys($schema);
                $positions = [];

                foreach ($keys as $key) {
                    $words = explode('_', $key);
                    $variations = [
                        $key,
                        str_replace('_', ' ', $key),
                        implode(' ', array_map('ucfirst', $words)),
                        ucfirst(str_replace('_', ' ', $key)),
                        str_replace('_', '', $key),
                    ];
                    $variations = array_unique($variations);

                    foreach ($variations as $var) {
                        $pattern = '/\b'.preg_quote($var, '/').'\b\s*[:\-–—]*/i';
                        if (preg_match($pattern, $cleanContent, $matches, PREG_OFFSET_CAPTURE)) {
                            $matchOffset = $matches[0][1];
                            $matchLength = strlen($matches[0][0]);
                            $positions[$key] = [
                                'start' => $matchOffset,
                                'end' => $matchOffset + $matchLength,
                            ];
                            break;
                        }
                    }
                }

                if (count($positions) > 0) {
                    uasort($positions, fn ($a, $b) => $a['start'] <=> $b['start']);
                    $keysOrdered = array_keys($positions);

                    for ($i = 0; $i < count($keysOrdered); $i++) {
                        $currentKey = $keysOrdered[$i];
                        $valStart = $positions[$currentKey]['end'];

                        if ($i < count($keysOrdered) - 1) {
                            $nextKey = $keysOrdered[$i + 1];
                            $valEnd = $positions[$nextKey]['start'];
                            $val = substr($cleanContent, $valStart, $valEnd - $valStart);
                        } else {
                            $val = substr($cleanContent, $valStart);
                        }

                        $val = trim($val);
                        $val = preg_replace('/^["\'`“”‘’]+/u', '', $val);
                        $val = preg_replace('/["\'`“”‘’]+$/u', '', $val);
                        $val = trim($val);

                        $structured[$currentKey] = $val;
                    }
                }
            }

            // Ensure all required schema keys exist in the structured output
            if (is_array($structured)) {
                foreach (array_keys($schema) as $requiredKey) {
                    if (! isset($structured[$requiredKey])) {
                        $structured[$requiredKey] = '';
                    }
                }
            }

            if (! is_array($structured) || count(array_filter($structured)) === 0) {
                throw new AiException('CV AI provider returned invalid structured JSON. Raw response: '.substr($content, 0, 200));
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

    protected function failedResponse(Provider $provider, string $model, bool $structured = false): TextResponse
    {
        $payload = ['error' => true];
        $usage = new Usage(promptTokens: 0, completionTokens: 0);
        $meta = new Meta($provider->name(), $model);

        if ($structured) {
            return new StructuredTextResponse(
                structured: $payload,
                text: json_encode($payload),
                usage: $usage,
                meta: $meta,
            );
        }

        return new TextResponse(
            text: json_encode($payload),
            usage: $usage,
            meta: $meta,
        );
    }

    protected function chatCompletionsBaseUrl(Provider $provider): string
    {
        return rtrim($provider->additionalConfiguration()['url'] ?? 'https://api.openai.com/v1', '/');
    }
}
