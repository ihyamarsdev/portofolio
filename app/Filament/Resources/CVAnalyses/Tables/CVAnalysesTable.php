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
                    ->label('ATS Score')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),
                TextColumn::make('format_score')
                    ->label('Format Score')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger')),
                TextColumn::make('about_me_score')
                    ->label('About Me')
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
            ->defaultSort('created_at', 'desc');
    }
}
