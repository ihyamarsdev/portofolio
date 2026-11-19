<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class CVGeneratorService
{
    public function generateHTML(string $template, Profile $profile, Collection|array|null $experiences = null, Collection|array|null $educations = null, Collection|array|null $skills = null): string
    {
        $cvData = $this->buildCvData($profile, $experiences, $educations, $skills);

        return View::make("cv.{$template}", $cvData + ['isPdf' => false, 'selectedTemplate' => $template])->render();
    }

    public function generatePDF(string $template, Profile $profile, Collection|array|null $experiences = null, Collection|array|null $educations = null, Collection|array|null $skills = null)
    {
        $cvData = $this->buildCvData($profile, $experiences, $educations, $skills);

        return Pdf::loadView("cv.{$template}", $cvData + ['isPdf' => true, 'selectedTemplate' => $template]);
    }

    public function generateDOCX(string $template, Profile $profile, Collection|array|null $experiences = null, Collection|array|null $educations = null, Collection|array|null $skills = null): string
    {
        ['experiences' => $experiences, 'educations' => $educations, 'skills' => $skills] = $this->buildCvData($profile, $experiences, $educations, $skills);

        $phpWord = new PhpWord;
        $phpWord->getSettings()->setUpdateFields(true);

        $section = $phpWord->addSection();
        $this->addHeaderToDocx($section, $profile);
        $this->addSummaryToDocx($section, $profile);
        $this->addExperienceToDocx($section, $experiences);
        $this->addEducationToDocx($section, $educations);
        $this->addSkillsToDocx($section, $skills);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');

        ob_start();
        $writer->save('php://output');

        return ob_get_clean();
    }

    public function generateText(Profile $profile, Collection|array|null $experiences = null, Collection|array|null $educations = null, Collection|array|null $skills = null): string
    {
        ['experiences' => $experiences, 'educations' => $educations, 'skills' => $skills] = $this->buildCvData($profile, $experiences, $educations, $skills);

        $text = strtoupper($profile->name)."\n";
        $text .= $profile->title."\n\n";

        $contactInfo = [];
        if ($profile->email) {
            $contactInfo[] = $profile->email;
        }
        if ($profile->phone) {
            $contactInfo[] = $profile->phone;
        }
        if ($profile->location) {
            $contactInfo[] = $profile->location;
        }

        if (! empty($contactInfo)) {
            $text .= implode(' | ', $contactInfo)."\n\n";
        }

        if ($profile->biography) {
            $text .= "PROFESSIONAL SUMMARY\n";
            $text .= str_repeat('=', 50)."\n";
            $text .= strip_tags($profile->biography)."\n\n";
        }

        $text .= "EXPERIENCE\n";
        $text .= str_repeat('=', 50)."\n\n";

        foreach ($experiences as $exp) {
            $text .= strtoupper($exp->position)."\n";
            $text .= $exp->company.', '.$exp->location."\n";
            $text .= $exp->start_date->format('M Y').' - '.($exp->end_date ? $exp->end_date->format('M Y') : 'Present')."\n";

            if ($exp->description) {
                $text .= "\n".strip_tags($exp->description)."\n";
            }

            if ($exp->achievements) {
                foreach ($exp->achievements as $achievement) {
                    $text .= '- '.$achievement."\n";
                }
            }

            $text .= "\n";
        }

        $text .= "\nEDUCATION\n";
        $text .= str_repeat('=', 50)."\n\n";

        foreach ($educations as $edu) {
            $text .= $edu->degree.' in '.$edu->field_of_study."\n";
            $text .= $edu->institution."\n";
            $text .= $edu->start_date->format('Y').' - '.($edu->end_date ? $edu->end_date->format('Y') : 'Present')."\n";

            if ($edu->grade) {
                $text .= 'GPA/Grade: '.$edu->grade."\n";
            }

            if ($edu->activities) {
                $text .= 'Activities: '.$edu->activities."\n";
            }

            $text .= "\n";
        }

        $text .= "\nSKILLS\n";
        $text .= str_repeat('=', 50)."\n\n";

        $groupedSkills = $skills->groupBy('category');

        foreach ($groupedSkills as $category => $categorySkills) {
            $text .= ucfirst((string) $category).': '.$categorySkills->pluck('name')->join(', ')."\n";
        }

        return $text;
    }

    public function generateMarkdown(Profile $profile, Collection|array|null $experiences = null, Collection|array|null $educations = null, Collection|array|null $skills = null): string
    {
        ['experiences' => $experiences, 'educations' => $educations, 'skills' => $skills] = $this->buildCvData($profile, $experiences, $educations, $skills);

        $md = "# {$profile->name}\n\n";
        $md .= "**{$profile->title}**\n\n";

        $contactInfo = [];
        if ($profile->email) {
            $contactInfo[] = "Email: {$profile->email}";
        }
        if ($profile->phone) {
            $contactInfo[] = "Phone: {$profile->phone}";
        }
        if ($profile->location) {
            $contactInfo[] = "Location: {$profile->location}";
        }

        if (! empty($contactInfo)) {
            $md .= implode(' | ', $contactInfo)."\n\n";
        }

        if ($profile->biography) {
            $md .= "## Professional Summary\n\n";
            $md .= strip_tags($profile->biography)."\n\n";
        }

        $md .= "## Experience\n\n";

        foreach ($experiences as $exp) {
            $md .= "### **{$exp->position}** - {$exp->company}\n";
            $md .= "*{$exp->location}* | {$exp->start_date->format('M Y')} - ".($exp->end_date ? $exp->end_date->format('M Y') : 'Present')."\n\n";

            if ($exp->description) {
                $md .= strip_tags($exp->description)."\n\n";
            }

            if ($exp->achievements) {
                foreach ($exp->achievements as $achievement) {
                    $md .= "- {$achievement}\n";
                }
                $md .= "\n";
            }
        }

        $md .= "## Education\n\n";

        foreach ($educations as $edu) {
            $md .= "### **{$edu->degree}** in {$edu->field_of_study}\n";
            $md .= "{$edu->institution} | {$edu->start_date->format('Y')} - ".($edu->end_date ? $edu->end_date->format('Y') : 'Present')."\n\n";

            if ($edu->grade) {
                $md .= "**GPA/Grade:** {$edu->grade}\n";
            }

            if ($edu->activities) {
                $md .= "**Activities:** {$edu->activities}\n";
            }

            $md .= "\n";
        }

        $md .= "## Skills\n\n";

        $groupedSkills = $skills->groupBy('category');

        foreach ($groupedSkills as $category => $categorySkills) {
            $md .= '**'.ucfirst((string) $category).'**: '.$categorySkills->pluck('name')->join(', ')."\n\n";
        }

        return $md;
    }

    /**
     * Current schema stores experience/education/skill records globally rather than
     * attaching them to profiles. Keep CV data resolution consistent across all
     * export/analysis paths until profile-scoped tables are introduced.
     */
    public function buildCvData(Profile $profile, Collection|array|null $experiences = null, Collection|array|null $educations = null, Collection|array|null $skills = null): array
    {
        $allExperiences = collect($experiences ?? $this->getExperiences());
        $allEducations = collect($educations ?? $this->getEducations());

        return [
            'profile' => $profile,
            'experiences' => $allExperiences->take(3),
            'totalExperiences' => $allExperiences->count(),
            'educations' => $allEducations->take(3),
            'totalEducations' => $allEducations->count(),
            'skills' => $this->normalizeSkills($skills ?? $this->getSkills()),
            'projects' => $this->getProjects(),
            'certificates' => $this->getCertificates(),
        ];
    }

    /**
     * Featured projects with their linked skills, for the Selected Projects
     * section. Falls back to the most recently ordered projects when none are
     * explicitly featured so the section is populated by default.
     */
    protected function getProjects(): Collection
    {
        $query = Project::query()->with('skills');
        $projects = (clone $query)->where('is_featured', true)
            ->orderBy('order')
            ->take(3)
            ->get();

        if ($projects->isEmpty()) {
            $projects = $query->orderBy('order')->take(3)->get();
        }

        return $projects;
    }

    protected function getCertificates(): Collection
    {
        return Certificate::query()
            ->orderByDesc('issue_date')
            ->take(6)
            ->get();
    }

    public function buildCVContent(Profile $profile): string
    {
        ['experiences' => $experiences, 'educations' => $educations, 'skills' => $skills] = $this->buildCvData($profile);

        $cvContent = 'FULL NAME: '.$profile->name."\n";
        $cvContent .= 'TITLE: '.$profile->title."\n\n";
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
                $years = (float) $skill->years_experience;
                $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : (int) $years;
                $yearText = $years == 1.0 ? 'year' : 'years';
                $yearsExp = " ({$displayYears} {$yearText})";
            } else {
                $yearsExp = '';
            }

            $cvContent .= '- '.$skill->name.' ('.$skill->category.')'.$yearsExp."\n";
        }

        $cvContent .= $this->buildVerifiedEvidenceSection($experiences, $skills);

        return $cvContent;
    }

    /**
     * Build a grounded evidence ledger from the experience_skill and
     * project_skill pivot tables. This lets the analysis engine distinguish
     * skills actually demonstrated in work from skills merely listed — and
     * prevents the AI from inventing experience that is not in the database.
     */
    protected function buildVerifiedEvidenceSection(Collection $experiences, Collection $skills): string
    {
        if ($skills->isEmpty()) {
            return '';
        }

        $projects = $this->getProjects();
        $lines = [];

        // Load all pivot links once per entry instead of once per skill/entry.
        $experienceSkillIds = [];
        foreach ($experiences as $exp) {
            $experienceSkillIds[$exp->id] = $exp->skills()->pluck('skills.id')->all();
        }
        $projectSkillIds = [];
        foreach ($projects as $project) {
            $projectSkillIds[$project->id] = $project->skills()->pluck('skills.id')->all();
        }

        foreach ($skills as $skill) {
            if (empty($skill->id)) {
                continue;
            }

            $usedIn = [];
            foreach ($experiences as $exp) {
                if (in_array($skill->id, $experienceSkillIds[$exp->id] ?? [], false)) {
                    $usedIn[] = 'role "'.trim(($exp->position ?? '').' at '.($exp->company ?? '')).'"';
                }
            }
            foreach ($projects as $project) {
                if (in_array($skill->id, $projectSkillIds[$project->id] ?? [], false)) {
                    $usedIn[] = 'project "'.($project->title ?? '').'"';
                }
            }

            if ($usedIn !== []) {
                $lines[] = '- VERIFIED: "'.$skill->name.'" is linked in the database to '.implode('; ', $usedIn).'.';
            }
        }

        if ($lines === []) {
            return '';
        }

        return "\nVERIFIED SKILL EVIDENCE (from experience/project links — only these are backed by records):\n"
            .implode("\n", $lines)."\n";
    }

    protected function getExperiences(): Collection
    {
        return Experience::where('is_featured', true)
            ->orderBy('order', 'asc')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    protected function getEducations(): Collection
    {
        return Education::orderBy('start_date', 'desc')->get();
    }

    protected function getSkills(): Collection
    {
        return Skill::with('category')->get();
    }

    protected function normalizeSkills(Collection|array $skills): Collection
    {
        return collect($skills)->map(function ($skill) {
            if (is_object($skill)) {
                $skill->setAttribute('category_name', $this->resolveSkillCategoryName($skill));
            }

            return $skill;
        });
    }

    protected function resolveSkillCategoryName($skill): string
    {
        if (is_object($skill) && isset($skill->category_name) && is_string($skill->category_name) && $skill->category_name !== '') {
            return $skill->category_name;
        }

        if (is_object($skill) && method_exists($skill, 'relationLoaded') && method_exists($skill, 'category')) {
            $category = $skill->relationLoaded('category') ? $skill->getRelation('category') : $skill->category;

            if (is_object($category) && isset($category->name) && is_string($category->name) && $category->name !== '') {
                return $category->name;
            }
        }

        if (is_object($skill) && isset($skill->category) && is_string($skill->category) && $skill->category !== '') {
            return $skill->category;
        }

        return 'General';
    }

    protected function addHeaderToDocx($section, Profile $profile)
    {
        $section->addText($profile->name, ['bold' => true, 'size' => 24]);
        $section->addText($profile->title, ['size' => 14, 'color' => '666666']);

        $contactInfo = [];
        if ($profile->email) {
            $contactInfo[] = $profile->email;
        }
        if ($profile->phone) {
            $contactInfo[] = $profile->phone;
        }
        if ($profile->location) {
            $contactInfo[] = $profile->location;
        }

        if (! empty($contactInfo)) {
            $section->addText(implode(' | ', $contactInfo), ['size' => 10]);
        }

        $section->addTextBreak(1);
    }

    protected function addSummaryToDocx($section, Profile $profile)
    {
        if ($profile->biography) {
            $section->addText('Professional Summary', ['bold' => true, 'size' => 14]);
            $section->addText(strip_tags($profile->biography), ['size' => 11]);
            $section->addTextBreak(1);
        }
    }

    protected function addExperienceToDocx($section, $experiences)
    {
        $section->addText('Experience', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);

        foreach ($experiences as $exp) {
            $section->addText($exp->position, ['bold' => true, 'size' => 12]);
            $section->addText(
                $exp->company.' | '.$exp->location.' | '.$exp->start_date->format('M Y').' - '.($exp->end_date ? $exp->end_date->format('M Y') : 'Present'),
                ['size' => 11, 'italic' => true]
            );

            if ($exp->description) {
                $section->addText(strip_tags($exp->description), ['size' => 11]);
            }

            if ($exp->achievements) {
                foreach ($exp->achievements as $achievement) {
                    $section->addListItem($achievement, 0, null, 'listMultilevel');
                }
            }

            $section->addTextBreak(1);
        }
    }

    protected function addEducationToDocx($section, $educations)
    {
        $section->addText('Education', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);

        foreach ($educations as $edu) {
            $section->addText($edu->degree.' in '.$edu->field_of_study, ['bold' => true, 'size' => 12]);
            $section->addText(
                $edu->institution.' | '.$edu->start_date->format('Y').' - '.($edu->end_date ? $edu->end_date->format('Y') : 'Present'),
                ['size' => 11, 'italic' => true]
            );

            if ($edu->grade) {
                $section->addText('GPA/Grade: '.$edu->grade, ['size' => 10]);
            }

            if ($edu->activities) {
                $section->addText('Activities: '.$edu->activities, ['size' => 10]);
            }

            $section->addTextBreak(1);
        }
    }

    protected function addSkillsToDocx($section, Collection|array $skills)
    {
        $section->addText('Skills', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);

        $groupedSkills = $this->normalizeSkills($skills)->groupBy('category');

        foreach ($groupedSkills as $category => $categorySkills) {
            $section->addText(ucfirst((string) $category).':', ['bold' => true, 'size' => 11]);
            $section->addText($categorySkills->pluck('name')->join(', '), ['size' => 11]);
            $section->addTextBreak(1);
        }
    }

    public function formatDate($date): string
    {
        if (! $date) {
            return 'Present';
        }

        return $date->format('M Y');
    }
}
