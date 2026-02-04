<?php

namespace App\Filament\Pages;

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

    public static function getNavigationGroup(): ?string
    {
        return 'CV Management';
    }

    protected string $view = 'filament.pages.c-v-generator';

    public ?array $analysisResult = null;
    public bool $isAnalyzing = false;

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

            $experiences = Experience::orderBy('start_date', 'desc')->get();
            $educations = Education::orderBy('start_date', 'desc')->get();
            $skills = Skill::all();

            // Build simplified text representation of CV
            $cvContent = "FULL NAME: " . $profile->name . "\n";
            $cvContent .= "TITLE: " . $profile->title . "\n";
            $cvContent .= "SUMMARY: " . strip_tags($profile->biography) . "\n\n";

            $cvContent .= "EXPERIENCE:\n";
            foreach ($experiences as $exp) {
                $cvContent .= "- " . $exp->position . " at " . $exp->company . " (" . $exp->start_date->format('Y') . "-" . ($exp->end_date ? $exp->end_date->format('Y') : 'Present') . ")\n";
                $cvContent .= "  " . strip_tags($exp->description) . "\n";
            }

            $cvContent .= "\nEDUCATION:\n";
            foreach ($educations as $edu) {
                $cvContent .= "- " . $edu->degree . " in " . $edu->field_of_study . " at " . $edu->institution . "\n";
            }

            $cvContent .= "\nSKILLS:\n";
            foreach ($skills as $skill) {
                $cvContent .= "- " . $skill->name . " (" . $skill->category . ")\n";
            }

            $result = $service->analyzeCV($cvContent);

            if (isset($result['error'])) {
                Notification::make()
                    ->title('Analysis Failed')
                    ->body($result['error'])
                    ->danger()
                    ->send();
            } else {
                $this->analysisResult = $result;
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
}
