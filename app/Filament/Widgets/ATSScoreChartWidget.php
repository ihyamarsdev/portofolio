<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class ATSScoreChartWidget extends ChartWidget
{
    protected ?string $heading = 'ATS Score Trend';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = '1/2';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $query = CVAnalysis::query()->orderBy('created_at', 'asc');

        // Apply filter based on selected period
        match ($this->filter) {
            'today' => $query->whereDate('created_at', today()),
            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),
            'year' => $query->whereYear('created_at', now()->year),
            default => $query->take(30),
        };

        $analyses = $query->get();

        if ($analyses->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'ATS Score',
                        'data' => [],
                        'borderColor' => '#10b981',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
                    'label' => 'ATS Score',
                    'data' => $analyses->pluck('ats_score')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
