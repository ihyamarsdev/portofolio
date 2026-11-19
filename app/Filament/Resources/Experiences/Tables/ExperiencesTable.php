<?php

namespace App\Filament\Resources\Experiences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ExperiencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('position')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date('M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date('M Y')
                    ->placeholder('Present')
                    ->sortable(),
                TextColumn::make('location'),
                TextColumn::make('order')
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->label('Di CV'),
            ])
            ->filters([
                TernaryFilter::make('is_featured')
                    ->label('Tampil di CV'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No experiences yet')
            ->emptyStateDescription('Add your work experience to build your career history.')
            ->emptyStateIcon('heroicon-o-briefcase')
            ->defaultSort('order', 'asc');
    }
}
