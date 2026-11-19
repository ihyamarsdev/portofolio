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
                TextColumn::make('image_url')
                    ->label('Image')
                    ->formatStateUsing(function (?string $state, $record): HtmlString {
                        $imageUrl = $state ?: $record->getFirstMediaUrl('images');

                        if (! $imageUrl) {
                            return new HtmlString('<span style="color: #9ca3af;">—</span>');
                        }

                        return new HtmlString('<img src="'.e($imageUrl).'" alt="'.e($record->title).'" style="height: 45px; width: 80px; object-fit: cover; border-radius: 8px; border: 1px solid rgb(51 65 85);" loading="lazy">');
                    }),

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
                    ->url(fn ($state) => $state ?: null, shouldOpenInNewTab: true)
                    ->icon(fn ($state) => $state ? 'heroicon-o-code-bracket' : null)
                    ->iconColor('info')
                    ->placeholder('—')
                    ->limit(30),

                TextColumn::make('live_url')
                    ->label('Live')
                    ->url(fn ($state) => $state ?: null, shouldOpenInNewTab: true)
                    ->icon(fn ($state) => $state ? 'heroicon-o-globe-alt' : null)
                    ->iconColor('success')
                    ->placeholder('—')
                    ->limit(30),

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
                    ->dateTime('M j, Y')
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
            ->emptyStateHeading('No projects yet')
            ->emptyStateDescription('Add your first project to showcase your work.')
            ->emptyStateIcon('heroicon-o-briefcase')
            ->defaultSort('order', 'asc');
    }
}
