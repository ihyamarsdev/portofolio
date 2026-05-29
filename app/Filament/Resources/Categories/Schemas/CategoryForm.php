<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('e.g., Cloud Infrastructure')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->required()
                            ->placeholder('e.g., cloud-infrastructure')
                            ->unique(ignoreRecord: true),

                        ColorPicker::make('color')
                            ->required()
                            ->default('#06b6d4'),
                    ])
                    ->columns(3),
            ]);
    }
}
