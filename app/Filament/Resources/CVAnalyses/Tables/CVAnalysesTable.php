<?php

namespace App\Filament\Resources\CVAnalyses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CVAnalysesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('profile.name')
                    ->label('Profile')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ats_score')
                    ->label('ATS')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),
                TextColumn::make('format_score')
                    ->label('Format')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),
                TextColumn::make('impact_score')
                    ->label('Impact')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('security_score')
                    ->label('Security')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('reliability_score')
                    ->label('Reliability')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('cloud_score')
                    ->label('Cloud')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('job_match_score')
                    ->label('Job Match')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('target_seniority')
                    ->label('Seniority')
                    ->placeholder('-')
                    ->badge(),
                TextColumn::make('job_title_targeted')
                    ->label('Target Role')
                    ->placeholder('-')
                    ->limit(30)
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('profile')
                    ->relationship('profile', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No analyses yet')
            ->emptyStateDescription('Analyze a CV to see ATS insights and scores here.')
            ->emptyStateIcon('heroicon-o-document-chart-bar')
            ->defaultSort('created_at', 'desc');
    }
}
