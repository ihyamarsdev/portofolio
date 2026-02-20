<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    // Left Column - Main Content
                    Grid::make(1)
                        ->schema([
                            Section::make('Content')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->minLength(10)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                    TextInput::make('slug')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->readOnly(),

                                    RichEditor::make('content')
                                        ->required()
                                        ->columnSpanFull()
                                        ->fileAttachmentsDisk('public')
                                        ->fileAttachmentsDirectory('posts'),

                                    Textarea::make('excerpt')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),

                            Section::make('SEO')
                                ->icon('heroicon-o-magnifying-glass')
                                ->collapsed()
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->label('Meta Title')
                                        ->maxLength(60),

                                    Textarea::make('meta_description')
                                        ->label('Meta Description')
                                        ->rows(3)
                                        ->maxLength(160),
                                ]),
                        ])->grow(true), // Allow to grow

                    // Right Column - Sidebar
                    Grid::make(1)
                        ->schema([
                            Section::make('Status')
                                ->icon('heroicon-o-signal')
                                ->schema([
                                    Select::make('status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'pending' => 'Pending Review',
                                            'published' => 'Published',
                                            'archived' => 'Archived',
                                        ])
                                        ->default('draft')
                                        ->required()
                                        ->native(false),

                                    DateTimePicker::make('published_at')
                                        ->label('Publish Date')
                                        ->native(false),
                                ]),

                            Section::make('Details')
                                ->icon('heroicon-o-cog-6-tooth')
                                ->schema([
                                    Select::make('category_id')
                                        ->label('Category')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                            TextInput::make('slug')
                                                ->required(),
                                            TextInput::make('color')
                                                ->default('#6366f1'),
                                        ])
                                        ->native(false),

                                    SpatieMediaLibraryFileUpload::make('featured_image')
                                        ->collection('featured_image')
                                        ->image()
                                        ->imageEditor()
                                        ->imageResizeMode('cover')
                                        ->imageCropAspectRatio('16:9')
                                        ->imageResizeTargetWidth('1200')
                                        ->imageResizeTargetHeight('675'),
                                ]),
                        ])->grow(false), // Prevent growing too much
                ])
                    ->from('md') // Apply split on medium screens and up
                    ->columnSpanFull(),
            ]);
    }
}
