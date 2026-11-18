@props([
    'post',
    'index' => null,
])

@php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)

<a href="{{ route('blog.show', $post->slug) }}" class="group grid grid-cols-12 gap-x-4 gap-y-1 border-b border-line py-5 transition-colors duration-150 hover:bg-subtle">
    <div class="col-span-12 flex items-center gap-3 font-mono text-[11px] uppercase tracking-[0.1em] text-ink-3 sm:col-span-3 sm:flex-col sm:items-start sm:gap-1">
        @if($displayDate)
            <time datetime="{{ $displayDate->toDateString() }}">{{ $displayDate->format('M j, Y') }}</time>
        @endif
        @if($post->category)
            <span class="text-ink-3/80">{{ $post->category->name }}</span>
        @endif
    </div>

    <div class="col-span-12 sm:col-span-9">
        <div class="flex items-start justify-between gap-4">
            <h3 class="text-base font-semibold leading-snug tracking-[-0.02em] text-ink transition-colors duration-150 group-hover:text-accent">{{ $post->title }}</h3>
            @if($index)
                <span class="shrink-0 font-mono text-[11px] tracking-[0.12em] text-ink-3">{{ $index }}</span>
            @endif
        </div>
        <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-ink-2">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 160) }}</p>
        <span class="mt-2 inline-flex items-center gap-1 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2 transition-colors duration-150 group-hover:text-accent">
            {{ __('messages.ux_home.read_more') }} <span class="inline-block transition-transform duration-150 group-hover:translate-x-1">→</span>
        </span>
    </div>
</a>
