<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Skill Name'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->label('Category'),
                TextInput::make('years_experience')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Years of Experience')
                    ->helperText('Demonstrate proficiency through your project details and experience descriptions'),
                Select::make('stack_id')
                    ->relationship('stack', 'name')
                    ->label('Tech Stack'),
            ]);
    }
}
