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

class InboxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Decrypted Transmission Console')
                    ->icon('heroicon-o-cpu-chip')
                    ->headerActions([
                        Action::make('reply')
                            ->label('✉️ Send Secure Reply')
                            ->color('success')
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
                            })
                    ])
                    ->schema([
                        Placeholder::make('decrypted_message')
                            ->label('Decrypted Transmission')
                            ->columnSpanFull()
                            ->content(fn ($record) => $record ? new \Illuminate\Support\HtmlString(
                                '<div class="relative w-full bg-[#050509] border border-emerald-500/25 rounded-xl p-6 font-mono text-sm leading-relaxed text-emerald-400 select-text overflow-hidden shadow-2xl">
                                    <!-- Terminal Header Grid -->
                                    <div class="border-b border-emerald-500/15 pb-4 mb-4 flex flex-col md:flex-row justify-between gap-2 text-xs text-emerald-500/70 select-none">
                                        <div>
                                            <span class="text-emerald-400 font-bold">[SENDER]:</span> ' . e($record->name) . ' (' . e($record->email) . ')
                                        </div>
                                        <div>
                                            <span class="text-emerald-400 font-bold">[RECEIVED]:</span> ' . e($record->created_at->format('Y-m-d H:i:s')) . '
                                        </div>
                                    </div>
                                    
                                    <!-- Terminal Command Line -->
                                    <div class="mb-4 text-xs text-yellow-500/80 font-bold select-none animate-pulse">
                                        $ gpg --decrypt-transmission --stream --identity ' . e(request()->getHost()) . '
                                    </div>
                                    
                                    <!-- Message Body -->
                                    <div class="whitespace-pre-wrap selection:bg-emerald-500 selection:text-[#050509] leading-6">' . e($record->message) . '</div>
                                    
                                    <!-- Scanning Lines Background Effect -->
                                    <div class="absolute inset-0 pointer-events-none bg-[linear-gradient(rgba(16,185,129,0.03)_50%,_rgba(0,0,0,0)_50%)] bg-[length:100%_4px] opacity-35"></div>
                                 </div>'
                            ) : new \Illuminate\Support\HtmlString(
                                '<div class="w-full bg-[#050509] border border-dashed border-emerald-500/25 rounded-xl p-8 flex flex-col items-center justify-center text-center text-slate-500 font-mono" style="height: 150px;">
                                    <span class="text-yellow-500 animate-pulse mb-2">[SYSTEM] awaiting transmission incoming...</span>
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
