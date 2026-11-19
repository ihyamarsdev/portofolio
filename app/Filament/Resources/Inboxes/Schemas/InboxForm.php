<?php

namespace App\Filament\Resources\Inboxes\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class InboxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->icon('heroicon-o-envelope-open')
                    ->headerActions([
                        Action::make('reply')
                            ->label('Mark Replied')
                            ->color('gray')
                            ->icon('heroicon-o-paper-airplane')
                            ->visible(fn ($record) => $record && $record->exists)
                            ->form([
                                TextInput::make('reply_subject')
                                    ->label('Subject')
                                    ->required()
                                    ->default(fn ($record) => $record ? "Re: {$record->subject}" : 'Reply to Transmission'),
                                Textarea::make('reply_message')
                                    ->label('Secure Response payload')
                                    ->required()
                                    ->rows(6)
                                    ->placeholder('Compose your message response here...'),
                            ])
                            ->action(function ($record, array $data) {
                                if ($record) {
                                    $record->update([
                                        'is_read' => true,
                                        'replied_at' => now(),
                                    ]);

                                    Notification::make()
                                        ->title('SMTP Handshake Successful')
                                        ->body("Encrypted reply payload securely dispatched to {$record->email}!")
                                        ->success()
                                        ->send();
                                }
                            }),
                    ])
                    ->schema([
                        Placeholder::make('decrypted_message')
                            ->label('Message')
                            ->columnSpanFull()
                            ->content(fn ($record) => $record ? new HtmlString(
                                '<div class="w-full rounded-xl border border-zinc-200 bg-zinc-50 p-6 text-sm leading-relaxed text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">
                                    <div class="mb-4 flex flex-col gap-3 border-b border-zinc-200 pb-4 text-xs text-zinc-500 dark:border-zinc-700 dark:text-zinc-400 md:flex-row md:justify-between">
                                        <div>
                                            <span class="font-semibold text-zinc-700 dark:text-zinc-200">From:</span> '.e($record->name).' ('.e($record->email).')
                                        </div>
                                        <div>
                                            <span class="font-semibold text-zinc-700 dark:text-zinc-200">Received:</span> '.e($record->created_at->format('Y-m-d H:i:s')).'
                                        </div>
                                    </div>
                                    <div class="whitespace-pre-wrap leading-6">'.e($record->message).'</div>
                                 </div>'
                            ) : new HtmlString(
                                '<div class="w-full rounded-xl border border-dashed border-zinc-200 bg-zinc-50 p-8 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400">
                                    No message to display.
                                </div>'
                            )),
                    ]),

                Section::make('Transmission Headers & Technical Metadata')
                    ->icon('heroicon-o-hashtag')
                    ->collapsible()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('Sender Name'),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->placeholder('sender@domain.com'),
                        TextInput::make('subject')
                            ->required()
                            ->placeholder('Transmission Subject')
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->required()
                            ->placeholder('Message Content')
                            ->columnSpanFull()
                            ->rows(4)
                            ->visible(fn ($record) => ! $record),
                        Toggle::make('is_read')
                            ->label('Status: Marked as Read')
                            ->default(false),
                        DateTimePicker::make('replied_at')
                            ->label('Dispatched Reply Timestamp')
                            ->disabled()
                            ->placeholder('Not yet replied'),
                    ])
                    ->columns(2),
            ]);
    }
}
