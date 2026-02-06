<?php

namespace Tests\Feature;

use App\Models\CVAnalysis;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CVAnalysisTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_analysis_can_be_created()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        $analysis = CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 85,
            'format_score' => 90,
            'keywords_found' => ['DevOps', 'Docker', 'AWS'],
            'keywords_missing' => ['Kubernetes'],
            'suggestions' => ['Add Kubernetes experience'],
            'industry_match' => ['devops' => 90, 'cloud' => 85],
            'summary' => 'Good CV overall',
        ]);

        $this->assertDatabaseHas('cv_analyses', [
            'profile_id' => $profile->id,
            'ats_score' => 85,
        ]);
    }

    public function test_profile_has_cv_analyses_relationship()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 75,
            'format_score' => 80,
        ]);

        CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 85,
            'format_score' => 90,
        ]);

        $this->assertCount(2, $profile->cvAnalyses);
    }

    public function test_latest_analysis_returns_correct_record()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        $analysis1 = CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 75,
            'format_score' => 80,
            'created_at' => now()->subHours(2),
        ]);

        $analysis2 = CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 85,
            'format_score' => 90,
            'created_at' => now(),
        ]);

        $latestAnalysis = $profile->latestAnalysis;

        $this->assertEquals($analysis2->id, $latestAnalysis->id);
        $this->assertEquals(85, $latestAnalysis->ats_score);
    }

    public function test_cv_can_be_downloaded_as_pdf()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);
        Experience::factory()->create(['profile_id' => $profile->id]);
        Education::factory()->create();
        Skill::factory()->create();

        $response = $this->get(route('cv.download'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_cv_can_be_downloaded_with_template()
    {
        $profile = Profile::factory()->create(['is_primary' => true, 'cv_template' => 'modern']);
        Experience::factory()->create(['profile_id' => $profile->id]);
        Education::factory()->create();
        Skill::factory()->create();

        $response = $this->get(route('cv.download', ['template' => 'modern']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_text_export_works()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);
        Experience::factory()->create(['profile_id' => $profile->id]);
        Education::factory()->create();
        Skill::factory()->create();

        $response = $this->get(route('cv.download.text'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/plain');
    }

    public function test_markdown_export_works()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);
        Experience::factory()->create(['profile_id' => $profile->id]);
        Education::factory()->create();
        Skill::factory()->create();

        $response = $this->get(route('cv.download.markdown'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/markdown');
    }

    public function test_docx_export_works()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);
        Experience::factory()->create(['profile_id' => $profile->id]);
        Education::factory()->create();
        Skill::factory()->create();

        $response = $this->get(route('cv.download.docx'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    }

    public function test_preview_route_works()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);
        Experience::factory()->create(['profile_id' => $profile->id]);
        Education::factory()->create();
        Skill::factory()->create();

        $response = $this->get(route('cv.preview', ['template' => 'ats']));

        $response->assertStatus(200);
    }

    public function test_cv_analysis_json_casts_work()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        $analysis = CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 85,
            'format_score' => 90,
            'keywords_found' => ['DevOps', 'Docker', 'AWS'],
            'keywords_missing' => ['Kubernetes'],
            'suggestions' => ['Add Kubernetes experience'],
            'industry_match' => ['devops' => 90, 'cloud' => 85],
        ]);

        $this->assertIsArray($analysis->keywords_found);
        $this->assertIsArray($analysis->keywords_missing);
        $this->assertIsArray($analysis->suggestions);
        $this->assertIsArray($analysis->industry_match);

        $this->assertEquals(['DevOps', 'Docker', 'AWS'], $analysis->keywords_found);
        $this->assertEquals(['Kubernetes'], $analysis->keywords_missing);
    }

    public function test_cv_analysis_integer_casts_work()
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        $analysis = CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => '85',
            'format_score' => '90',
        ]);

        $this->assertIsInt($analysis->ats_score);
        $this->assertIsInt($analysis->format_score);
        $this->assertEquals(85, $analysis->ats_score);
        $this->assertEquals(90, $analysis->format_score);
    }
}
