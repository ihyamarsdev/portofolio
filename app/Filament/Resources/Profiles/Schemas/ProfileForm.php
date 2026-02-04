<?php

namespace App\Filament\Resources\Profiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Basic Information')
                            ->columnSpan(2)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('title')
                                            ->label('Professional Title')
                                            ->placeholder('e.g. DevOps Engineer')
                                            ->maxLength(255),
                                    ]),
                                MarkdownEditor::make('biography')
                                    ->label('About Me')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Avatar')
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('avatar')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->directory('avatars'),
                                Toggle::make('is_primary')
                                    ->label('Set as Primary Profile')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('location')
                                    ->placeholder('City, Country')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Social Media & Links')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('github_url')
                                    ->label('GitHub URL')
                                    ->prefix('https://github.com/')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->prefix('https://linkedin.com/in/')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('twitter_url')
                                    ->label('Twitter / X URL')
                                    ->prefix('https://twitter.com/')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('website_url')
                                    ->label('Personal Website')
                                    ->prefix('https://')
                                    ->url()
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }
}
