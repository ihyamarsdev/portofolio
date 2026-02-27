<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Ai\Agents\PostSeoAgent;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Content')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->minLength(10)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

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
                                    ->columnSpanFull()
                                    ->hint(fn($state) => strlen($state ?? '') . ' / 160 chars')
                                    ->hintIcon('heroicon-o-information-circle'),
                            ]),

                        Section::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->headerActions([
                                Action::make('generate_seo')
                                    ->label('✨ Generate with AI')
                                    ->color('warning')
                                    ->icon('heroicon-o-sparkles')
                                    ->action(function (Get $get, Set $set) {
                                        $title = $get('title') ?? '';
                                        $content = $get('content') ?? '';

                                        if (blank($title) || blank($content)) {
                                            Notification::make()
                                                ->title('Title and Content Required')
                                                ->body('Please fill in the Title and Content fields before generating SEO data.')
                                                ->warning()
                                                ->send();

                                            return;
                                        }

                                        $agent = new PostSeoAgent($title, $content);
                                        $result = json_decode(
                                            $agent->prompt(
                                                prompt: 'Generate SEO-optimized excerpt, meta title, and meta description based on the post title and content provided in the instructions.',
                                                model: 'gemini-2.5-flash',
                                            )->text,
                                            true,
                                        );

                                        if (isset($result['excerpt'])) {
                                            $set('excerpt', Str::limit($result['excerpt'], 160, ''));
                                            $set('meta_title', Str::limit($result['meta_title'] ?? '', 60, ''));
                                            $set('meta_description', Str::limit($result['meta_description'] ?? '', 160, ''));

                                            Notification::make()
                                                ->title('SEO Generated Successfully')
                                                ->body('Excerpt, Meta Title and Meta Description have been filled by AI.')
                                                ->success()
                                                ->send();
                                        } else {
                                            Notification::make()
                                                ->title('AI Generation Failed')
                                                ->body('Failed to generate SEO data. Please try again.')
                                                ->danger()
                                                ->send();
                                        }
                                    }),
                            ])
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->hint(fn($state) => strlen($state ?? '') . ' / 60 chars')
                                    ->hintIcon('heroicon-o-information-circle'),

                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->hint(fn($state) => strlen($state ?? '') . ' / 160 chars')
                                    ->hintIcon('heroicon-o-information-circle'),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
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
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
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
                    ]),
            ]);
    }
}
