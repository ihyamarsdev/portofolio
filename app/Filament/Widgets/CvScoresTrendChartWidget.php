<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;

class CvScoresTrendChartWidget extends ChartWidget
{
    protected ?string $heading = 'CV Scores Trend';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All Time',
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $query = CVAnalysis::query()->orderBy('created_at', 'asc');

        match ($this->filter) {
            'today' => $query->whereDate('created_at', today()),
            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year),
            'year' => $query->whereYear('created_at', now()->year),
            default => null,
        };

        $analyses = $query->get();

        $labels = $analyses->map(fn (CVAnalysis $analysis) => $analysis->created_at->format('M j, Y'));

        return [
            'datasets' => [
                $this->dataset('ATS Score', $analyses->pluck('ats_score'), '#06b6d4', 'rgba(6, 182, 212, 0.1)'),
                $this->dataset('Format', $analyses->pluck('format_score'), '#3b82f6', 'rgba(59, 130, 246, 0.08)'),
                $this->dataset('About Me', $analyses->pluck('about_me_score'), '#8b5cf6', 'rgba(139, 92, 246, 0.08)'),
                $this->dataset('Job Match', $analyses->pluck('job_match_score'), '#f59e0b', 'rgba(245, 158, 11, 0.1)'),
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function dataset(string $label, $values, string $border, string $background): array
    {
        return [
            'label' => $label,
            'data' => $values->map(fn ($score) => $score ?? 0)->toArray(),
            'borderColor' => $border,
            'backgroundColor' => $background,
            'fill' => false,
            'tension' => 0.4,
            'spanGaps' => true,
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
