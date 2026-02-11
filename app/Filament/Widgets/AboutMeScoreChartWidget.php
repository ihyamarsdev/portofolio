<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class AboutMeScoreChartWidget extends ChartWidget
{
    protected ?string $heading = 'About Me Score Trend';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $analyses = CVAnalysis::query()
            ->whereNotNull('about_me_score')
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get();

        if ($analyses->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'About Me Score',
                        'data' => [],
                        'borderColor' => '#8b5cf6',
                        'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
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
                    'label' => 'About Me Score',
                    'data' => $analyses->pluck('about_me_score')->toArray(),
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
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
