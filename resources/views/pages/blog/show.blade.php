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
                    {!! Illuminate\Support\Str::markdown($post->content ?? '') !!}
                </div>

                <!-- Footer -->
                <footer class="mt-12 pt-8 border-t border-[--color-border]">
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="text-sm text-[--color-text-muted]">
                            Thanks for reading! Have questions? <a href="{{ route('contact') }}"
                                class="text-[--color-accent-emerald] hover:underline">Get in touch</a>.
                        </div>
                        {!! \Kudashevs\ShareButtons\Facades\ShareButtonsFacade::page(request()->url(), $post->title, [
    'block_prefix' => '<div class="flex gap-3 share-buttons">',
    'block_suffix' => '</div>',
])
    ->twitter(['class' => ' p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors', 'title' => 'Share on X'])
    ->linkedin(['class' => ' p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors', 'title' => 'Share on LinkedIn'])
    ->threads(['class' => ' p-2 bg-[--color-bg-secondary] rounded-lg text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors', 'title' => 'Share on Threads'])
    ->render() !!}
                    </div>
                </footer>
            </div>
        </div>
    </article>
</x-layouts.app>