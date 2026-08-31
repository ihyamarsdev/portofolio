<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class CvRequirementStatusWidget extends ChartWidget
{
    protected ?string $heading = 'Latest Run — Requirement Evidence';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $analysis = CVAnalysis::latest()->first();
        $counts = $analysis?->job_match_detail['counts'] ?? null;

        if ($counts === null) {
            return [
                'datasets' => [
                    [
                        'label' => 'Requirements',
                        'data' => [0, 0, 0],
                        'backgroundColor' => ['#10b981', '#f59e0b', '#f43f5e'],
                    ],
                ],
                'labels' => ['Verified', 'Claimed', 'Missing'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Requirements',
                    'data' => [
                        $counts['verified'] ?? 0,
                        $counts['claimed'] ?? 0,
                        $counts['missing'] ?? 0,
                    ],
                    'backgroundColor' => ['#10b981', '#f59e0b', '#f43f5e'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Verified', 'Claimed', 'Missing'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
