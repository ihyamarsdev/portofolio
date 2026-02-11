<?php

namespace App\Services;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpWord\PhpWord;

class CVGeneratorService
{
    public function generateHTML(string $template, Profile $profile, ?array $experiences = null, ?array $educations = null, ?array $skills = null): string
    {
        $experiences = $experiences ?? Experience::where('profile_id', $profile->id)->orderBy('start_date', 'desc')->get();
        $educations = $educations ?? Education::where('profile_id', $profile->id)->orderBy('start_date', 'desc')->get();
        $skills = $skills ?? Skill::all();

        return View::make("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills'))->render();
    }

    public function generatePDF(string $template, Profile $profile, ?array $experiences = null, ?array $educations = null, ?array $skills = null)
    {
        $experiences = $experiences ?? Experience::orderBy('start_date', 'desc')->get();
        $educations = $educations ?? Education::orderBy('start_date', 'desc')->get();
        $skills = $skills ?? Skill::all();

        return Pdf::loadView("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills'));
    }

    public function generateDOCX(string $template, Profile $profile, ?array $experiences = null, ?array $educations = null, ?array $skills = null): string
    {
        $phpWord = new PhpWord;

        $phpWord->getSettings()->setThemeColor('Office');
        $phpWord->getSettings()->setUpdateFields(true);

        $section = $phpWord->addSection();

        $this->addHeaderToDocx($section, $profile);
        $this->addSummaryToDocx($section, $profile);
        $this->addExperienceToDocx($section, $experiences ?? Experience::orderBy('start_date', 'desc')->get());
        $this->addEducationToDocx($section, $educations ?? Education::orderBy('start_date', 'desc')->get());
        $this->addSkillsToDocx($section, $skills ?? Skill::all());

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        ob_start();
        $writer->save('php://output');

        return ob_get_clean();
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

        if (!empty($contactInfo)) {
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
                $exp->company . ' | ' . $exp->location . ' | ' .
                $exp->start_date->format('M Y') . ' - ' .
                ($exp->end_date ? $exp->end_date->format('M Y') : 'Present'),
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
            $section->addText($edu->degree . ' in ' . $edu->field_of_study, ['bold' => true, 'size' => 12]);
            $section->addText(
                $edu->institution . ' | ' .
                $edu->start_date->format('Y') . ' - ' .
                ($edu->end_date ? $edu->end_date->format('Y') : 'Present'),
                ['size' => 11, 'italic' => true]
            );

            if ($edu->grade) {
                $section->addText('GPA/Grade: ' . $edu->grade, ['size' => 10]);
            }

            if ($edu->activities) {
                $section->addText('Activities: ' . $edu->activities, ['size' => 10]);
            }

            $section->addTextBreak(1);
        }
    }

    protected function addSkillsToDocx($section, $skills)
    {
        $section->addText('Skills', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);

        $groupedSkills = $skills->groupBy('category');

        foreach ($groupedSkills as $category => $categorySkills) {
            $section->addText(ucfirst($category) . ':', ['bold' => true, 'size' => 11]);
            $section->addText($categorySkills->pluck('name')->join(', '), ['size' => 11]);
            $section->addTextBreak(1);
        }
    }

    public function generateText(Profile $profile, ?array $experiences = null, ?array $educations = null, ?array $skills = null): string
    {
        $experiences = $experiences ?? Experience::orderBy('start_date', 'desc')->get();
        $educations = $educations ?? Education::orderBy('start_date', 'desc')->get();
        $skills = $skills ?? Skill::all();

        $text = strtoupper($profile->name) . "\n";
        $text .= $profile->title . "\n\n";

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

        if (!empty($contactInfo)) {
            $text .= implode(' | ', $contactInfo) . "\n\n";
        }

        if ($profile->biography) {
            $text .= "PROFESSIONAL SUMMARY\n";
            $text .= str_repeat('=', 50) . "\n";
            $text .= strip_tags($profile->biography) . "\n\n";
        }

        $text .= "EXPERIENCE\n";
        $text .= str_repeat('=', 50) . "\n\n";

        foreach ($experiences as $exp) {
            $text .= strtoupper($exp->position) . "\n";
            $text .= $exp->company . ', ' . $exp->location . "\n";
            $text .= $exp->start_date->format('M Y') . ' - ' . ($exp->end_date ? $exp->end_date->format('M Y') : 'Present') . "\n";

            if ($exp->description) {
                $text .= "\n" . strip_tags($exp->description) . "\n";
            }

            if ($exp->achievements) {
                foreach ($exp->achievements as $achievement) {
                    $text .= '- ' . $achievement . "\n";
                }
            }

            $text .= "\n";
        }

        $text .= "\nEDUCATION\n";
        $text .= str_repeat('=', 50) . "\n\n";

        foreach ($educations as $edu) {
            $text .= $edu->degree . ' in ' . $edu->field_of_study . "\n";
            $text .= $edu->institution . "\n";
            $text .= $edu->start_date->format('Y') . ' - ' . ($edu->end_date ? $edu->end_date->format('Y') : 'Present') . "\n";

            if ($edu->grade) {
                $text .= 'GPA/Grade: ' . $edu->grade . "\n";
            }

            if ($edu->activities) {
                $text .= 'Activities: ' . $edu->activities . "\n";
            }

            $text .= "\n";
        }

        $text .= "\nSKILLS\n";
        $text .= str_repeat('=', 50) . "\n\n";

        $groupedSkills = $skills->groupBy('category');

        foreach ($groupedSkills as $category => $categorySkills) {
            $text .= ucfirst($category) . ': ' . $categorySkills->pluck('name')->join(', ') . "\n";
        }

        return $text;
    }

    public function generateMarkdown(Profile $profile, ?array $experiences = null, ?array $educations = null, ?array $skills = null): string
    {
        $experiences = $experiences ?? Experience::orderBy('start_date', 'desc')->get();
        $educations = $educations ?? Education::orderBy('start_date', 'desc')->get();
        $skills = $skills ?? Skill::all();

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

        if (!empty($contactInfo)) {
            $md .= implode(' | ', $contactInfo) . "\n\n";
        }

        if ($profile->biography) {
            $md .= "## Professional Summary\n\n";
            $md .= strip_tags($profile->biography) . "\n\n";
        }

        $md .= "## Experience\n\n";

        foreach ($experiences as $exp) {
            $md .= "### **{$exp->position}** - {$exp->company}\n";
            $md .= "*{$exp->location}* | {$exp->start_date->format('M Y')} - " . ($exp->end_date ? $exp->end_date->format('M Y') : 'Present') . "\n\n";

            if ($exp->description) {
                $md .= strip_tags($exp->description) . "\n\n";
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
            $md .= "{$edu->institution} | {$edu->start_date->format('Y')} - " . ($edu->end_date ? $edu->end_date->format('Y') : 'Present') . "\n\n";

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
            $md .= '**' . ucfirst($category) . '**: ' . $categorySkills->pluck('name')->join(', ') . "\n\n";
        }

        return $md;
    }

    public function buildCVContent(Profile $profile): string
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
            if ($skill->years_experience > 0) {
                $years = floatval($skill->years_experience);
                $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                $yearText = $years == 1 ? 'year' : 'years';
                $yearsExp = ' (' . $displayYears . ' ' . $yearText . ')';
            } else {
                $yearsExp = '';
            }
            $cvContent .= '- ' . $skill->name . ' (' . $skill->category . ')' . $yearsExp . "\n";
        }

        return $cvContent;
    }

    public function formatDate($date): string
    {
        if (!$date) {
            return 'Present';
        }

        return $date->format('M Y');
    }
}
