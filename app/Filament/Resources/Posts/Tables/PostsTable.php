<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('featured_image_url')
                    ->label('Image')
                    ->formatStateUsing(function (?string $state, $record): HtmlString {
                        $imageUrl = $state ?: $record->getFirstMediaUrl('featured_image');

                        if (! $imageUrl) {
                            return new HtmlString('<span style="color: #9ca3af;">—</span>');
                        }

                        return new HtmlString('<img src="'.e($imageUrl).'" alt="'.e($record->title).'" style="height: 45px; width: 80px; object-fit: cover; border-radius: 8px; border: 1px solid rgb(51 65 85);" loading="lazy">');
                    }),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(45)
                    ->description(fn ($record) => Str::limit($record->excerpt, 60)),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->category ? new HtmlString(
                        '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border bg-slate-900/50 shrink-0" style="color: '.e($record->category->color).'; border-color: '.e($record->category->color).'60; box-shadow: 0 0 6px '.e($record->category->color).'30">
                            '.e($record->category->name).'
                         </span>'
                    ) : new HtmlString('<span class="text-slate-500">-</span>')),

                TextColumn::make('reading_specs')
                    ->label('Specs')
                    ->state(function ($record) {
                        $wordCount = str_word_count(strip_tags($record->content ?? ''));
                        $readTime = max(1, (int) ceil($wordCount / 200));

                        return "{$wordCount} w • {$readTime}m read";
                    })
                    ->badge()
                    ->color('gray')
                    ->fontFamily('mono')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'published' => 'success',
                        'archived' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'draft' => 'heroicon-o-pencil',
                        'pending' => 'heroicon-o-clock',
                        'published' => 'heroicon-o-check-circle',
                        'archived' => 'heroicon-o-archive-box',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending Review',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),

                SelectFilter::make('category')
                    ->relationship('category', 'name')
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
