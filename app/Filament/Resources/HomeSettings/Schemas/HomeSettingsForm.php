<?php

namespace App\Filament\Resources\HomeSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero Section')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('hero_greeting')
                                    ->label('Greeting')
                                    ->default('Hi, I\'m a')
                                    ->required(),
                                TextInput::make('availability_status')
                                    ->label('Availability Status')
                                    ->default('Available for new opportunities')
                                    ->required(),
                            ]),
                        Textarea::make('hero_description')
                            ->label('Description')
                            ->rows(3)
                            ->required(),
                        Toggle::make('availability_active')
                            ->label('Show Availability')
                            ->default(true),
                    ]),

                Section::make('Terminal Commands')
                    ->schema([
                        TagsInput::make('terminal_commands')
                            ->label('Commands')
                            ->placeholder('Add a command')
                            ->hint('These commands will rotate in the terminal animation')
                            ->required(),
                    ])
                    ->collapsible(),

                Section::make('Tech Stack Section')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('tech_stack_title')
                                    ->label('Title')
                                    ->default('Tech Stack')
                                    ->required(),
                                TextInput::make('tech_stack_description')
                                    ->label('Description')
                                    ->default('Tools and technologies I work with daily')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Featured Projects Section')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('featured_projects_title')
                                    ->label('Title')
                                    ->default('Featured Projects')
                                    ->required(),
                                TextInput::make('featured_projects_description')
                                    ->label('Description')
                                    ->default('Infrastructure and automation work I\'m proud of')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Infrastructure Section')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('infrastructure_title')
                                    ->label('Title')
                                    ->default('Live Infrastructure')
                                    ->required(),
                                TextInput::make('infrastructure_description')
                                    ->label('Description')
                                    ->default('How this portfolio is deployed and monitored')
                                    ->required(),
                            ]),
                        TextInput::make('infrastructure_status_text')
                            ->label('Status Text')
                            ->default('All services running')
                            ->required(),
                        Select::make('infrastructure_status')
                            ->label('Status')
                            ->options([
                                'operational' => 'Operational',
                                'degraded' => 'Degraded',
                                'down' => 'Down',
                            ])
                            ->default('operational')
                            ->required(),
                    ]),

                Section::make('CTA Section')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('cta_title')
                                    ->label('Title')
                                    ->default('Let\'s Build Something Together')
                                    ->required(),
                                TextInput::make('cta_primary_button_text')
                                    ->label('Primary Button Text')
                                    ->default('Get In Touch')
                                    ->required(),
                            ]),
                        Textarea::make('cta_description')
                            ->label('Description')
                            ->rows(3)
                            ->required(),
                        TextInput::make('cta_secondary_button_text')
                            ->label('Secondary Button Text')
                            ->default('Download CV')
                            ->required(),
                    ]),

                Section::make('Branding')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('site_brand')
                                    ->label('Site Brand (Logo)')
                                    ->placeholder('~/devops')
                                    ->required()
                                    ->hint('This appears in the navigation logo'),
                                TextInput::make('site_title')
                                    ->label('Site Title')
                                    ->placeholder('DevOps Engineer')
                                    ->required()
                                    ->hint('Your professional title that appears in page titles'),
                            ]),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(2)
                            ->hint('SEO description for search engines'),
                        TextInput::make('footer_text')
                            ->label('Footer Text')
                            ->default('DevOps Portfolio. Built with Laravel & Filament.')
                            ->required(),
                    ])
                    ->collapsible(),
            ]);
    }
}
