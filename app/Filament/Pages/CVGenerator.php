<?php

namespace App\Filament\Pages;

use App\Models\CVAnalysis;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Services\GeminiService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class CVGenerator extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

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

    public function mount()
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



    public function analyze(GeminiService $service)
    {
        $this->isAnalyzing = true;

        try {
            $profile = Profile::where('is_primary', true)->first();

            if (!$profile) {
                Notification::make()
                    ->title('Profile Not Found')
                    ->body('Please create a primary profile first.')
                    ->danger()
                    ->send();
                $this->isAnalyzing = false;

                return;
            }

            $cvContent = $this->buildCVContent($profile);

            $result = $service->comprehensiveCVAnalysis($cvContent, $this->jobDescription);

            if (isset($result['error'])) {
                Notification::make()
                    ->title('Analysis Failed')
                    ->body($result['error'])
                    ->danger()
                    ->send();
            } else {
                $this->analysisResult = $result;

                $analysis = CVAnalysis::create([
                    'profile_id' => $profile->id,
                    'ats_score' => $result['ats_score'],
                    'format_score' => $result['format_score'],
                    'keywords_found' => $result['keywords']['found'] ?? [],
                    'keywords_missing' => $result['keywords']['missing'] ?? [],
                    'suggestions' => $result['suggestions'] ?? [],
                    'industry_match' => $result['industry_match'] ?? [],
                    'summary' => $result['summary'] ?? null,
                    'job_match_score' => $result['job_match_score'] ?? null,
                ]);

                $this->currentAnalysis = $analysis;
                $this->history = $profile->cvAnalyses()->latest()->take(10)->get();

                Notification::make()
                    ->title('Analysis Complete')
                    ->success()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        $this->isAnalyzing = false;
    }

    public function analyzeJobMatch(GeminiService $service)
    {
        if (empty($this->jobDescription)) {
            Notification::make()
                ->title('Job Description Required')
                ->body('Please paste a job description first.')
                ->warning()
                ->send();

            return;
        }

        $this->analyze($service);
    }

    public function updatedSelectedTemplate($state)
    {
        $this->selectTemplate($state);
    }

    public function selectTemplate(string $template)
    {
        if (!array_key_exists($template, config('cv-templates'))) {
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
            ->body('Now using: ' . ucfirst($template))
            ->success()
            ->send();
    }

    public function downloadCv()
    {
        $profile = Profile::where('is_primary', true)->first();

        if (!$profile) {
            Notification::make()
                ->title('Profile Not Found')
                ->body('Please create a primary profile first.')
                ->danger()
                ->send();
            return;
        }

        return redirect()->route('cv.download', ['template' => $this->selectedTemplate]);
    }

    public function downloadExport()
    {
        Notification::make()
            ->title('Export Options')
            ->body('PDF, DOCX, Text, Markdown exports coming soon!')
            ->info()
            ->send();
    }

    protected function buildCVContent(Profile $profile): string
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $cvContent = 'FULL NAME: ' . $profile->name . "\n";
        $cvContent .= 'TITLE: ' . $profile->title . "\n";
        $cvContent .= 'SUMMARY: ' . strip_tags($profile->biography) . "\n\n";

        $cvContent .= "EXPERIENCE:\n";
        foreach ($experiences as $exp) {
            $cvContent .= '- ' . $exp->position . ' at ' . $exp->company . ' (' . $exp->start_date->format('Y') . '-' . ($exp->end_date ? $exp->end_date->format('Y') : 'Present') . ")\n";
            $cvContent .= '  ' . strip_tags($exp->description) . "\n";
            if ($exp->achievements) {
                foreach ($exp->achievements as $achievement) {
                    $cvContent .= '  Achievement: ' . $achievement . "\n";
                }
            }
        }

        $cvContent .= "\nEDUCATION:\n";
        foreach ($educations as $edu) {
            $cvContent .= '- ' . $edu->degree . ' in ' . $edu->field_of_study . ' at ' . $edu->institution . "\n";
        }

        $cvContent .= "\nSKILLS:\n";
        foreach ($skills as $skill) {
            $cvContent .= '- ' . $skill->name . ' (' . $skill->category . ', Proficiency: ' . $skill->proficiency . "/100)\n";
        }

        return $cvContent;
    }
}
