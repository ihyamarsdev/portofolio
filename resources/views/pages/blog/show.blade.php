<x-layouts.app :title="$post->title">
    @php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)
    @php($imageSrc = $post->imageSrc())

    <article class="section-container py-12 sm:py-16">
        <div class="mx-auto max-w-3xl">
            <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2 transition-colors duration-150 hover:text-accent">
                <span>←</span> {{ __('messages.blog.back_to') }}
            </a>

            <header class="mt-8 border-b border-line pb-8">
                <p class="font-mono text-[11px] uppercase tracking-[0.16em] text-accent">{{ __('messages.blog.eyebrow') }}</p>
                <h1 class="mt-4 text-3xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink sm:text-5xl">{{ $post->title }}</h1>

                <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 font-mono text-[11px] uppercase tracking-wider text-ink-3">
                    @if($displayDate)
                        <time datetime="{{ $displayDate->toDateString() }}">{{ $displayDate->format('F j, Y') }}</time>
                    @endif
                    @if($post->category)<span>{{ $post->category->name }}</span>@endif
                    <span>{{ ceil(str_word_count(strip_tags($post->content ?? '')) / 200) }} {{ __('messages.blog.min_read') }}</span>
                </div>
            </header>

            @if($imageSrc)
                <div class="mt-8 overflow-hidden border border-line bg-subtle">
                    <img src="{{ $imageSrc }}" alt="{{ $post->title }}" width="1600" height="900" fetchpriority="high" decoding="async" class="aspect-video w-full object-cover">
                </div>
            @endif

            <div class="prose measure mt-10">
                {!! Illuminate\Support\Str::markdown($post->content ?? '') !!}
            </div>

            <footer class="mt-12 flex flex-col gap-6 border-t border-line pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm leading-relaxed text-ink-2">
                    {{ __('messages.blog.thanks') }} <a href="{{ route('contact') }}" class="text-ink underline decoration-line underline-offset-2 transition-colors duration-150 hover:text-accent">{{ __('messages.blog.get_in_touch') }}</a>.
                </p>
                <div class="flex gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 w-9 items-center justify-center border border-line text-ink-2 transition-colors duration-150 hover:border-ink hover:text-ink" title="Share on X" aria-label="Share on X">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-9 w-9 items-center justify-center border border-line text-ink-2 transition-colors duration-150 hover:border-ink hover:text-ink" title="Share on LinkedIn" aria-label="Share on LinkedIn">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </footer>
        </div>
    </article>
</x-layouts.app>
