<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Skill Details')
                    ->icon('heroicon-o-light-bulb')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Skill Name'),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->label('Category'),
                        Select::make('proficiency')
                            ->required()
                            ->default(3)
                            ->options([
                                1 => '1 — Beginner',
                                2 => '2 — Developing',
                                3 => '3 — Intermediate',
                                4 => '4 — Advanced',
                                5 => '5 — Expert',
                            ])
                            ->label('Proficiency')
                            ->helperText('Level of mastery, from 1 (Beginner) to 5 (Expert).'),
                        TextInput::make('years_experience')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(40)
                            ->default(0)
                            ->label('Years of Experience')
                            ->helperText('Demonstrate proficiency through your project details and experience descriptions'),
                        Select::make('stacks')
                            ->relationship('stacks', 'name')
                            ->multiple()
                            ->preload()
                            ->label('Tech Stacks')
                            ->helperText('Select relevant tech stacks for this skill'),
                    ])
                    ->columns(2),
            ]);
    }
}
