@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-md border font-medium transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-canvas';
    $sizes = ['sm' => 'px-3 py-2 text-xs', 'md' => 'px-4 py-2.5 text-sm'];
    $variants = [
        'primary' => 'border-accent bg-accent text-accent-contrast hover:bg-transparent hover:text-accent',
        'secondary' => 'border-line bg-surface text-ink hover:border-ink hover:bg-subtle',
        'ghost' => 'border-transparent bg-transparent text-ink-2 hover:border-line hover:text-ink',
    ];
    $classes = $base.' '.$sizes[$size].' '.$variants[$variant];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
