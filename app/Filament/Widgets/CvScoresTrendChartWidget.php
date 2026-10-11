<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class CvScoresTrendChartWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return __('cv-workspace.widgets.scores_trend.heading');
    }

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'all' => __('cv-workspace.widgets.scores_trend.filters.all'),
            'today' => __('cv-workspace.widgets.scores_trend.filters.today'),
            'week' => __('cv-workspace.widgets.scores_trend.filters.week'),
            'month' => __('cv-workspace.widgets.scores_trend.filters.month'),
            'year' => __('cv-workspace.widgets.scores_trend.filters.year'),
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
        $labels = $this->formatTrendLabels($analyses);

        return [
            'datasets' => [
                $this->dataset(__('cv-workspace.widgets.scores_trend.datasets.ats'), $analyses->pluck('ats_score'), '#06b6d4', 'rgba(6, 182, 212, 0.1)'),
                $this->dataset(__('cv-workspace.widgets.scores_trend.datasets.format'), $analyses->pluck('format_score'), '#3b82f6', 'rgba(59, 130, 246, 0.08)'),
                $this->dataset(__('cv-workspace.widgets.scores_trend.datasets.about_me'), $analyses->pluck('about_me_score'), '#8b5cf6', 'rgba(139, 92, 246, 0.08)'),
                $this->dataset(__('cv-workspace.widgets.scores_trend.datasets.job_match'), $analyses->pluck('job_match_score'), '#f59e0b', 'rgba(245, 158, 11, 0.1)'),
            ],
            'labels' => $labels,
        ];
    }

    /**
     * Format trend labels so identical labels do not appear on both ends
     * when runs share a day, preserving chronological order.
     *
     * @param  Collection<int, CVAnalysis>  $analyses
     * @return array<int, string>
     */
    public function formatTrendLabels($analyses): array
    {
        $count = $analyses->count();
        if ($count === 0) {
            return [];
        }

        if ($count === 1) {
            return [$analyses->first()->created_at->format('M j, Y H:i')];
        }

        $firstDate = $analyses->first()->created_at->format('Y-m-d');
        $allSameDay = $analyses->every(fn (CVAnalysis $a) => $a->created_at->format('Y-m-d') === $firstDate);

        if ($allSameDay) {
            return $analyses->values()->map(function (CVAnalysis $a, int $i) {
                return 'Run '.($i + 1).' ('.$a->created_at->format('H:i').')';
            })->toArray();
        }

        $dateStrings = $analyses->map(fn (CVAnalysis $a) => $a->created_at->format('M j, Y'));
        if ($dateStrings->count() !== $dateStrings->unique()->count()) {
            return $analyses->map(fn (CVAnalysis $a) => $a->created_at->format('M j, H:i'))->toArray();
        }

        return $dateStrings->toArray();
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
