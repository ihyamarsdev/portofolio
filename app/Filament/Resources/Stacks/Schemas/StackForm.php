<?php

namespace App\Filament\Resources\Stacks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tech Stack Details')
                    ->icon('heroicon-o-circle-stack')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),
                        TextInput::make('icon'),
                    ])
                    ->columns(2),
            ]);
    }
}
