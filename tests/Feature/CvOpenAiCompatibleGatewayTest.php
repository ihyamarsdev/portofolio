<?php

namespace Tests\Feature;

use App\Ai\Agents\ComprehensiveCVAnalysisAgent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CvOpenAiCompatibleGatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_provider_posts_to_chat_completions_and_returns_structured_output(): void
    {
        config([
            'ai.providers.cv' => [
                'driver' => 'cv-openai-compatible',
                'key' => 'test-key',
                'url' => 'https://cv-ai.test/v1',
                'models' => [
                    'text' => [
                        'default' => 'cv-test-model',
                    ],
                ],
            ],
        ]);

        Http::fake([
            'https://cv-ai.test/v1/chat/completions' => Http::response([
                'model' => 'cv-test-model',
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'ats_score' => 88,
                            'format_score' => 91,
                            'about_me_score' => 84,
                            'keywords' => [
                                'found' => ['Laravel', 'Docker'],
                                'missing' => ['Kubernetes'],
                            ],
                            'suggestions' => ['Add Kubernetes experience'],
                            'industry_match' => [
                                'devops' => 86,
                                'cloud' => 82,
                                'sre' => 78,
                            ],
                            'summary' => 'Strong CV.',
                            'job_match_score' => 80,
                            'job_title_targeted' => 'DevOps Engineer',
                        ]),
                    ],
                    'finish_reason' => 'stop',
                ]],
                'usage' => [
                    'prompt_tokens' => 10,
                    'completion_tokens' => 20,
                ],
            ]),
        ]);

        $response = (new ComprehensiveCVAnalysisAgent('CV content'))->prompt(
            'Analyze CV.',
            provider: 'cv',
            model: 'cv-test-model',
            timeout: 120,
        );

        $this->assertSame(88, $response->toArray()['ats_score']);
        $this->assertSame(['Laravel', 'Docker'], $response->toArray()['keywords']['found']);

        Http::assertSent(fn ($request): bool => $request->url() === 'https://cv-ai.test/v1/chat/completions'
            && $request->hasHeader('Authorization', 'Bearer test-key')
            && $request['model'] === 'cv-test-model'
            && $request['response_format']['type'] === 'json_object'
        );
    }

    public function test_cv_provider_parses_json_with_sse_done_suffix(): void
    {
        config([
            'ai.providers.cv' => [
                'driver' => 'cv-openai-compatible',
                'key' => 'test-key',
                'url' => 'https://cv-ai.test/v1',
                'models' => [
                    'text' => [
                        'default' => 'cv-test-model',
                    ],
                ],
            ],
        ]);

        $payload = [
            'model' => 'cv-test-model',
            'choices' => [[
                'message' => [
                    'content' => json_encode([
                        'ats_score' => 90,
                        'format_score' => 92,
                        'about_me_score' => 85,
                        'keywords' => [
                            'found' => ['Docker'],
                            'missing' => ['Kubernetes'],
                        ],
                        'suggestions' => ['Add Kubernetes projects'],
                        'industry_match' => [
                            'devops' => 88,
                            'cloud' => 80,
                            'sre' => 75,
                        ],
                        'summary' => 'Good CV.',
                        'job_match_score' => 0,
                        'job_title_targeted' => 'Not Specified',
                    ]),
                ],
                'finish_reason' => 'stop',
            ]],
        ];

        Http::fake([
            'https://cv-ai.test/v1/chat/completions' => Http::response(json_encode($payload)."\ndata: [DONE]\n", 200),
        ]);

        $response = (new ComprehensiveCVAnalysisAgent('CV content'))->prompt(
            'Analyze CV.',
            provider: 'cv',
            model: 'cv-test-model',
        );

        $this->assertSame(90, $response->toArray()['ats_score']);
    }

    public function test_cv_provider_throws_clear_error_for_empty_non_json_response(): void
    {
        config([
            'ai.providers.cv' => [
                'driver' => 'cv-openai-compatible',
                'key' => 'test-key',
                'url' => 'https://cv-ai.test/v1',
                'models' => [
                    'text' => [
                        'default' => 'cv-test-model',
                    ],
                ],
            ],
        ]);

        Http::fake([
            'https://cv-ai.test/v1/chat/completions' => Http::response('', 200),
        ]);

        $this->expectExceptionMessage('CV AI provider returned a non-JSON response.');

        (new ComprehensiveCVAnalysisAgent('CV content'))->prompt(
            'Analyze CV.',
            provider: 'cv',
            model: 'cv-test-model',
        );
    }
}
