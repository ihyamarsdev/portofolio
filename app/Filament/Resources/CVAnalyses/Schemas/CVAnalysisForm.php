<?php

namespace App\Filament\Resources\CVAnalyses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CVAnalysisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Analysis Overview')
                    ->schema([
                        Select::make('profile_id')
                            ->relationship('profile', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('ats_score')
                            ->label('ATS Score')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('format_score')
                            ->label('Format Score')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('about_me_score')
                            ->label('About Me Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('job_match_score')
                            ->label('Job Match Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('job_title_targeted')
                            ->label('Job Title Targeted')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Keywords & Suggestions')
                    ->schema([
                        TagsInput::make('keywords_found')
                            ->label('Keywords Found')
                            ->placeholder('Add found keyword'),
                        TagsInput::make('keywords_missing')
                            ->label('Keywords Missing')
                            ->placeholder('Add missing keyword'),
                        TagsInput::make('suggestions')
                            ->label('Suggestions')
                            ->placeholder('Add suggestion')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Industry Match')
                    ->schema([
                        TextInput::make('industry_match.devops')
                            ->label('DevOps Match')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('industry_match.cloud')
                            ->label('Cloud Match')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('industry_match.sre')
                            ->label('SRE Match')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->columns(3),

                Section::make('Summary')
                    ->schema([
                        Textarea::make('summary')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
