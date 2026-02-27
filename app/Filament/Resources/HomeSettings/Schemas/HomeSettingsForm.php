<?php

namespace App\Filament\Resources\HomeSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Hero Section')
                            ->description('Configure the main intro section on the homepage.')
                            ->icon('heroicon-o-sparkles')
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

                        Section::make('Tech Stack Section')
                            ->description('Manage your tools and technologies.')
                            ->icon('heroicon-o-cpu-chip')
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
                            ->description('Highlight your best work.')
                            ->icon('heroicon-o-briefcase')
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

                        Section::make('CTA Section')
                            ->description('Configure the call to action at the bottom of the page.')
                            ->icon('heroicon-o-megaphone')
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
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Terminal Commands')
                            ->description('Animated terminal commands on the hero section.')
                            ->icon('heroicon-o-command-line')
                            ->schema([
                                TagsInput::make('terminal_commands')
                                    ->label('Commands')
                                    ->placeholder('Add a command')
                                    ->hint('These commands will rotate in the terminal animation')
                                    ->required(),
                            ])
                            ->collapsible(),

                        Section::make('Infrastructure Section')
                            ->description('Status of live services.')
                            ->icon('heroicon-o-server')
                            ->schema([
                                TextInput::make('infrastructure_title')
                                    ->label('Title')
                                    ->default('Live Infrastructure')
                                    ->required(),
                                TextInput::make('infrastructure_description')
                                    ->label('Description')
                                    ->default('How this portfolio is deployed and monitored')
                                    ->required(),
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

                        Section::make('Branding')
                            ->description('Site identity and SEO details.')
                            ->icon('heroicon-o-globe-alt')
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
                    ]),
            ]);
    }
}
