<?php

namespace App\Filament\Resources\Inboxes\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class InboxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('is_read')
                    ->label('Status')
                    ->alignCenter()
                    ->formatStateUsing(fn (bool $state) => new HtmlString(
                        $state
                        ? '<svg class="w-5 h-5 mx-auto shrink-0" style="color: #10b981; filter: drop-shadow(0 0 4px rgba(16,185,129,0.4))" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"></path>
                           </svg>'
                        : '<svg class="w-5 h-5 mx-auto shrink-0 animate-pulse" style="color: #ef4444; filter: drop-shadow(0 0 6px rgba(239,68,68,0.6))" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                           </svg>'
                    ))
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email)
                    ->weight('bold'),

                TextColumn::make('subject')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('message')
                    ->limit(45)
                    ->color('slate-400'),

                TextColumn::make('replied_at')
                    ->label('Replied')
                    ->dateTime('M j, Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->boolean()
                    ->trueLabel('Read Messages')
                    ->falseLabel('Unread Messages')
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make()
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                Action::make('toggle_read')
                    ->label(fn ($record) => $record->is_read ? 'Mark Unread' : 'Mark Read')
                    ->icon(fn ($record) => $record->is_read ? 'heroicon-o-envelope' : 'heroicon-o-envelope-open')
                    ->color(fn ($record) => $record->is_read ? 'warning' : 'success')
                    ->action(function ($record) {
                        $record->update(['is_read' => ! $record->is_read]);
                        Notification::make()
                            ->title($record->is_read ? 'Message marked as read' : 'Message marked as unread')
                            ->success()
                            ->send();
                    }),
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
