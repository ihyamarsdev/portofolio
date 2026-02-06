<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Services\CVGeneratorService;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;

class CVController extends Controller
{
    protected CVGeneratorService $cvService;

    public function __construct(CVGeneratorService $cvService)
    {
        $this->cvService = $cvService;
    }

    public function download(Request $request)
    {
        $template = $request->query('template', 'ats');
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        if (!$profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        return Pdf::view("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills'))
            ->format('a4')
            ->name('cv-' . ($profile->name ?? 'ats') . '.pdf');
    }

    public function downloadDOCX(Request $request)
    {
        $template = $request->query('template', 'ats');
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $docxContent = $this->cvService->generateDOCX($template, $profile, $experiences, $educations, $skills);

        return response($docxContent)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', 'attachment; filename="cv-' . ($profile->name ?? 'ats') . '.docx"');
    }

    public function downloadText()
    {
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $text = $this->cvService->generateText($profile, $experiences, $educations, $skills);

        return response($text)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="cv-' . ($profile->name ?? 'ats') . '.txt"');
    }

    public function downloadMarkdown()
    {
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $markdown = $this->cvService->generateMarkdown($profile, $experiences, $educations, $skills);

        return response($markdown)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="cv-' . ($profile->name ?? 'ats') . '.md"');
    }

    public function preview(Request $request)
    {
        $template = $request->query('template', 'ats');
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        return view("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills'));
    }
}
