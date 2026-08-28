<?php

namespace App\Filament\Resources\Skills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('proficiency')
                    ->label('Proficiency')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => '1 · Beginner',
                        2 => '2 · Developing',
                        3 => '3 · Intermediate',
                        4 => '4 · Advanced',
                        5 => '5 · Expert',
                        default => (string) $state,
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1, 2 => 'gray',
                        3 => 'warning',
                        4, 5 => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('years_experience')
                    ->label('Years')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stacks.name')
                    ->label('Tech Stacks')
                    ->badge()
                    ->color('primary')
                    ->separator(', ')
                    ->searchable(),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with(['stacks', 'category']))
            ->filters([
                SelectFilter::make('proficiency')
                    ->label('Proficiency')
                    ->options([
                        1 => '1 — Beginner',
                        2 => '2 — Developing',
                        3 => '3 — Intermediate',
                        4 => '4 — Advanced',
                        5 => '5 — Expert',
                    ]),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
