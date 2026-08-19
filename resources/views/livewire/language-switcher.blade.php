<div wire:ignore class="fi-language-switcher flex items-center gap-1 rounded-lg bg-gray-500/10 px-1 py-1">
    @foreach($locales as $code => $label)
        <button
            type="button"
            wire:click="switch('{{ $code }}')"
            @class([
                'rounded-md px-2 py-1 text-xs font-semibold transition',
                'bg-white text-gray-900 shadow-sm dark:bg-white/20 dark:text-white' => $currentLocale === $code,
                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' => $currentLocale !== $code,
            ])
        >
            {{ $label }}
        </button>
    @endforeach
</div>
