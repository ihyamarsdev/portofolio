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
                    ->required(),
                TextInput::make('category')
                    ->required(),
                TextInput::make('proficiency')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('years_experience')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('stack_id')
                    ->relationship('stack', 'name'),
            ]);
    }
}
