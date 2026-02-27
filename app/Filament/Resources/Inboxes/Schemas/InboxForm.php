<?php

namespace App\Filament\Resources\Inboxes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InboxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message Details')
                    ->icon('heroicon-o-inbox')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('subject')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->required()
                            ->columnSpanFull(),
                        Toggle::make('is_read')
                            ->required(),
                        DateTimePicker::make('replied_at'),
                    ])
                    ->columns(2),
            ]);
    }
}
