<?php

namespace App\Filament\Resources\Inboxes\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class InboxesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->alignCenter()
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
                    ->label('Unread / Read')
                    ->boolean()
                    ->trueLabel('Read')
                    ->falseLabel('Unread')
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_read')
                        ->label('Mark selected as read')
                        ->icon('heroicon-o-envelope-open')
                        ->color('success')
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->update(['is_read' => true]);
                            }

                            Notification::make()
                                ->title($records->count().' messages marked as read')
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('mark_unread')
                        ->label('Mark selected as unread')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->update(['is_read' => false]);
                            }

                            Notification::make()
                                ->title($records->count().' messages marked as unread')
                                ->success()
                                ->send();
                        }),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Inbox all clear')
            ->emptyStateDescription('New contact messages will appear here.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->defaultSort('created_at', 'desc');
    }
}
