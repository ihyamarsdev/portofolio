<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class JobMatchChartWidget extends ChartWidget
{
    protected ?string $heading = 'Job Match Score Trend';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $analyses = CVAnalysis::query()
            ->whereNotNull('job_match_score')
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get();

        if ($analyses->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Job Match Score',
                        'data' => [],
                        'borderColor' => '#f59e0b',
                        'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => [],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Job Match Score',
                    'data' => $analyses->pluck('job_match_score')->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $analyses->map(function ($analysis) {
                return $analysis->created_at->format('M j, Y');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'stepSize' => 10,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
