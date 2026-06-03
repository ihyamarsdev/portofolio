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
                            ->description('Write and structure the post content.')
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
                                    ->fileAttachmentsDirectory('posts'),

                            ])
                            ->columns(2),

                        Section::make('Excerpt & SEO')
                            ->description('Post summary and search engine metadata.')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Textarea::make('excerpt')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->columnSpanFull()
                                    ->hint(fn ($state) => strlen($state ?? '').' / 160 chars')
                                    ->hintIcon('heroicon-o-information-circle'),

                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->hint(fn ($state) => strlen($state ?? '').' / 60 chars')
                                    ->hintIcon('heroicon-o-information-circle'),

                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->hint(fn ($state) => strlen($state ?? '').' / 160 chars')
                                    ->hintIcon('heroicon-o-information-circle'),
                            ])
                            ->columns(2),

                        Section::make('Live Post Viewport')
                            ->description('Real-time responsive rendering of the article on the public site.')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Placeholder::make('viewport')
                                    ->content(fn (?Post $record) => $record && $record->exists ? new HtmlString(
                                        '<div class="w-full bg-[#0b0b10] border border-[#2d2d3a] rounded-xl overflow-hidden shadow-2xl relative" style="height: 600px;">
                                            <div class="bg-slate-900 border-b border-[#2d2d3a] px-4 py-2 flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-1.5 shrink-0 select-none">
                                                    <span class="w-3 h-3 rounded-full bg-[#ff5f56]"></span>
                                                    <span class="w-3 h-3 rounded-full bg-[#ffbd2e]"></span>
                                                    <span class="w-3 h-3 rounded-full bg-[#27c93f]"></span>
                                                </div>
                                                <div class="flex items-center gap-2 bg-[#09090d] border border-slate-700 rounded-md px-3 py-1 text-xs text-slate-400 font-mono w-full max-w-[450px] overflow-hidden truncate">
                                                    <span class="text-emerald-500 shrink-0 select-none">HTTPS://</span>
                                                    <span>'.e(request()->getHost()).'/blog/'.e($record->slug).'</span>
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <button type="button" onclick="document.getElementById(\'live-post-viewport\').src = document.getElementById(\'live-post-viewport\').src" class="p-1 text-slate-400 hover:text-slate-200 transition-colors flex items-center justify-center rounded bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-slate-600 w-7 h-7" title="Reload Preview">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m0 0l-3 3-3-3"></path></svg>
                                                    </button>
                                                    <a href="/blog/'.e($record->slug).'" target="_blank" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 rounded text-[11px] font-semibold flex items-center gap-1 transition-all h-7 select-none">
                                                        <span>Open Tab</span>
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe id="live-post-viewport" sandbox="allow-same-origin allow-forms" src="/blog/'.e($record->slug).'?t='.($record->updated_at ? $record->updated_at->timestamp : time()).'" class="w-full h-full border-none" style="height: calc(100% - 37px); min-height: 563px;"></iframe>
                                        </div>'
                                    ) : new HtmlString(
                                        '<div class="w-full bg-[#0b0b10] border border-[#2d2d3a] rounded-xl p-8 flex flex-col items-center justify-center text-center text-slate-400 font-space-grotesk" style="height: 300px;">
                                            <svg class="w-12 h-12 text-slate-600 mb-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                            <h4 class="font-bold text-white text-sm">Post Viewport Uninitialized</h4>
                                            <p class="text-xs text-slate-500 max-w-[280px] mt-1">Please save your post draft once to instantiate a live preview viewport anchor.</p>
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
                                    ->image()
                                    ->imageEditor()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('675'),
                                TextInput::make('featured_image_url')
                                    ->label('Internet Image URL')
                                    ->url()
                                    ->prefixIcon('heroicon-o-photo')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->helperText('Use this when the featured image is hosted on the internet instead of uploaded locally.'),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ]),
            ]);
    }
}
