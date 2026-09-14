<?php

namespace App\Filament\Resources\CVAnalyses\Pages;

use App\Filament\Resources\CVAnalyses\CVAnalysisResource;
use App\Filament\Widgets\CvCoverageRadarWidget;
use App\Filament\Widgets\CvRequirementStatusWidget;
use App\Filament\Widgets\CvScoresTrendChartWidget;
use App\Filament\Widgets\LatestCvAnalysisWidget;
use Filament\Resources\Pages\ListRecords;

class ListCVAnalyses extends ListRecords
{
    protected static string $resource = CVAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LatestCvAnalysisWidget::class,
            CvScoresTrendChartWidget::class,
            CvRequirementStatusWidget::class,
            CvCoverageRadarWidget::class,
        ];
    }
}
