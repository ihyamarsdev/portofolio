<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Services\CVGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class CVController extends Controller
{
    protected CVGeneratorService $cvService;

    protected ?bool $browsershotAvailable = null;

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

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $filename = 'cv-'.str()->slug($profile->name ?? 'candidate').'.pdf';

        if ($this->isBrowsershotAvailable()) {
            try {
                return $this->downloadWithBrowsershot(
                    $template,
                    $profile,
                    $experiences,
                    $educations,
                    $skills,
                    $filename
                );
            } catch (\Throwable $exception) {
                Log::warning('Browsershot PDF generation failed, falling back to DomPDF.', [
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return Pdf::loadView(
            "cv.{$template}",
            compact('profile', 'experiences', 'educations', 'skills'),
        )
            ->setPaper('a4')
            ->download($filename);
    }

    protected function isBrowsershotAvailable(): bool
    {
        if ($this->browsershotAvailable !== null) {
            return $this->browsershotAvailable;
        }

        return Cache::remember('browsershot_available', 300, function () {
            try {
                Browsershot::html('<html><body>test</body></html>')
                    ->noSandbox()
                    ->timeout(10)
                    ->bodyHtml();

                $this->browsershotAvailable = true;

                return true;
            } catch (\Throwable) {
                $this->browsershotAvailable = false;

                return false;
            }
        });
    }

    protected function downloadWithBrowsershot(
        string $template,
        Profile $profile,
        $experiences,
        $educations,
        $skills,
        string $filename
    ): Response {
        $html = View::make(
            "cv.{$template}",
            compact('profile', 'experiences', 'educations', 'skills')
        )->render();

        $pdf = Browsershot::html($html)
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->timeout(60)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function downloadDOCX(Request $request)
    {
        $template = $request->query('template', 'ats');
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $docxContent = $this->cvService->generateDOCX(
            $template,
            $profile,
            $experiences,
            $educations,
            $skills,
        );

        return response($docxContent)
            ->header(
                'Content-Type',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="cv-'.
                    ($profile->name ?? 'ats').
                    '.docx"',
            );
    }

    public function downloadText()
    {
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $text = $this->cvService->generateText(
            $profile,
            $experiences,
            $educations,
            $skills,
        );

        return response($text)
            ->header('Content-Type', 'text/plain')
            ->header(
                'Content-Disposition',
                'attachment; filename="cv-'.
                    ($profile->name ?? 'ats').
                    '.txt"',
            );
    }

    public function downloadMarkdown()
    {
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        $markdown = $this->cvService->generateMarkdown(
            $profile,
            $experiences,
            $educations,
            $skills,
        );

        return response($markdown)
            ->header('Content-Type', 'text/markdown')
            ->header(
                'Content-Disposition',
                'attachment; filename="cv-'.
                    ($profile->name ?? 'ats').
                    '.md"',
            );
    }

    public function preview(Request $request)
    {
        $template = $request->query('template', 'ats');
        $profile = Profile::where('is_primary', true)->first();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $educations = Education::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        return view(
            "cv.{$template}",
            compact('profile', 'experiences', 'educations', 'skills'),
        );
    }
}
