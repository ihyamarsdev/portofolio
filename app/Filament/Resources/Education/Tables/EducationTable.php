<?php

namespace App\Filament\Resources\Education\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EducationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('degree')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('institution')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('field_of_study')
                    ->label('Major')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date('Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date('Y')
                    ->placeholder('Present')
                    ->sortable(),
                TextColumn::make('grade')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('order')
                    ->sortable(),
            ])
            ->filters([
                //
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
            ->defaultSort('order', 'asc');
    }
}
