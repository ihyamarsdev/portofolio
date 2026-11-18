@props(['accent' => false])

@php
    $classes = 'inline-flex items-center border px-2 py-1 font-mono text-[10px] font-medium uppercase leading-none tracking-[0.12em] ';
    $classes .= $accent ? 'border-accent/40 text-accent' : 'border-line text-ink-2';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
