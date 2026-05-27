<?php

namespace App\Filament\Pages;

use App\Ai\Agents\ComprehensiveCVAnalysisAgent;
use App\Models\CVAnalysis;
use App\Models\Profile;
use App\Services\CVGeneratorService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Responses\StructuredAgentResponse;

class CVGenerator extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public function __construct()
    {
        $this->cvService = app(CVGeneratorService::class);
    }

    protected CVGeneratorService $cvService;

    protected static ?string $title = 'CV Generator & Analysis';

    protected static ?string $slug = 'cv-generator';

    public static function getNavigationGroup(): ?string
    {
        return 'CV Management';
    }

    protected string $view = 'filament.pages.cv-generator';

    public ?array $analysisResult = null;

    public bool $isAnalyzing = false;

    public string $selectedTemplate = 'ats';

    public ?string $jobDescription = null;

    public ?CVAnalysis $currentAnalysis = null;

    public $history = [];

    public function mount(): void
    {

        $profile = Profile::where('is_primary', true)->first();
        if ($profile) {
            $this->selectedTemplate = $profile->cv_template ?? 'ats';
            $this->currentAnalysis = $profile->latestAnalysis;
            $this->history = $profile->cvAnalyses()->latest()->take(10)->get();

            if ($this->currentAnalysis) {
                $this->analysisResult = [
                    'ats_score' => $this->currentAnalysis->ats_score,
                    'format_score' => $this->currentAnalysis->format_score,
                    'about_me_score' => $this->currentAnalysis->about_me_score ?? 0,
                    'keywords' => [
                        'found' => $this->currentAnalysis->keywords_found ?? [],
                        'missing' => $this->currentAnalysis->keywords_missing ?? [],
                    ],
                    'suggestions' => $this->currentAnalysis->suggestions ?? [],
                    'industry_match' => $this->currentAnalysis->industry_match ?? [],
                    'summary' => $this->currentAnalysis->summary,
                    'job_match_score' => $this->currentAnalysis->job_match_score,
                ];
            }
        }
    }

    public function analyze(): void
    {
        $this->isAnalyzing = true;

        try {
            $profile = Profile::where('is_primary', true)->first();

            if (! $profile) {
                Notification::make()
                    ->title('Profile Not Found')
                    ->body('Please create a primary profile first.')
                    ->danger()
                    ->send();
                $this->isAnalyzing = false;

                return;
            }

            $cvContent = $this->buildCVContent($profile);

            $agent = new ComprehensiveCVAnalysisAgent($cvContent, $this->jobDescription);
            $result = $this->analyzeWithLaravelAi($agent);

            $this->analysisResult = [
                'ats_score' => $result['ats_score'] ?? 0,
                'format_score' => $result['format_score'] ?? 0,
                'about_me_score' => $result['about_me_score'] ?? 0,
                'keywords' => $result['keywords'] ?? ['found' => [], 'missing' => []],
                'suggestions' => $result['suggestions'] ?? [],
                'industry_match' => $result['industry_match'] ?? [],
                'summary' => $result['summary'] ?? null,
                'job_match_score' => $result['job_match_score'] ?? null,
                'job_title_targeted' => $result['job_title_targeted'] ?? null,
            ];

            $analysis = $profile->cvAnalyses()->create([
                'ats_score' => $result['ats_score'] ?? 0,
                'format_score' => $result['format_score'] ?? 0,
                'about_me_score' => $result['about_me_score'] ?? 0,
                'keywords_found' => $result['keywords']['found'] ?? [],
                'keywords_missing' => $result['keywords']['missing'] ?? [],
                'suggestions' => $result['suggestions'] ?? [],
                'industry_match' => $result['industry_match'] ?? [],
                'job_match_score' => $result['job_match_score'] ?? null,
                'summary' => $result['summary'] ?? null,
                'job_title_targeted' => $result['job_title_targeted'] ?? null,
            ]);

            $this->currentAnalysis = $analysis;
            $this->history = $profile->cvAnalyses()->latest()->take(10)->get();

            Notification::make()
                ->title('Analysis Complete')
                ->body('Your CV has been analyzed successfully.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Analysis Failed')
                ->body('Error: '.$e->getMessage())
                ->danger()
                ->send();

            Log::error('CV Analysis Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        } finally {
            $this->isAnalyzing = false;
        }
    }

    public function analyzeJobMatch(): void
    {
        if (empty($this->jobDescription)) {
            Notification::make()
                ->title('Job Description Required')
                ->body('Please paste a job description first.')
                ->warning()
                ->send();

            return;
        }

        $this->analyze();
    }

    public function updatedSelectedTemplate($state): void
    {
        $this->selectTemplate($state);
    }

    public function selectTemplate(string $template): void
    {
        if (! array_key_exists($template, config('cv-templates'))) {
            Notification::make()
                ->title('Invalid Template')
                ->body('Template not found.')
                ->danger()
                ->send();

            return;
        }

        $profile = Profile::where('is_primary', true)->first();
        if ($profile) {
            $profile->update(['cv_template' => $template]);
        }

        $this->selectedTemplate = $template;

        Notification::make()
            ->title('Template Selected')
            ->body('Now using: '.ucfirst($template))
            ->success()
            ->send();
    }

    public function downloadCv()
    {
        $profile = Profile::where('is_primary', true)->first();

        if (! $profile) {
            Notification::make()
                ->title('Profile Not Found')
                ->body('Please create a primary profile first.')
                ->danger()
                ->send();

            return;
        }

        return redirect()->route('cv.download', ['template' => $this->selectedTemplate]);
    }

    public function downloadExport(): void
    {
        Notification::make()
            ->title('Export Options')
            ->body('PDF, DOCX, Text, Markdown exports coming soon!')
            ->info()
            ->send();
    }

    protected function analyzeWithLaravelAi(ComprehensiveCVAnalysisAgent $agent): array
    {
        $response = $agent->prompt(
            'Analyze the CV content and return structured output matching the schema.',
            provider: config('cv.provider', 'cv'),
            model: config('cv.model'),
            timeout: 120,
        );

        if ($response instanceof StructuredAgentResponse) {
            return $response->toArray();
        }

        return json_decode($response->text, true, flags: JSON_THROW_ON_ERROR);
    }

    protected function buildCVContent(Profile $profile): string
    {
        return $this->cvService->buildCVContent($profile);
    }
}
