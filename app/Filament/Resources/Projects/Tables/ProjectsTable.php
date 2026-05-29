<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string => Str::limit($record->description ?? '', 60)),

                TextColumn::make('slug')
                    ->searchable()
                    ->fontFamily('mono'),

                TextColumn::make('repository_url')
                    ->label('Repo')
                    ->formatStateUsing(function (?string $state): HtmlString {
                        if (! $state) {
                            return new HtmlString('<span style="color: #9ca3af;">—</span>');
                        }

                        return new HtmlString(
                            '<a href="' . e($state) . '" target="_blank" rel="noopener noreferrer" style="color: #6366f1; display: inline-flex; align-items: center; gap: 4px;">'
                            . '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #6366f1;"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path><path d="M9 18c-4.51 2-5-2-7-2"></path></svg>'
                            . '</a>'
                        );
                    }),

                TextColumn::make('live_url')
                    ->label('Live')
                    ->formatStateUsing(function (?string $state): HtmlString {
                        if (! $state) {
                            return new HtmlString('<span style="color: #9ca3af;">—</span>');
                        }

                        return new HtmlString(
                            '<a href="' . e($state) . '" target="_blank" rel="noopener noreferrer" style="color: #10b981; display: inline-flex; align-items: center; gap: 4px;">'
                            . '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #10b981;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>'
                            . '</a>'
                        );
                    }),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('skills_count')
                    ->counts('skills')
                    ->badge()
                    ->color('info')
                    ->label('Skills')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_featured'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }
}
