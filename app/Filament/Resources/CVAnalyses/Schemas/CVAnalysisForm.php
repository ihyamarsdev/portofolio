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
                        TextInput::make('ats_score')->label('ATS Score')->required()->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('format_score')->label('Format Score')->required()->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('about_me_score')->label('About Me Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('impact_score')->label('Impact Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('role_alignment_score')->label('Role Alignment Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('security_score')->label('Security Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('reliability_score')->label('Reliability Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('cloud_score')->label('Cloud Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('job_match_score')->label('Job Match Score')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('job_title_targeted')->label('Job Title Targeted')->maxLength(255),
                        TextInput::make('target_seniority')->label('Target Seniority')->maxLength(255),
                    ])
                    ->columns(3),

                Section::make('Keywords & Guidance')
                    ->schema([
                        TagsInput::make('keywords_found')
                            ->label('Keywords Found')
                            ->placeholder('Add found keyword'),
                        TagsInput::make('weak_evidence')
                            ->label('Weak Evidence')
                            ->placeholder('Add weak evidence keyword'),
                        TagsInput::make('keywords_missing')
                            ->label('Keywords Missing')
                            ->placeholder('Add missing keyword'),
                        TagsInput::make('strengths')
                            ->label('Strengths')
                            ->placeholder('Add strength')
                            ->columnSpanFull(),
                        TagsInput::make('critical_gaps')
                            ->label('Critical Gaps')
                            ->placeholder('Add critical gap')
                            ->columnSpanFull(),
                        TagsInput::make('missing_metrics')
                            ->label('Missing Metrics')
                            ->placeholder('Add missing metric')
                            ->columnSpanFull(),
                        TagsInput::make('rewrite_priorities')
                            ->label('Rewrite Priorities')
                            ->placeholder('Add rewrite priority')
                            ->columnSpanFull(),
                        TagsInput::make('suggestions')
                            ->label('Suggestions')
                            ->placeholder('Add suggestion')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Industry Match')
                    ->schema([
                        TextInput::make('industry_match.devops')->label('DevOps Match')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('industry_match.devsecops')->label('DevSecOps Match')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('industry_match.sre')->label('SRE Match')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('industry_match.cloud_engineer')->label('Cloud Engineer Match')->numeric()->minValue(0)->maxValue(100),
                    ])
                    ->columns(4),

                Section::make('Experience Review')
                    ->schema([
                        Textarea::make('experience_review')
                            ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                            ->dehydrateStateUsing(fn (?string $state) => $state ? json_decode($state, true) : [])
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('JSON array of section/strength/gap/rewrite_hint objects.'),
                    ]),

                Section::make('Summary')
                    ->schema([
                        Textarea::make('summary')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
