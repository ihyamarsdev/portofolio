<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 8])
                    ->schema([
                        Section::make('Content')
                            ->description('Write and structure the post content in both languages.')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Tabs::make('Post Content')
                                    ->tabs([
                                        Tabs\Tab::make('🇬🇧 English (EN)')
                                            ->schema([
                                                TextInput::make('title.en')
                                                    ->label('Title (English)')
                                                    ->required()
                                                    ->minLength(10)
                                                    ->placeholder('e.g., Kubernetes Rollouts in Production')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                                self::contentEditor('en'),
                                            ]),
                                        Tabs\Tab::make('🇮🇩 Bahasa Indonesia (ID)')
                                            ->schema([
                                                TextInput::make('title.id')
                                                    ->label('Judul (Bahasa Indonesia)')
                                                    ->placeholder('misal: Rollout Kubernetes di Produksi')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                                        if (blank($get('slug')) && filled($state)) {
                                                            $set('slug', Str::slug($state));
                                                        }
                                                    }),

                                                self::contentEditor('id'),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Excerpt & SEO')
                            ->description('Post summary and search engine metadata for each language.')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Tabs::make('Excerpt & SEO')
                                    ->tabs([
                                        Tabs\Tab::make('🇬🇧 English (EN)')
                                            ->schema([
                                                Textarea::make('excerpt.en')
                                                    ->label('Excerpt (English)')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->columnSpanFull()
                                                    ->hint(fn ($state) => strlen($state ?? '').' / 160 chars')
                                                    ->hintIcon('heroicon-o-information-circle'),

                                                TextInput::make('meta_title.en')
                                                    ->label('Meta Title (English)')
                                                    ->maxLength(60)
                                                    ->hint(fn ($state) => strlen($state ?? '').' / 60 chars')
                                                    ->hintIcon('heroicon-o-information-circle'),

                                                Textarea::make('meta_description.en')
                                                    ->label('Meta Description (English)')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->hint(fn ($state) => strlen($state ?? '').' / 160 chars')
                                                    ->hintIcon('heroicon-o-information-circle'),
                                            ]),
                                        Tabs\Tab::make('🇮🇩 Bahasa Indonesia (ID)')
                                            ->schema([
                                                Textarea::make('excerpt.id')
                                                    ->label('Ringkasan (Bahasa Indonesia)')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->columnSpanFull()
                                                    ->hint(fn ($state) => strlen($state ?? '').' / 160 chars')
                                                    ->hintIcon('heroicon-o-information-circle'),

                                                TextInput::make('meta_title.id')
                                                    ->label('Meta Title (Bahasa Indonesia)')
                                                    ->maxLength(60)
                                                    ->hint(fn ($state) => strlen($state ?? '').' / 60 chars')
                                                    ->hintIcon('heroicon-o-information-circle'),

                                                Textarea::make('meta_description.id')
                                                    ->label('Meta Description (Bahasa Indonesia)')
                                                    ->rows(3)
                                                    ->maxLength(160)
                                                    ->hint(fn ($state) => strlen($state ?? '').' / 160 chars')
                                                    ->hintIcon('heroicon-o-information-circle'),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Live Post Viewport')
                            ->description('Real-time responsive rendering of the article on the public site.')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Placeholder::make('viewport')
                                    ->content(fn (?Post $record) => $record && $record->exists ? new HtmlString(
                                        '<div class="w-full overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900" style="height: 600px;">
                                            <div class="flex items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50 px-4 py-2 dark:border-zinc-700 dark:bg-zinc-800">
                                                <div class="flex shrink-0 items-center gap-1.5 select-none">
                                                    <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                                                    <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                                    <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                                                </div>
                                                <div class="flex w-full max-w-[450px] items-center gap-2 overflow-hidden truncate rounded-md border border-zinc-200 bg-white px-3 py-1 font-mono text-xs text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900">
                                                    <span class="shrink-0 select-none text-emerald-600">HTTPS://</span>
                                                    <span>'.e(request()->getHost()).'/blog/'.e($record->slug).'</span>
                                                </div>
                                                <div class="flex shrink-0 items-center gap-2">
                                                    <button type="button" onclick="document.getElementById(\'live-post-viewport\').src = document.getElementById(\'live-post-viewport\').src" class="flex h-7 w-7 items-center justify-center rounded border border-zinc-200 bg-white p-1 text-zinc-500 hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:text-zinc-100" title="Reload Preview">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m0 0l-3 3-3-3"></path></svg>
                                                    </button>
                                                    <a href="/blog/'.e($record->slug).'" target="_blank" class="flex h-7 items-center gap-1 rounded border border-zinc-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                                        <span>Open Tab</span>
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe id="live-post-viewport" sandbox="allow-same-origin allow-forms" src="/blog/'.e($record->slug).'?t='.($record->updated_at ? $record->updated_at->timestamp : time()).'" class="h-full w-full border-none" style="height: calc(100% - 37px); min-height: 563px;"></iframe>
                                        </div>'
                                    ) : new HtmlString(
                                        '<div class="flex w-full flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900" style="height: 300px;">
                                            <svg class="mb-3 h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                            <h4 class="text-sm font-bold text-zinc-800 dark:text-zinc-100">Post Viewport Uninitialized</h4>
                                            <p class="mt-1 max-w-[280px] text-xs text-zinc-500 dark:text-zinc-400">Save your post draft once to instantiate a live preview viewport anchor.</p>
                                        </div>'
                                    ))
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->collapsed(fn (?Post $record) => ! ($record && $record->exists)),
                    ]),

                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 4])
                    ->schema([
                        Section::make('Permalink')
                            ->description('URL-friendly identifier for this post.')
                            ->icon('heroicon-o-link')
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->readOnly(),
                            ])
                            ->collapsible(),

                        Section::make('Publishing')
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
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                        if ($state === 'published' && blank($get('published_at'))) {
                                            $set('published_at', now());
                                        }
                                    }),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(fn (Get $get) => $get('status') === 'published' ? now() : null)
                                    ->helperText('Saat status Published dan kosong, otomatis memakai waktu sekarang.')
                                    ->native(false),
                            ])
                            ->collapsible(),

                        Section::make('Category & Media')
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
                                    ->label('Featured Image')
                                    ->image()
                                    ->imageEditor()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1600')
                                    ->imageResizeTargetHeight('900')
                                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'])
                                    ->helperText('Upload is preferred: automatically optimized and converted to responsive WebP.'),

                                TextInput::make('featured_image_url')
                                    ->label('External Image URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-photo')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->helperText('Fallback only for remote images. When an image is uploaded above, the uploaded WebP is preferred.'),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ]),
            ]);
    }

    private static function contentEditor(string $locale): RichEditor
    {
        return RichEditor::make('content.'.$locale)
            ->label($locale === 'en' ? 'Content (English)' : 'Konten (Bahasa Indonesia)')
            ->required($locale === 'en')
            ->columnSpanFull()
            ->plugins([
                app('Awcodes\\RicherEditor\\Plugins\\EmbedPlugin'),
                app('Awcodes\\RicherEditor\\Plugins\\SourceCodePlugin'),
                app('Awcodes\\RicherEditor\\Plugins\\FullScreenPlugin'),
                app('Awcodes\\RicherEditor\\Plugins\\IdPlugin'),
                app('Awcodes\\RicherEditor\\Plugins\\LinkPlugin'),
                app('Awcodes\\RicherEditor\\Plugins\\EmojiPlugin'),
            ])
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'code', 'link'],
                ['h2', 'h3', 'blockquote', 'lead', 'small'],
                ['bulletList', 'orderedList', 'table', 'attachFiles'],
                ['embed', 'sourceCode', 'fullscreen'],
                ['undo', 'redo'],
            ])
            ->floatingToolbars([
                'paragraph' => ['bold', 'italic', 'underline', 'code', 'link'],
                'heading' => ['h2', 'h3'],
                'table' => [
                    'tableAddColumnBefore',
                    'tableAddColumnAfter',
                    'tableDeleteColumn',
                    'tableAddRowBefore',
                    'tableAddRowAfter',
                    'tableDeleteRow',
                    'tableMergeCells',
                    'tableSplitCell',
                    'tableToggleHeaderRow',
                    'tableToggleHeaderCell',
                    'tableDelete',
                ],
            ])
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory('posts');
    }
}
