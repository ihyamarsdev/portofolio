<?php

namespace App\Ai\Gateway;

use App\Support\AiNotification;
use App\Support\AiStructuredOutputSanitizer;
use Generator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\JsonSchema\Types\ArrayType;
use Illuminate\JsonSchema\Types\BooleanType;
use Illuminate\JsonSchema\Types\IntegerType;
use Illuminate\JsonSchema\Types\NumberType;
use Illuminate\JsonSchema\Types\ObjectType;
use Illuminate\JsonSchema\Types\StringType;
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
            $cleanContent = AiStructuredOutputSanitizer::stripReasoningBlocks(trim($content));
            if (str_starts_with($cleanContent, '```')) {
                $cleanContent = preg_replace('/^```(?:json)?\n?/i', '', $cleanContent);
                $cleanContent = preg_replace('/```$/', '', $cleanContent);
                $cleanContent = trim($cleanContent);
            }

            $structured = json_decode($cleanContent, true);

            // Fallback 1: Extract the first balanced JSON object from the content
            if (! is_array($structured)) {
                $jsonCandidate = $this->extractJsonCandidate($cleanContent);
                if ($jsonCandidate !== null) {
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

            // Ensure all required schema keys exist in the structured output and have correct types
            if (is_array($structured)) {
                foreach ($schema as $requiredKey => $typeObj) {
                    if (! isset($structured[$requiredKey]) || $structured[$requiredKey] === null) {
                        if ($typeObj instanceof ArrayType || $typeObj instanceof ObjectType) {
                            $structured[$requiredKey] = [];
                        } elseif ($typeObj instanceof IntegerType || $typeObj instanceof NumberType) {
                            $structured[$requiredKey] = 0;
                        } elseif ($typeObj instanceof BooleanType) {
                            $structured[$requiredKey] = false;
                        } else {
                            $structured[$requiredKey] = '';
                        }
                    } else {
                        // Key exists, let's enforce type
                        $val = $structured[$requiredKey];
                        if ($typeObj instanceof ArrayType) {
                            if (is_string($val)) {
                                $cleanVal = trim($val);
                                if (str_starts_with($cleanVal, '```')) {
                                    $cleanVal = preg_replace('/^```(?:json)?\n?/i', '', $cleanVal);
                                    $cleanVal = preg_replace('/```$/', '', $cleanVal);
                                    $cleanVal = trim($cleanVal);
                                }
                                $decoded = json_decode($cleanVal, true);
                                if (is_array($decoded)) {
                                    $structured[$requiredKey] = $decoded;
                                } else {
                                    // Parse bullet points or wrap in array
                                    $lines = preg_split('/\R+/', $val);
                                    $items = [];
                                    foreach ($lines as $line) {
                                        $line = trim(preg_replace('/^\s*[-*•+\d.]+\s+/', '', trim($line)));
                                        if ($line !== '') {
                                            $items[] = $line;
                                        }
                                    }
                                    $structured[$requiredKey] = AiStructuredOutputSanitizer::cleanList(
                                        count($items) > 0 ? $items : [$val]
                                    );
                                }
                            } elseif (! is_array($val)) {
                                $structured[$requiredKey] = (array) $val;
                            }
                        } elseif ($typeObj instanceof ObjectType) {
                            if (is_string($val)) {
                                $cleanVal = trim($val);
                                if (str_starts_with($cleanVal, '```')) {
                                    $cleanVal = preg_replace('/^```(?:json)?\n?/i', '', $cleanVal);
                                    $cleanVal = preg_replace('/```$/', '', $cleanVal);
                                    $cleanVal = trim($cleanVal);
                                }
                                $decoded = json_decode($cleanVal, true);
                                $structured[$requiredKey] = is_array($decoded) ? $decoded : [];
                            } elseif (! is_array($val)) {
                                $structured[$requiredKey] = (array) $val;
                            }
                        } elseif ($typeObj instanceof IntegerType || $typeObj instanceof NumberType) {
                            if (is_string($val)) {
                                $val = trim($val);
                                if (str_contains($val, '/')) {
                                    $val = explode('/', $val)[0];
                                }
                                $val = str_replace('%', '', $val);
                                $val = trim($val);
                            }
                            if (is_numeric($val)) {
                                $structured[$requiredKey] = $typeObj instanceof IntegerType ? (int) round((float) $val) : (float) $val;
                            } else {
                                // Extract first float or int match
                                if (preg_match('/[+-]?\d+(?:\.\d+)?/', $val, $matches)) {
                                    $extracted = $matches[0];
                                    $structured[$requiredKey] = $typeObj instanceof IntegerType ? (int) round((float) $extracted) : (float) $extracted;
                                } else {
                                    $structured[$requiredKey] = 0;
                                }
                            }
                        } elseif ($typeObj instanceof BooleanType) {
                            $structured[$requiredKey] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
                        } elseif ($typeObj instanceof StringType) {
                            if (is_array($val)) {
                                $structured[$requiredKey] = json_encode($val);
                            } else {
                                $structured[$requiredKey] = (string) $val;
                            }
                        }
                    }
                }
            }

            // Remove any reasoning/markdown artifacts that survived parsing.
            $structured = AiStructuredOutputSanitizer::sanitize($structured);

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

    /**
     * Extract the first balanced JSON object from free-form AI content,
     * honoring string literals so braces inside quotes do not skew depth.
     * Falls back to the first-'{' to last-'}' span when unbalanced.
     */
    protected function extractJsonCandidate(string $text): ?string
    {
        $start = strpos($text, '{');

        if ($start === false) {
            return null;
        }

        $depth = 0;
        $inString = false;
        $escaped = false;

        for ($i = $start, $length = strlen($text); $i < $length; $i++) {
            $char = $text[$i];

            if ($inString) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($char === '\\') {
                    $escaped = true;
                } elseif ($char === '"') {
                    $inString = false;
                }

                continue;
            }

            if ($char === '"') {
                $inString = true;
            } elseif ($char === '{') {
                $depth++;
            } elseif ($char === '}') {
                $depth--;

                if ($depth === 0) {
                    return substr($text, $start, $i - $start + 1);
                }
            }
        }

        $lastBrace = strrpos($text, '}');

        if ($lastBrace !== false && $lastBrace > $start) {
            return substr($text, $start, $lastBrace - $start + 1);
        }

        return null;
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
