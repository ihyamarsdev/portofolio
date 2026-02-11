<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Details')
                    ->components([
                        TextInput::make('company')
                            ->required(),
                        TextInput::make('position')
                            ->required(),
                        Select::make('employment_type')
                            ->options([
                                'Full-time' => 'Full-time',
                                'Part-time' => 'Part-time',
                                'Contract' => 'Contract',
                                'Freelance' => 'Freelance',
                                'Internship' => 'Internship',
                            ]),
                        TextInput::make('location'),
                    ])->columns(2),

                Section::make('Duration')
                    ->components([
                        DatePicker::make('start_date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('End Date (Leave blank if current)'),
                    ])->columns(2),

                Section::make('Description')
                    ->components([
                        MarkdownEditor::make('description')
                            ->columnSpanFull(),
                        Repeater::make('achievements')
                            ->simple(
                                TextInput::make('achievement')
                                    ->required()
                            )
                            ->columnSpanFull(),
                    ]),

                Section::make('Skills Used')
                    ->description('Select the skills you utilized in this experience')
                    ->icon('heroicon-o-sparkles')
                    ->components([
                        Select::make('skills')
                            ->relationship('skills', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select all skills that you used or developed during this experience')
                            ->columnSpanFull()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                TextInput::make('years_experience')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ]),

                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->label('Display Order')
                    ->helperText('Lower numbers appear first'),
            ]);
    }
}
