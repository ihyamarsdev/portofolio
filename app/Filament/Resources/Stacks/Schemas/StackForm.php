<?php

namespace App\Filament\Resources\Stacks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use TangoDevIt\FilamentEmojiPicker\EmojiPickerAction;

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
                        TextInput::make('icon')
                            ->placeholder('e.g., 🐳, devicon-docker-plain, or https://...')
                            ->helperText('Choose an emoji, or enter a Devicon class name / image URL for the stack icon.')
                            ->suffixAction(
                                EmojiPickerAction::make('choose-stack-emoji')
                                    ->label('Choose emoji')
                            ),
                    ])
                    ->columns(2),
            ]);
    }
}
