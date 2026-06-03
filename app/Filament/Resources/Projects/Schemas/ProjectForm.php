<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Project Information')
                            ->description('Basic information about the project')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->placeholder('e.g., Kubernetes Auto-Scaling Pipeline')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(
                                        fn (Set $set, ?string $state) => $set(
                                            'slug',
                                            Str::slug($state),
                                        ),
                                    )
                                    ->maxLength(255)
                                    ->helperText(
                                        'The slug will be automatically generated from the title',
                                    ),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('URL-friendly version of the title'),
                            ])
                            ->columns(2),

                        Section::make('Description')
                            ->description(
                                'Detailed project description and technical details',
                            )
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Textarea::make('description')
                                    ->required()
                                    ->rows(5)
                                    ->placeholder('Describe the project architecture, purpose, and key features...')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->collapsed(false),

                        Section::make('Architecture Diagram')
                            ->description('Mermaid diagram representation of the project architecture')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Textarea::make('architecture_diagram')
                                    ->rows(10)
                                    ->columnSpanFull()
                                    ->placeholder('flowchart TD\n    A[Client] --> B[Load Balancer]\n    B --> C[App Server]\n    C --> D[(Database)]')
                                    ->helperText('Enter Mermaid diagram syntax or use the ⚡ Generate with AI button at the top-right.'),
                            ])
                            ->collapsible()
                            ->collapsed(),

                        Section::make('Live Project Viewport')
                            ->description('Real-time rendering of the project page on the public site.')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Placeholder::make('project_viewport')
                                    ->content(fn (?Project $record) => $record && $record->exists ? new HtmlString(
                                        '<div class="w-full bg-[#0b0b10] border border-[#2d2d3a] rounded-xl overflow-hidden shadow-2xl relative" style="height: 550px;">
                                            <div class="bg-slate-900 border-b border-[#2d2d3a] px-4 py-2 flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-1.5 shrink-0 select-none">
                                                    <span class="w-3 h-3 rounded-full" style="background: #ff5f56"></span>
                                                    <span class="w-3 h-3 rounded-full" style="background: #ffbd2e"></span>
                                                    <span class="w-3 h-3 rounded-full" style="background: #27c93f"></span>
                                                </div>
                                                <div class="flex items-center gap-2 bg-[#09090d] border border-slate-700 rounded-md px-3 py-1 text-xs text-slate-400 font-mono w-full max-w-[450px] overflow-hidden truncate">
                                                    <span class="shrink-0 select-none" style="color: #10b981">HTTPS://</span>
                                                    <span>'.e(request()->getHost()).'/projects/'.e($record->slug).'</span>
                                                </div>
                                                <a href="/projects/'.e($record->slug).'" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 rounded text-[11px] font-semibold flex items-center gap-1 transition-all shrink-0">
                                                    <span>Open Tab</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>
                                            <iframe sandbox="allow-same-origin allow-forms" src="/projects/'.e($record->slug).'" class="w-full border-none" style="height: calc(100% - 37px); min-height: 510px;"></iframe>
                                        </div>'
                                    ) : new HtmlString(
                                        '<div class="w-full bg-[#0b0b10] border border-[#2d2d3a] rounded-xl p-8 flex flex-col items-center justify-center text-center text-slate-400 font-mono" style="height: 250px;">
                                            <svg class="w-12 h-12 text-slate-600 mb-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 17L12 22L22 17M2 12L12 17L22 12M12 2L2 7L12 12L22 7L12 2Z"></path></svg>
                                            <h4 class="font-bold text-white text-sm">Project Viewport Uninitialized</h4>
                                            <p class="text-xs text-slate-500 max-w-[280px] mt-1">Save the project first to activate the live preview viewport.</p>
                                        </div>'
                                    ))
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->collapsed(fn (?Project $record) => ! ($record && $record->exists)),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Tech Stack')
                            ->description('Skills and technologies used in this project')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Select::make('skills')
                                    ->multiple()
                                    ->relationship('skills', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),

                        Section::make('Links')
                            ->description('External links for the project')
                            ->icon('heroicon-o-link')
                            ->schema([
                                TextInput::make('repository_url')
                                    ->label('Repository URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-code-bracket')
                                    ->placeholder('https://github.com/username/repo')
                                    ->columnSpanFull(),
                                TextInput::make('live_url')
                                    ->label('Live Demo URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-globe-alt')
                                    ->placeholder('https://project-demo.com')
                                    ->columnSpanFull(),
                                TextInput::make('image_url')
                                    ->label('Internet Image URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-photo')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->helperText('Use this when the project image is hosted on the internet instead of uploaded locally.')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),

                        Section::make('Settings')
                            ->description('Project display settings')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured Project')
                                    ->helperText('Show this project on the homepage')
                                    ->default(false)
                                    ->inline(false)
                                    ->columnSpanFull(),
                                TextInput::make('order')
                                    ->label('Display Order')
                                    ->helperText('Lower numbers appear first')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->columnSpanFull(),
                            ])
                            ->collapsible(),
                    ]),
            ]);
    }
}
