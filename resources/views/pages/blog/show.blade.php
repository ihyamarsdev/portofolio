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

                    <div class="flex items-center gap-4 text-sm text-[--color-text-muted]">
                        <time datetime="{{ $post->published_at->toISOString() }}">
                            {{ $post->published_at->format('F j, Y') }}
                        </time>
                        <span>•</span>
                        <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($post->getFirstMediaUrl('featured_image'))
                    <div class="aspect-video rounded-xl overflow-hidden mb-12 bg-[--color-bg-secondary]">
                        <img src="{{ $post->getFirstMediaUrl('featured_image') }}" alt="{{ $post->title }}"
                            class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Content -->
                <div class="prose prose-invert prose-emerald max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Footer -->
                <footer class="mt-12 pt-8 border-t border-[--color-border]">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="text-sm text-[--color-text-muted]">
                            Thanks for reading! Have questions? <a href="{{ route('contact') }}"
                                class="text-[--color-accent-emerald] hover:underline">Get in touch</a>.
                        </div>
                        <div class="flex gap-3">
                            <button
                                class="p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors"
                                title="Share on Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </button>
                            <button
                                class="p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors"
                                title="Share on LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </article>
</x-layouts.app>