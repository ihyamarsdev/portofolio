<?php

namespace App\Filament\Resources\Education\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Institution Details')
                    ->components([
                        TextInput::make('institution')
                            ->required(),
                        TextInput::make('degree')
                            ->required(),
                        TextInput::make('field_of_study')
                            ->required()
                            ->label('Major/Field of Study'),
                    ])->columns(3),

                Section::make('Duration & Grade')
                    ->components([
                        DatePicker::make('start_date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('End Date (Leave blank if current)'),
                        TextInput::make('grade')
                            ->label('GPA/Grade'),
                    ])->columns(3),

                Section::make('Additional Info')
                    ->components([
                        Textarea::make('activities')
                            ->label('Activities and Societies')
                            ->columnSpanFull(),
                    ]),

                TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
