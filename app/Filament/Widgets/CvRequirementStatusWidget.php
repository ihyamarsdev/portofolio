<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class CvRequirementStatusWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('cv-workspace.widgets.requirement_status.heading');
    }

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = '1/2';

    protected string $view = 'filament.widgets.cv-requirement-status';

    public function hasEvidence(): bool
    {
        $analysis = CVAnalysis::latest()->first();
        $counts = $analysis?->job_match_detail['counts'] ?? null;

        if ($counts === null) {
            return false;
        }

        $total = ($counts['verified'] ?? 0) + ($counts['claimed'] ?? 0) + ($counts['missing'] ?? 0);

        return $total > 0;
    }

    protected function getData(): array
    {
        $analysis = CVAnalysis::latest()->first();
        $counts = $analysis?->job_match_detail['counts'] ?? null;

        if ($counts === null) {
            return [
                'datasets' => [
                    [
                        'label' => __('cv-workspace.widgets.requirement_status.label'),
                        'data' => [0, 0, 0],
                        'backgroundColor' => ['#10b981', '#f59e0b', '#f43f5e'],
                    ],
                ],
                'labels' => [
                    __('cv-workspace.widgets.requirement_status.verified'),
                    __('cv-workspace.widgets.requirement_status.claimed'),
                    __('cv-workspace.widgets.requirement_status.missing'),
                ],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => __('cv-workspace.widgets.requirement_status.label'),
                    'data' => [
                        $counts['verified'] ?? 0,
                        $counts['claimed'] ?? 0,
                        $counts['missing'] ?? 0,
                    ],
                    'backgroundColor' => ['#10b981', '#f59e0b', '#f43f5e'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => [
                __('cv-workspace.widgets.requirement_status.verified'),
                __('cv-workspace.widgets.requirement_status.claimed'),
                __('cv-workspace.widgets.requirement_status.missing'),
            ],
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
