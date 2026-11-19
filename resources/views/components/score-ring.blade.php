@props([
    'value' => null,       // int|float|null 0-100; null renders an empty track
    'size' => 64,          // rendered diameter in px
    'strokeWidth' => 4.5,
    'centerClass' => 'text-sm',
    'variant' => 'default', // default | muted
])

@php
    $pct = is_numeric($value) ? (int) max(0, min(100, $value)) : null;
    $radius = 20;
    $circumference = 2 * M_PI * $radius;
    $arcClass = $pct === null
        ? 'text-zinc-400 dark:text-zinc-500'
        : ($pct >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($pct >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400'));
@endphp

<div class="relative inline-flex items-center justify-center {{ $arcClass }}" style="width: {{ $size }}px; height: {{ $size }}px;">
    <svg viewBox="0 0 48 48" class="absolute inset-0 w-full h-full -rotate-90" aria-hidden="true">
        <circle cx="24" cy="24" r="{{ $radius }}" fill="none" stroke="currentColor" stroke-width="{{ $strokeWidth }}"
                class="text-zinc-200 dark:text-zinc-700" />
        @if($pct !== null)
            <circle cx="24" cy="24" r="{{ $radius }}" fill="none" stroke="currentColor" stroke-width="{{ $strokeWidth }}"
                    stroke-linecap="round" stroke-dasharray="{{ round($circumference, 2) }}"
                    stroke-dashoffset="{{ round($circumference * (1 - $pct / 100), 2) }}" />
        @endif
    </svg>
    <span class="font-bold font-mono leading-none {{ $centerClass }} {{ $arcClass }}">
        {{ $pct === null ? '-' : $pct.'%' }}
    </span>
</div>
