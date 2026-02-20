<?php

namespace App\Filament\Resources\CVAnalyses;

use App\Models\CVAnalysis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class CVAnalysisResource extends Resource
{
    protected static ?string $model = CVAnalysis::class;

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('profile.name')
                    ->label('Profile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ats_score')
                    ->label('ATS Score')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('format_score')
                    ->label('Format Score')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('job_match_score')
                    ->label('Job Match')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn (?int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCVAnalyses::route('/'),
        ];
    }
}
