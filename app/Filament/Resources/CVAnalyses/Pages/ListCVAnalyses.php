<?php

namespace App\Filament\Resources\CVAnalyses\Pages;

use App\Filament\Resources\CVAnalyses\CVAnalysisResource;
use Filament\Resources\Pages\ListRecords;

class ListCVAnalyses extends ListRecords
{
    protected static string $resource = CVAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
