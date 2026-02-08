<?php

namespace App\Filament\Resources\CVAnalyses\Pages;

use App\Filament\Resources\CVAnalyses\CVAnalysisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCVAnalysis extends CreateRecord
{
    protected static string $resource = CVAnalysisResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
