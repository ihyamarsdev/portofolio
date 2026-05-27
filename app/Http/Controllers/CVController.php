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
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        $cvData = $this->cvService->buildCvData($profile);
        extract($cvData);

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

        return Pdf::loadView("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills'))
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
        $template = $request->query('template', 'ats');
        $profile = $this->getPrimaryProfile();

        if (! $profile) {
            return back()->with('error', 'Please create a profile first.');
        }

        return view("cv.{$template}", $this->cvService->buildCvData($profile));
    }

    protected function getPrimaryProfile(): ?Profile
    {
        return Profile::where('is_primary', true)->first();
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
        $html = View::make("cv.{$template}", compact('profile', 'experiences', 'educations', 'skills'))->render();

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
}
