<?php

namespace App\Filament\Resources\CVAnalyses;

use App\Filament\Resources\CVAnalyses\Pages\CreateCVAnalysis;
use App\Filament\Resources\CVAnalyses\Pages\EditCVAnalysis;
use App\Filament\Resources\CVAnalyses\Pages\ListCVAnalyses;
use App\Filament\Resources\CVAnalyses\Schemas\CVAnalysisForm;
use App\Filament\Resources\CVAnalyses\Tables\CVAnalysesTable;
use App\Models\CVAnalysis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CVAnalysisResource extends Resource
{
    protected static ?string $model = CVAnalysis::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'cv-analyses';

    public static function getModelLabel(): string
    {
        return 'CV Analysis';
    }

    public static function getPluralModelLabel(): string
    {
        return 'CV Analyses';
    }

    public static function form(Schema $schema): Schema
    {
        return CVAnalysisForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CVAnalysesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCVAnalyses::route('/'),
            'create' => CreateCVAnalysis::route('/create'),
            'edit' => EditCVAnalysis::route('/{record}/edit'),
        ];
    }
}
