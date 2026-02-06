<?php

namespace App\Filament\Resources\CVAnalyses\Pages;

use App\Filament\Resources\CVAnalyses\CVAnalysisResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCVAnalysis extends EditRecord
{
    protected static string $resource = CVAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
