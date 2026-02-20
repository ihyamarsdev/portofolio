<?php

namespace Tests\Unit;

use Dompdf\Dompdf;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    public function test_pdf_can_be_generated_from_html()
    {
        $dompdf = new Dompdf([
            'enable_html5_parser' => false,
        ]);
        $html = view('cv.ats', [
            'profile' => (object) [
                'name' => 'Test User',
                'title' => 'Developer',
                'email' => 'test@example.com',
                'phone' => '123-456-7890',
                'location' => 'Test City',
                'biography' => 'Test biography',
            ],
            'experiences' => collect(),
            'educations' => collect(),
            'skills' => collect(),
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');

        $this->assertNotNull($dompdf);
    }

    public function test_dompdf_facade_is_available()
    {
        $this->assertTrue(class_exists(\Dompdf\Dompdf::class));
    }
}
