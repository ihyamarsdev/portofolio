<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Services\CVGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

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
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $cvData = $this->cvService->buildCvData($profile);
        extract($cvData);

        $filename = 'cv-'.str()->slug($profile->name ?? 'candidate').'.pdf';

        return Pdf::loadView("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills') + ['isPdf' => true])
            ->setPaper('a4')
            ->download($filename);
    }

    public function downloadDOCX(Request $request)
    {
        $template = $request->query('template', 'ats');
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $docxContent = $this->cvService->generateDOCX($template, $profile);

        return response($docxContent)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->header('Content-Disposition', 'attachment; filename="cv-'.($profile->name ?? 'ats').'.docx"');
    }

    public function downloadText()
    {
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $text = $this->cvService->generateText($profile);

        return response($text)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="cv-'.($profile->name ?? 'ats').'.txt"');
    }

    public function downloadMarkdown()
    {
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $markdown = $this->cvService->generateMarkdown($profile);

        return response($markdown)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="cv-'.($profile->name ?? 'ats').'.md"');
    }

    public function preview(Request $request)
    {
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $template = $this->resolveTemplate($request, $profile);

        return view("cv.{$template}", $this->cvService->buildCvData($profile) + ['isPdf' => false]);
    }

    protected function resolveTemplate(Request $request, Profile $profile): string
    {
        $template = $request->query('template') ?: $profile->cv_template ?: 'ats';

        if (! array_key_exists($template, config('cv-templates', []))) {
            return 'ats';
        }

        return $template;
    }

    protected function getPrimaryProfile(): ?Profile
    {
        return Profile::where('is_primary', true)->first();
    }
}
