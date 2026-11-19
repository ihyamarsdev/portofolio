<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Company Details')
                            ->icon('heroicon-o-building-office')
                            ->components([
                                TextInput::make('company')
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('position')
                                    ->required()
                                    ->columnSpanFull(),
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

                        Section::make('Description & Achievements')
                            ->icon('heroicon-o-document-text')
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
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Duration')
                            ->icon('heroicon-o-calendar')
                            ->components([
                                DatePicker::make('start_date')
                                    ->required()
                                    ->columnSpanFull(),
                                DatePicker::make('end_date')
                                    ->label('End Date (Leave blank if current)')
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

                        Section::make('Settings')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Tampilkan di CV')
                                    ->helperText('Centang untuk menyertakan experience ini di CV. Hanya experience yang dicentang yang akan tampil di CV.')
                                    ->default(false)
                                    ->inline(false)
                                    ->columnSpanFull(),

                                TextInput::make('order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Display Order')
                                    ->helperText('Lower numbers appear first')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
