<?php

namespace App\Filament\Resources\Stacks\Schemas;

use App\Filament\Forms\Components\SvgIconPickerField;
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
                    ->description('Define technology stack entries and their visual identifiers.')
                    ->icon('heroicon-o-circle-stack')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('e.g., Docker, Kubernetes, Terraform'),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Select a category'),
                        SvgIconPickerField::make('icon')
                            ->placeholder('Enter URL, devicon class, or emoji — or browse theSVG')
                            ->helperText('Paste an image URL / devicon class, or click Browse Icons to search theSVG (6000+ brand SVGs).'),
                    ])
                    ->columns(2),
            ]);
    }
}
