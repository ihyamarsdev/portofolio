<?php

namespace Tests\Feature;

use App\Ai\Agents\ComprehensiveCVAnalysisAgent;
use App\Filament\Pages\CVGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Ai;
use ReflectionMethod;
use Tests\TestCase;

class CVGeneratorAiSdkTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_generator_uses_laravel_ai_structured_response(): void
    {
        config([
            'cv.provider' => 'cv',
            'cv.model' => 'cv-test-model',
        ]);

        Ai::fakeAgent(ComprehensiveCVAnalysisAgent::class, [[
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
            'summary' => 'Strong CV for backend and DevOps roles.',
            'job_match_score' => 80,
            'job_title_targeted' => 'DevOps Engineer',
        ]]);

        $agent = new ComprehensiveCVAnalysisAgent('CV content');
        $method = new ReflectionMethod(CVGenerator::class, 'analyzeWithLaravelAi');
        $method->setAccessible(true);

        $result = $method->invoke(new CVGenerator, $agent);

        $this->assertSame(88, $result['ats_score']);
        $this->assertSame(['Laravel', 'Docker'], $result['keywords']['found']);

        Ai::assertAgentWasPrompted(
            ComprehensiveCVAnalysisAgent::class,
            fn ($prompt): bool => $prompt->provider->name() === 'cv'
                && $prompt->model === 'cv-test-model'
                && $prompt->timeout === 120
        );
    }
}
