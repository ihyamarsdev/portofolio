<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record): array => Color::hex($record->color ?? '#6366f1')),
                TextColumn::make('slug')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->counts('posts')
                    ->badge()
                    ->color('primary')
                    ->label('Blog Posts')
                    ->sortable(),
                TextColumn::make('skills_count')
                    ->counts('skills')
                    ->badge()
                    ->color('info')
                    ->label('Linked Skills')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No categories yet')
            ->emptyStateDescription('Create a category to organize your content.')
            ->emptyStateIcon('heroicon-o-tag')
            ->defaultSort('name', 'asc');
    }
}
