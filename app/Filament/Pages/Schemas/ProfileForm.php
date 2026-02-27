<?php

namespace App\Filament\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        // Personal Information Section
                        Section::make('Personal Information')
                            ->description('Your basic professional details')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('John Doe')
                                    ->columnSpanFull(),

                                TextInput::make('title')
                                    ->label('Professional Title')
                                    ->placeholder('DevOps Engineer')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                MarkdownEditor::make('biography')
                                    ->label('Professional Summary')
                                    ->columnSpanFull()
                                    ->maxLength(1000)
                                    ->toolbarButtons([
                                        'bold',
                                        'bulletList',
                                        'heading',
                                        'italic',
                                        'orderedList',
                                        'redo',
                                        'undo',
                                    ]),
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'xl' => 3,
                            ]),

                        // Contact Information Section
                        Section::make('Contact Information')
                            ->description('How people can reach you')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('john@example.com')
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan([
                                        'sm' => 3,
                                        'md' => 2,
                                        'lg' => 1,
                                    ]),

                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('+62 812 3456 7890')
                                    ->columnSpan([
                                        'sm' => 3,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),

                                TextInput::make('location')
                                    ->label('Location')
                                    ->placeholder('Jakarta, Indonesia')
                                    ->maxLength(255)
                                    ->columnSpan([
                                        'sm' => 3,
                                        'md' => 3,
                                        'lg' => 1,
                                    ]),
                            ])
                            ->columns([
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 3,
                            ]),

                        // Social Media Section
                        Section::make('Social Media')
                            ->description('Connect your professional profiles')
                            ->icon('heroicon-o-link')
                            ->schema([
                                TextInput::make('github_url')
                                    ->label('GitHub')
                                    ->prefix('github.com/')
                                    ->placeholder('username')
                                    ->maxLength(255),

                                TextInput::make('linkedin_url')
                                    ->label('LinkedIn')
                                    ->prefix('linkedin.com/in/')
                                    ->placeholder('username')
                                    ->maxLength(255),

                                TextInput::make('twitter_url')
                                    ->label('Twitter / X')
                                    ->prefix('x.com/')
                                    ->placeholder('username')
                                    ->maxLength(255),

                                TextInput::make('website_url')
                                    ->label('Personal Website')
                                    ->url()
                                    ->prefix('https://')
                                    ->placeholder('yourwebsite.com')
                                    ->maxLength(255),
                            ])
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        // Profile Settings Section
                        Section::make('Profile Settings')
                            ->description('Manage your profile image and default settings')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        FileUpload::make('avatar')
                                            ->label('Avatar')
                                            ->image()
                                            ->avatar()
                                            ->imageEditor()
                                            ->circleCropper()
                                            ->directory('avatars')
                                            ->acceptedFileTypes([
                                                'image/png',
                                                'image/jpeg',
                                                'image/jpg',
                                                'image/webp',
                                            ])
                                            ->maxSize(2048),

                                        Toggle::make('is_primary')
                                            ->label('Primary Profile')
                                            ->default(true)
                                            ->inline(false),

                                        Select::make('cv_template')
                                            ->label('CV Template')
                                            ->options([
                                                'ats' => 'ATS - Optimized',
                                                'modern' => 'Modern',
                                                'classic' => 'Classic',
                                                'minimal' => 'Minimal',
                                            ])
                                            ->default('modern')
                                            ->required()
                                            ->native(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
