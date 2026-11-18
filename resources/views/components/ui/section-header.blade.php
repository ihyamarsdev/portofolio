@props([
    'index' => null,
    'title' => '',
    'description' => null,
    'as' => 'h2',
])

<div class="border-t border-line pt-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            @if($index)
                <p class="font-mono text-[11px] font-medium uppercase tracking-[0.18em] text-ink-3">{{ $index }}</p>
            @endif

            @if($as === 'h1')
                <h1 class="mt-2 text-3xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink sm:text-4xl">{{ $title }}</h1>
            @else
                <h2 class="mt-2 text-2xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink sm:text-3xl">{{ $title }}</h2>
            @endif

            @if($description)
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-ink-2">{{ $description }}</p>
            @endif
        </div>

        @if(isset($action))
            <div class="shrink-0">{{ $action }}</div>
        @endif
    </div>
</div>
