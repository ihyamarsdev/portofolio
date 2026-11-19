@php
    $statePath = $getStatePath();
    $state = $getState();
    $placeholder = $getPlaceholder();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    <div
        class="flex items-center gap-3"
        x-data="{
            state: @entangle($statePath),
            updateIcon(url) {
                this.state = url;
            }
        }"
        x-on:svg-icon-selected.window="updateIcon($event.detail.url)"
    >
        {{-- Icon preview --}}
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800 shadow-sm overflow-hidden p-1.5">
            <template x-if="state && state.startsWith('http')">
                <img :src="state" alt="Preview" class="h-full w-full object-contain" />
            </template>
            <template x-if="state && state.startsWith('devicon-')">
                <i :class="state + ' text-xl text-zinc-700 dark:text-zinc-200'"></i>
            </template>
            <template x-if="state && !state.startsWith('http') && !state.startsWith('devicon-')">
                <span x-text="state" class="text-lg leading-none text-zinc-800 dark:text-white"></span>
            </template>
            <template x-if="!state">
                <svg class="h-4 w-4 text-zinc-400 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/></svg>
            </template>
        </div>

        {{-- Text input --}}
        <div class="min-w-0 flex-1">
            <x-filament::input.wrapper
                :valid="! $errors->has($statePath)"
            >
                <x-filament::input
                    type="text"
                    x-model="state"
                    placeholder="{{ $placeholder }}"
                />
            </x-filament::input.wrapper>
        </div>

        {{-- Browse button --}}
        <livewire:svg-icon-picker />
    </div>
</x-dynamic-component>
