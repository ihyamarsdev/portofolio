<div wire:ignore class="inline-flex items-center border border-line">
    @foreach($locales as $code => $label)
        <button
            type="button"
            wire:click="switch('{{ $code }}')"
            @class([
                'px-2.5 py-1 font-mono text-[11px] uppercase tracking-wider transition-colors duration-150',
                'text-accent' => $currentLocale === $code,
                'text-ink-3 hover:text-ink' => $currentLocale !== $code,
            ])
        >
            {{ $label }}
        </button>
        @if(! $loop->last)<span class="text-line">/</span>@endif
    @endforeach
</div>
