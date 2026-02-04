<?php

namespace App\Filament\Resources\ServiceStatuses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('url')
                    ->url(),
                TextInput::make('status')
                    ->required()
                    ->default('operational'),
                DateTimePicker::make('last_checked_at'),
                TextInput::make('uptime_percentage')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('response_time_ms')
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
