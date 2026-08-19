<?php

namespace App\Filament\Resources\HomeSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
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
                        Tabs::make('Homepage Content')
                            ->tabs([
                                Tabs\Tab::make('🇬🇧 English (EN)')
                                    ->schema([
                                        Section::make('Hero Section (EN)')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('hero_greeting.en')
                                                            ->label('Greeting (English)')
                                                            ->default('Hi, I\'m a')
                                                            ->required(),
                                                        TextInput::make('availability_status.en')
                                                            ->label('Availability Status (English)')
                                                            ->default('Available for new opportunities')
                                                            ->required(),
                                                    ]),
                                                Textarea::make('hero_description.en')
                                                    ->label('Description (English)')
                                                    ->rows(3)
                                                    ->required(),
                                            ]),

                                        Section::make('Sections Title & CTA (EN)')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('tech_stack_title.en')
                                                            ->label('Tech Stack Title (EN)')
                                                            ->default('Tech Stack'),
                                                        TextInput::make('featured_projects_title.en')
                                                            ->label('Featured Projects Title (EN)')
                                                            ->default('Featured Projects'),
                                                    ]),
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('cta_title.en')
                                                            ->label('CTA Title (EN)')
                                                            ->default('Let\'s Build Something Together'),
                                                        TextInput::make('cta_primary_button_text.en')
                                                            ->label('Primary Button Text (EN)')
                                                            ->default('Get In Touch'),
                                                    ]),
                                                Textarea::make('cta_description.en')
                                                    ->label('CTA Description (EN)')
                                                    ->rows(3),
                                            ]),
                                    ]),

                                Tabs\Tab::make('🇮🇩 Bahasa Indonesia (ID)')
                                    ->schema([
                                        Section::make('Hero Section (ID)')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('hero_greeting.id')
                                                            ->label('Greeting (Bahasa Indonesia)')
                                                            ->placeholder('Halo, saya seorang'),
                                                        TextInput::make('availability_status.id')
                                                            ->label('Status Ketersediaan (ID)')
                                                            ->placeholder('Tersedia untuk peluang baru'),
                                                    ]),
                                                Textarea::make('hero_description.id')
                                                    ->label('Deskripsi (Bahasa Indonesia)')
                                                    ->rows(3),
                                            ]),

                                        Section::make('Judul Seksi & CTA (ID)')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('tech_stack_title.id')
                                                            ->label('Judul Tech Stack (ID)')
                                                            ->placeholder('Keahlian Teknologi'),
                                                        TextInput::make('featured_projects_title.id')
                                                            ->label('Judul Proyek Unggulan (ID)')
                                                            ->placeholder('Proyek Unggulan'),
                                                    ]),
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('cta_title.id')
                                                            ->label('Judul CTA (ID)')
                                                            ->placeholder('Mari Bekerja Sama'),
                                                        TextInput::make('cta_primary_button_text.id')
                                                            ->label('Teks Tombol Kontak (ID)')
                                                            ->placeholder('Hubungi Saya'),
                                                    ]),
                                                Textarea::make('cta_description.id')
                                                    ->label('Deskripsi CTA (ID)')
                                                    ->rows(3),
                                            ]),
                                    ]),
                            ])
                            ->columnSpanFull(),
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
