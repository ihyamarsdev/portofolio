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
                Stat::make(__('cv-workspace.widgets.latest_analysis.title'), __('cv-workspace.widgets.latest_analysis.not_run'))
                    ->description(__('cv-workspace.widgets.latest_analysis.not_run_desc'))
                    ->color('gray'),
            ];
        }

        $detail = $analysis->job_match_detail;

        $stats = [
            Stat::make(__('cv-workspace.widgets.latest_analysis.title'), $analysis->job_title_targeted ?? __('cv-workspace.widgets.latest_analysis.general_title'))
                ->description(__('cv-workspace.widgets.latest_analysis.run_at', ['date' => $analysis->created_at->translatedFormat('M j, Y H:i')]))
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];

        if (is_array($detail)) {
            $band = $detail['band']['label'] ?? 'N/A';
            $counts = $detail['counts'] ?? [];

            $stats[] = Stat::make(__('cv-workspace.widgets.latest_analysis.job_match_score'), ($analysis->job_match_score ?? 0).'%')
                ->description(__('cv-workspace.widgets.latest_analysis.formula_suffix', ['band' => $band, 'version' => $detail['formula_version'] ?? 'legacy']))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($this->bandColor($detail['overall'] ?? 0));

            $stats[] = Stat::make(__('cv-workspace.widgets.latest_analysis.must_have_coverage'), ($detail['must_have_coverage'] ?? 0).'%')
                ->description(__('cv-workspace.widgets.latest_analysis.nice_to_have', ['pct' => $detail['nice_to_have_coverage'] ?? 0]))
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('cyan');

            $stats[] = Stat::make(__('cv-workspace.widgets.latest_analysis.evidence_quality'), ($detail['evidence_quality'] ?? 0).'%')
                ->description(__('cv-workspace.widgets.latest_analysis.evidence_summary', [
                    'verified' => $counts['verified'] ?? 0,
                    'claimed' => $counts['claimed'] ?? 0,
                    'missing' => $counts['missing'] ?? 0,
                ]))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('emerald');
        } else {
            $stats[] = Stat::make(__('cv-workspace.widgets.latest_analysis.job_match_score'), $analysis->job_match_score !== null ? $analysis->job_match_score.'%' : 'N/A')
                ->description(__('cv-workspace.widgets.latest_analysis.legacy_desc'))
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
