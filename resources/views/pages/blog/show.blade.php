<x-layouts.app :title="$post->title">
    <article class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="max-w-3xl mx-auto">
                <!-- Header -->
                <header class="mb-12">
                    <a href="{{ route('blog') }}"
                        class="inline-flex items-center gap-2 text-[--color-text-muted] hover:text-[--color-accent-emerald] mb-6 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Back to Blog
                    </a>

                    <h1 class="text-3xl sm:text-4xl font-bold text-[--color-text-primary] mb-4">{{ $post->title }}</h1>

                    @php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)
                    <div class="flex items-center gap-4 text-sm text-[--color-text-muted]">
                        @if($displayDate)
                            <time datetime="{{ $displayDate->toISOString() }}">
                                {{ $displayDate->format('F j, Y H:i') }}
                            </time>
                            <span>•</span>
                        @endif
                        <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($post->featured_image_url || $post->getFirstMediaUrl('featured_image'))
                    <div class="aspect-video rounded-xl overflow-hidden mb-12 bg-[--color-bg-secondary]">
                        <img src="{{ $post->featured_image_url ?: $post->getFirstMediaUrl('featured_image') }}" alt="{{ $post->title }}"
                            class="h-full w-full bg-sky-50 object-contain p-4 dark:bg-slate-950/40">
                    </div>
                @endif

                <!-- Content -->
                <div class="prose prose-invert prose-emerald max-w-none">
                    {!! Illuminate\Support\Str::markdown($post->content ?? '') !!}
                </div>

                <!-- Footer -->
                <footer class="mt-12 pt-8 border-t border-[--color-border]">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="text-sm text-[--color-text-muted]">
                            Thanks for reading! Have questions? <a href="{{ route('contact') }}"
                                class="text-[--color-accent-emerald] hover:underline">Get in touch</a>.
                        </div>
                        <div class="flex gap-3 share-buttons">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors"
                               title="Share on X">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors"
                               title="Share on LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="https://threads.net/intent/post?text={{ urlencode($post->title . ' ' . request()->url()) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors flex items-center justify-center"
                               title="Share on Threads">
                                <span class="text-xs font-bold px-1">Threads</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </article>
</x-layouts.app>