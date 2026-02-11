<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class FormatScoreChartWidget extends ChartWidget
{
    protected ?string $heading = 'Format Score Trend';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $analyses = CVAnalysis::query()
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get();

        if ($analyses->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Format Score',
                        'data' => [],
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
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
                    'label' => 'Format Score',
                    'data' => $analyses->pluck('format_score')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
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
