@php
    $icons = $this->icons;
    $visibleIcons = array_slice($icons, 0, $this->perPage);
    $hasMore = count($icons) > $this->perPage;
@endphp

<div>
    {{-- Browse button --}}
    <button
        type="button"
        wire:click="$set('showModal', true)"
        class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 bg-white px-3.5 py-2 text-xs font-semibold text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/>
        </svg>
        Browse Icons
    </button>

    {{-- Modal structure using Alpine --}}
    <div
        x-data="{ open: @entangle('showModal') }"
        x-show="open"
        x-cloak
        x-on:keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-10"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-zinc-950/70 dark:bg-zinc-950/80"
            x-on:click="open = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        {{-- Modal panel --}}
        <div
            class="relative z-10 flex w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xl transition-all duration-300"
            style="max-height: 85vh;"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            {{-- Header --}}
            <div class="flex items-center justify-between gap-4 border-b border-zinc-200 dark:border-zinc-700 px-5 py-4 bg-zinc-50/50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-3">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">Browse Brand Icons</h3>
                    <span class="rounded-full bg-zinc-200 dark:bg-zinc-700 px-2.5 py-0.5 text-[10px] font-semibold text-zinc-600 dark:text-zinc-400">{{ count($icons) }}</span>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <div class="relative w-64">
                        <input
                            type="text"
                            wire:model.live.debounce.200ms="search"
                            placeholder="Search 6000+ brand SVGs..."
                            class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 py-2 pl-8 pr-8 text-xs text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-zinc-300/40"
                        />
                        <svg class="pointer-events-none absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        @if($search !== '')
                            <button
                                type="button"
                                wire:click="$set('search', '')"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded p-0.5 text-zinc-400 dark:text-zinc-500 transition hover:text-zinc-900 dark:hover:text-zinc-100"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    {{-- Close Button --}}
                    <button
                        type="button"
                        x-on:click="open = false"
                        class="rounded-lg p-1.5 text-zinc-400 dark:text-zinc-500 transition hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-zinc-100"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Icons grid body --}}
            <div class="relative flex-1 overflow-y-auto p-6" style="max-height: calc(85vh - 80px);">
                {{-- Livewire loading overlay --}}
                <div wire:loading.delay class="absolute inset-0 z-20 flex items-center justify-center bg-white/60 dark:bg-zinc-900/60">
                    <svg class="h-8 w-8 animate-spin text-zinc-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                @if(empty($visibleIcons))
                    <div class="flex flex-col items-center justify-center gap-3 py-20">
                        <svg class="h-12 w-12 text-zinc-300 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/>
                        </svg>
                        <p class="text-sm font-semibold text-zinc-500 dark:text-zinc-400">No brand icons match your search</p>
                    </div>
                @else
                    <div class="grid grid-cols-[repeat(auto-fill,minmax(80px,1fr))] gap-2.5">
                        @foreach($visibleIcons as $icon)
                            <button
                                type="button"
                                wire:click="select('{{ $icon['slug'] }}')"
                                title="{{ $icon['title'] }}"
                                class="group flex aspect-square w-full flex-col items-center justify-between rounded-lg border border-zinc-200/60 dark:border-zinc-700/50 bg-zinc-50/30 dark:bg-zinc-800/20 p-2 transition-all duration-200 hover:-translate-y-0.5 hover:border-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/60"
                            >
                                {{-- Unified light background logo container --}}
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white p-1.5 shadow-sm border border-slate-100 transition-transform duration-300 group-hover:scale-105">
                                    <img
                                        src="https://cdn.jsdelivr.net/gh/glincker/thesvg@main/public/icons/{{ $icon['slug'] }}/default.svg"
                                        alt="{{ $icon['title'] }}"
                                        loading="lazy"
                                        class="h-full w-full object-contain"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'"
                                    />
                                    <span
                                        style="display:none"
                                        class="flex h-full w-full items-center justify-center rounded bg-slate-100 text-[10px] font-bold uppercase text-slate-500"
                                    >
                                        {{ mb_substr($icon['title'], 0, 1) }}
                                    </span>
                                </div>
                                <span class="mt-1.5 w-full truncate text-center text-[9px] font-semibold text-zinc-600 dark:text-zinc-400 transition-colors duration-200 group-hover:text-zinc-900 dark:group-hover:text-zinc-100">
                                    {{ $icon['title'] }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    @if($hasMore)
                        <div class="mt-8 flex items-center justify-center border-t border-zinc-200 dark:border-zinc-700 pt-6">
                            <button
                                type="button"
                                wire:click="loadMore"
                                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 dark:border-zinc-700 px-6 py-2.5 text-xs font-semibold text-zinc-500 dark:text-zinc-400 transition hover:border-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                </svg>
                                Show more icons ({{ count($icons) - $this->perPage }} remaining)
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
