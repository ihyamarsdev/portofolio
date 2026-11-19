<div class="tab-pane-content animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        {{-- Left column: JD input + progress + history (sticky on desktop) --}}
        <aside class="lg:col-span-4 xl:col-span-3 space-y-4 lg:sticky lg:top-4">
            @include('filament.pages.cv-generator.aimatch.jd-input')
            <x-cv-analysis-progress />
            @include('filament.pages.cv-generator.aimatch.history')
        </aside>

        {{-- Right column: results workspace --}}
        <section class="lg:col-span-8 xl:col-span-9 min-w-0 space-y-5">
            {{-- Header (always visible) --}}
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-zinc-400">{{ __('cv-aimatch.studio.eyebrow') }}</p>
                <h2 class="mt-0.5 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.studio.title') }}</h2>
                <p class="mt-1 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.subtitle') }}</p>
            </div>

            @if(! $isAnalyzing && $analysisResult)

                {{-- Executive summary strip --}}
                @include('filament.pages.cv-generator.aimatch.summary-strip')

                {{-- Segmented tabs --}}
                <div class="border-b border-zinc-200 dark:border-zinc-700">
                    <nav class="flex gap-1 -mb-px overflow-x-auto scrollbar-none" aria-label="{{ __('cv-aimatch.studio.eyebrow') }}">
                        @foreach([
                            'queue' => __('cv-aimatch.studio.tabs.queue'),
                            'evidence' => __('cv-aimatch.studio.tabs.evidence'),
                            'gaps' => __('cv-aimatch.studio.tabs.gaps'),
                            'rubric' => __('cv-aimatch.studio.tabs.rubric'),
                        ] as $key => $label)
                            <button type="button" wire:click="setAimatchTab('{{ $key }}')"
                                    @class([
                                        'shrink-0 px-3 py-2 text-xs font-medium border-b-2 transition-colors',
                                        'border-zinc-800 dark:border-zinc-200 text-zinc-900 dark:text-zinc-100' => $aimatchTab === $key,
                                        'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' => $aimatchTab !== $key,
                                    ])>
                                {{ $label }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                {{-- Tab content --}}
                <div class="pt-1">
                    @switch($aimatchTab)
                        @case('evidence')
                            @include('filament.pages.cv-generator.aimatch.evidence')
                            @break
                        @case('gaps')
                            @include('filament.pages.cv-generator.aimatch.gaps')
                            @break
                        @case('rubric')
                            @include('filament.pages.cv-generator.aimatch.rubric')
                            @break
                        @default
                            @include('filament.pages.cv-generator.aimatch.action-queue')
                    @endswitch
                </div>
            @elseif($isAnalyzing)
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 space-y-4 animate-pulse">
                    <div class="h-5 w-1/3 rounded bg-zinc-100 dark:bg-zinc-800"></div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @for($i = 0; $i < 4; $i++)
                            <div class="h-20 rounded bg-zinc-100 dark:bg-zinc-800"></div>
                        @endfor
                    </div>
                    <div class="h-32 rounded bg-zinc-100 dark:bg-zinc-800"></div>
                </div>
            @else
                <div class="rounded-lg border border-dashed border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-8 py-14 text-center">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400">
                        <x-heroicon-o-sparkles class="w-6 h-6" />
                    </span>
                    <h3 class="mt-3 text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.empty_state.title') }}</h3>
                    <p class="mt-1.5 mx-auto max-w-md text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.empty_state.hint') }}</p>
                    <x-filament::button wire:click="analyzeJobMatch" color="primary" class="mt-5">
                        <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                        <span>{{ __('cv-aimatch.empty_state.run_analysis') }}</span>
                    </x-filament::button>
                </div>
            @endif
        </section>
    </div>
</div>
