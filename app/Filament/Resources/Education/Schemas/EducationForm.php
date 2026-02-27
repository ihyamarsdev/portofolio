<?php

namespace App\Filament\Resources\Education\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Institution Details')
                            ->icon('heroicon-o-academic-cap')
                            ->components([
                                TextInput::make('institution')
                                    ->required(),
                                TextInput::make('degree')
                                    ->required(),
                                TextInput::make('field_of_study')
                                    ->required()
                                    ->label('Major/Field of Study'),
                            ])->columns(3),

                        Section::make('Additional Info')
                            ->icon('heroicon-o-information-circle')
                            ->components([
                                Textarea::make('activities')
                                    ->label('Activities and Societies')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Duration & Grade')
                            ->icon('heroicon-o-calendar-days')
                            ->components([
                                DatePicker::make('start_date')
                                    ->required()
                                    ->columnSpanFull(),
                                DatePicker::make('end_date')
                                    ->label('End Date (Leave blank if current)')
                                    ->columnSpanFull(),
                                TextInput::make('grade')
                                    ->label('GPA/Grade')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Settings')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                TextInput::make('order')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
