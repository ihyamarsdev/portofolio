<?php

namespace App\Filament\Pages;

use App\Ai\Agents\ComprehensiveCVAnalysisAgent;
use App\Models\CVAnalysis;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
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

    public function analyze()
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
            $result = json_decode($agent->prompt(
                prompt: 'Please analyze the CV content and provide comprehensive feedback following the instructions.',
                model: 'gemini-2.5-flash'
            )->text, true);

            $this->analysisResult = [
                'ats_score' => $result['ats_score'],
                'format_score' => $result['format_score'],
                'about_me_score' => $result['about_me_score'] ?? 0,
                'keywords' => $result['keywords'],
                'suggestions' => $result['suggestions'],
                'industry_match' => $result['industry_match'],
                'summary' => $result['summary'],
                'job_match_score' => $result['job_match_score'] ?? null,
            ];

            // Save to database
            $profile->cvAnalyses()->create([
                'content' => $cvContent,
                'ats_score' => $result['ats_score'],
                'format_score' => $result['format_score'],
                'about_me_score' => $result['about_me_score'] ?? 0,
                'keywords_found' => $result['keywords']['found'] ?? [],
                'keywords_missing' => $result['keywords']['missing'] ?? [],
                'suggestions' => $result['suggestions'] ?? [],
                'industry_compatibility' => $result['industry_match'] ?? [],
                'job_match_score' => $result['job_match_score'] ?? null,
                'summary' => $result['summary'] ?? null,
                'job_description' => $this->jobDescription,
            ]);

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

            \Log::error('CV Analysis Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        } finally {
            $this->isAnalyzing = false;
        }
    }

    public function analyzeJobMatch()
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

    public function updatedSelectedTemplate($state)
    {
        $this->selectTemplate($state);
    }

    public function selectTemplate(string $template)
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

        $cvContent = 'FULL NAME: '.$profile->name."\n";
        $cvContent .= 'TITLE: '.$profile->title."\n\n";

        // Add explicit ABOUT ME section for AI analysis
        $cvContent .= "ABOUT ME / PROFESSIONAL SUMMARY:\n";
        $cvContent .= strip_tags($profile->biography)."\n\n";

        $cvContent .= "EXPERIENCE:\n";
        foreach ($experiences as $exp) {
            $cvContent .= '- '.$exp->position.' at '.$exp->company.' ('.$exp->start_date->format('Y').'-'.($exp->end_date ? $exp->end_date->format('Y') : 'Present').")\n";
            $cvContent .= '  '.strip_tags($exp->description)."\n";
            if ($exp->achievements) {
                foreach ($exp->achievements as $achievement) {
                    $cvContent .= '  Achievement: '.$achievement."\n";
                }
            }
        }

        $cvContent .= "\nEDUCATION:\n";
        foreach ($educations as $edu) {
            $cvContent .= '- '.$edu->degree.' in '.$edu->field_of_study.' at '.$edu->institution."\n";
        }

        $cvContent .= "\nSKILLS:\n";
        foreach ($skills as $skill) {
            if ($skill->years_experience > 0) {
                $years = floatval($skill->years_experience);
                $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                $yearText = $years == 1 ? 'year' : 'years';
                $yearsExp = " ({$displayYears} {$yearText})";
            } else {
                $yearsExp = '';
            }
            $cvContent .= '- '.$skill->name.' ('.$skill->category.')'.$yearsExp."\n";
        }

        return $cvContent;
    }
}
