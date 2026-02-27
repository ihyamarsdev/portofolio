<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Certificate Info')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('issuer')
                            ->required(),
                        TextInput::make('credential_id'),
                        TextInput::make('credential_url')
                            ->url()
                            ->columnSpanFull(),
                        DatePicker::make('issue_date')
                            ->required(),
                        DatePicker::make('expiry_date'),
                        Toggle::make('is_active')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
