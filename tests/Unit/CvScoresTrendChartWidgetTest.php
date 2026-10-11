<?php

namespace Tests\Unit;

use App\Filament\Widgets\CvScoresTrendChartWidget;
use App\Models\CVAnalysis;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CvScoresTrendChartWidgetTest extends TestCase
{
    public function test_format_trend_labels_empty(): void
    {
        $widget = new CvScoresTrendChartWidget;
        $this->assertSame([], $widget->formatTrendLabels(collect([])));
    }

    public function test_format_trend_labels_single_run_includes_date_and_time(): void
    {
        $widget = new CvScoresTrendChartWidget;
        $run = new CVAnalysis(['created_at' => Carbon::parse('2026-10-11 14:30:00')]);
        $run->created_at = Carbon::parse('2026-10-11 14:30:00');

        $labels = $widget->formatTrendLabels(collect([$run]));

        $this->assertSame(['Oct 11, 2026 14:30'], $labels);
    }

    public function test_format_trend_labels_same_day_uses_run_ordinal_and_time(): void
    {
        $widget = new CvScoresTrendChartWidget;
        $run1 = new CVAnalysis;
        $run1->created_at = Carbon::parse('2026-10-11 09:15:00');
        $run2 = new CVAnalysis;
        $run2->created_at = Carbon::parse('2026-10-11 16:45:00');

        $labels = $widget->formatTrendLabels(collect([$run1, $run2]));

        $this->assertSame(['Run 1 (09:15)', 'Run 2 (16:45)'], $labels);
    }

    public function test_format_trend_labels_multi_day_distinct_dates(): void
    {
        $widget = new CvScoresTrendChartWidget;
        $run1 = new CVAnalysis;
        $run1->created_at = Carbon::parse('2026-10-09 10:00:00');
        $run2 = new CVAnalysis;
        $run2->created_at = Carbon::parse('2026-10-11 12:00:00');

        $labels = $widget->formatTrendLabels(collect([$run1, $run2]));

        $this->assertSame(['Oct 9, 2026', 'Oct 11, 2026'], $labels);
    }

    public function test_format_trend_labels_multi_day_with_duplicate_day_includes_time(): void
    {
        $widget = new CvScoresTrendChartWidget;
        $run1 = new CVAnalysis;
        $run1->created_at = Carbon::parse('2026-10-09 10:00:00');
        $run2 = new CVAnalysis;
        $run2->created_at = Carbon::parse('2026-10-09 15:30:00');
        $run3 = new CVAnalysis;
        $run3->created_at = Carbon::parse('2026-10-11 12:00:00');

        $labels = $widget->formatTrendLabels(collect([$run1, $run2, $run3]));

        $this->assertSame(['Oct 9, 10:00', 'Oct 9, 15:30', 'Oct 11, 12:00'], $labels);
    }
}
