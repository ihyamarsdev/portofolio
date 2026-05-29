<?php

namespace App\Filament\Resources\Stacks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class StacksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->category) {
                            return '—';
                        }

                        $color = $record->category->color;

                        return new HtmlString(
                            '<span style="color: ' . $color . '; border: 1px solid ' . $color . '60; background: ' . $color . '10; box-shadow: 0 1px 3px ' . $color . '30; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block;">'
                            . e($state) .
                            '</span>'
                        );
                    }),
                ImageColumn::make('icon')
                    ->size(28)
                    ->circular(),
                TextColumn::make('skills_count')
                    ->counts('skills')
                    ->badge()
                    ->color('primary')
                    ->label('Skills')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
