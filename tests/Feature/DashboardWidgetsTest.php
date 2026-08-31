<?php

namespace Tests\Feature;

use App\Filament\Widgets\CvCoverageRadarWidget;
use App\Filament\Widgets\CvRequirementStatusWidget;
use App\Filament\Widgets\CvScoresTrendChartWidget;
use App\Filament\Widgets\LatestCvAnalysisWidget;
use App\Filament\Widgets\StatsOverview;
use App\Models\CVAnalysis;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_overview_renders(): void
    {
        Livewire::test(StatsOverview::class)
            ->assertSuccessful()
            ->assertSee('Total Projects')
            ->assertSee('Unread Messages');
    }

    public function test_latest_cv_analysis_widget_shows_placeholder_without_runs(): void
    {
        Livewire::test(LatestCvAnalysisWidget::class)
            ->assertSuccessful()
            ->assertSee('Not run yet');
    }

    public function test_latest_cv_analysis_widget_renders_evidence_stats(): void
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 88,
            'format_score' => 84,
            'job_title_targeted' => 'DevOps Engineer',
            'job_match_score' => 74,
            'job_match_detail' => [
                'formula_version' => 'v2-evidence-based',
                'overall' => 74,
                'band' => ['label' => 'Good Match', 'definition' => 'test'],
                'must_have_coverage' => 80,
                'nice_to_have_coverage' => 60,
                'evidence_quality' => 67,
                'counts' => ['verified' => 3, 'claimed' => 1, 'missing' => 1],
            ],
        ]);

        Livewire::test(LatestCvAnalysisWidget::class)
            ->assertSuccessful()
            ->assertSee('DevOps Engineer')
            ->assertSee('74%')
            ->assertSee('Good Match')
            ->assertSee('3 verified · 1 claimed · 1 missing');
    }

    public function test_scores_trend_chart_renders(): void
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        CVAnalysis::create([
            'profile_id' => $profile->id,
            'ats_score' => 80,
            'format_score' => 75,
        ]);

        Livewire::test(CvScoresTrendChartWidget::class)
            ->assertSuccessful()
            ->assertSee('ATS Score')
            ->assertSee('Job Match');
    }

    public function test_requirement_status_chart_renders(): void
    {
        Livewire::test(CvRequirementStatusWidget::class)->assertSuccessful();
    }

    public function test_coverage_radar_chart_renders(): void
    {
        Livewire::test(CvCoverageRadarWidget::class)->assertSuccessful();
    }
}
