<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class CvCoverageRadarWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('cv-workspace.widgets.coverage_radar.heading');
    }

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $analyses = CVAnalysis::latest()
            ->take(5)
            ->get()
            ->reverse()
            ->values();

        $colors = [
            ['#06b6d4', 'rgba(6, 182, 212, 0.15)'],
            ['#8b5cf6', 'rgba(139, 92, 246, 0.15)'],
            ['#f59e0b', 'rgba(245, 158, 11, 0.15)'],
            ['#10b981', 'rgba(16, 185, 129, 0.15)'],
            ['#f43f5e', 'rgba(244, 63, 94, 0.15)'],
        ];

        $datasets = $analyses->map(function (CVAnalysis $analysis, int $index) use ($colors) {
            $detail = $analysis->job_match_detail;

            return [
                'label' => $analysis->created_at->format('M j'),
                'data' => $detail ? [
                    $detail['must_have_coverage'] ?? 0,
                    $detail['nice_to_have_coverage'] ?? 0,
                    $detail['evidence_quality'] ?? 0,
                ] : [0, 0, 0],
                'backgroundColor' => $colors[$index % count($colors)][1],
                'borderColor' => $colors[$index % count($colors)][0],
                'pointBackgroundColor' => $colors[$index % count($colors)][0],
                'borderWidth' => 2,
            ];
        })->toArray();

        return [
            'datasets' => $datasets,
            'labels' => [
                __('cv-workspace.widgets.coverage_radar.labels.must_have'),
                __('cv-workspace.widgets.coverage_radar.labels.nice_to_have'),
                __('cv-workspace.widgets.coverage_radar.labels.evidence_quality'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'r' => [
                    'beginAtZero' => true,
                    'max' => 100,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
