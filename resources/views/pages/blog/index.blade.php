<x-layouts.app title="Blog">
    <section class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="max-w-3xl mb-12">
                <h1 class="text-4xl font-bold text-[--color-text-primary] mb-4">Blog</h1>
                <p class="text-lg text-[--color-text-secondary]">
                    Technical articles, tutorials, and case studies about DevOps, infrastructure, and automation.
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Posts List -->
                <div class="lg:col-span-2 space-y-6">
                    @php
                        $posts = \App\Models\Post::published()->orderBy('published_at', 'desc')->get();
                    @endphp
                    
                    @forelse($posts as $post)
                        <article class="glass-card p-6 group">
                            <div class="flex items-center gap-4 mb-4 text-sm text-[--color-text-muted]">
                                <time datetime="{{ $post->published_at->toISOString() }}">
                                    {{ $post->published_at->format('M j, Y') }}
                                </time>
                                <span>•</span>
                                <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                            </div>
                            
                            <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                <h2 class="text-xl font-semibold text-[--color-text-primary] mb-3 group-hover:text-[--color-accent-emerald] transition-colors">
                                    {{ $post->title }}
                                </h2>
                            </a>
                            
                            @if($post->excerpt)
                                <p class="text-[--color-text-secondary] mb-4">{{ $post->excerpt }}</p>
                            @else
                                <p class="text-[--color-text-secondary] mb-4">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                            @endif
                            
                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-2 text-[--color-accent-emerald] text-sm hover:underline">
                                Read more
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </article>
                    @empty
                        <div class="glass-card p-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-[--color-bg-secondary] rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-[--color-text-muted]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-[--color-text-primary] mb-2">No posts yet</h3>
                            <p class="text-[--color-text-secondary]">Blog posts will appear here once published through the admin panel.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 sticky top-24">
                        <h3 class="font-semibold text-[--color-text-primary] mb-4">Subscribe</h3>
                        <p class="text-sm text-[--color-text-secondary] mb-4">
                            Get notified when I publish new articles about DevOps and infrastructure.
                        </p>
                        <form class="space-y-3">
                            <input type="email" placeholder="your@email.com" class="w-full px-4 py-2 bg-[--color-bg-tertiary] border border-[--color-border] rounded-lg text-[--color-text-primary] placeholder-[--color-text-muted] focus:outline-none focus:border-[--color-accent-emerald]">
                            <button type="submit" class="btn-primary w-full justify-center">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
