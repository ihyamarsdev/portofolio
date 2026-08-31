<?php

namespace App\Filament\Widgets;

use App\Models\CVAnalysis;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LatestCvAnalysisWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $analysis = CVAnalysis::latest()->first();

        if ($analysis === null) {
            return [
                Stat::make('Latest CV Analysis', 'Not run yet')
                    ->description('Run an analysis from CV Builder → AI Job Match')
                    ->descriptionIcon('heroicon-m-sparkles')
                    ->color('gray'),
            ];
        }

        $detail = $analysis->job_match_detail;

        $stats = [
            Stat::make('Latest CV Analysis', $analysis->job_title_targeted ?? 'General CV Analysis')
                ->description('Run '.$analysis->created_at->format('M j, Y H:i'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];

        if (is_array($detail)) {
            $band = $detail['band']['label'] ?? 'N/A';
            $counts = $detail['counts'] ?? [];

            $stats[] = Stat::make('Job Match Score', ($analysis->job_match_score ?? 0).'%')
                ->description($band.' (formula '.($detail['formula_version'] ?? 'legacy').')')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($this->bandColor($detail['overall'] ?? 0));

            $stats[] = Stat::make('Must-Have Coverage', ($detail['must_have_coverage'] ?? 0).'%')
                ->description('Nice-to-have '.($detail['nice_to_have_coverage'] ?? 0).'%')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('cyan');

            $stats[] = Stat::make('Evidence Quality', ($detail['evidence_quality'] ?? 0).'%')
                ->description(sprintf(
                    '%d verified · %d claimed · %d missing',
                    $counts['verified'] ?? 0,
                    $counts['claimed'] ?? 0,
                    $counts['missing'] ?? 0
                ))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('emerald');
        } else {
            $stats[] = Stat::make('Job Match Score', $analysis->job_match_score !== null ? $analysis->job_match_score.'%' : 'N/A')
                ->description('Legacy run — no evidence breakdown stored')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('gray');
        }

        return $stats;
    }

    protected function bandColor(int $score): string
    {
        return match (true) {
            $score >= 85 => 'success',
            $score >= 70 => 'info',
            $score >= 50 => 'warning',
            default => 'danger',
        };
    }
}
