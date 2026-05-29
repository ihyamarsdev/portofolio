<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => new \Illuminate\Support\HtmlString(
                        '<div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full border border-slate-700 shadow-sm shrink-0" style="background-color: ' . e($record->color ?? '#06b6d4') . '; box-shadow: 0 0 8px ' . e($record->color ?? '#06b6d4') . '"></span>
                            <span class="font-semibold text-white">' . e($record->name) . '</span>
                         </div>'
                    )),
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
            ]);
    }
}
