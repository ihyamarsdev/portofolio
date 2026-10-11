<x-layouts.app title="Blog">
    <section class="bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container py-12 sm:py-16">
            <div class="mb-8 max-w-3xl">
                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#FF90E8] text-black px-2.5 py-1 rounded-[6px] border-[2.5px] border-black">{{ __('messages.blog.eyebrow') }}</span>
                <h1 class="mt-3 text-4xl font-black tracking-[-0.06em] leading-none text-black dark:text-[#FFFBF0] sm:text-6xl">{{ __('messages.blog.heading') }}</h1>
                <p class="mt-4 text-base leading-7 font-medium text-[#2a2a2a] dark:text-[#d6d6d6] border-l-[4px] border-black dark:border-[#CBD5E1] pl-4 sm:text-lg">{{ __('messages.blog.subtitle') }}</p>
            </div>
            @if($posts->count() > 0)
                @php($firstPost = $posts->first())
                <article class="group relative brutal-card p-0 overflow-hidden mb-8 grid md:grid-cols-12 gap-0">
                    <a href="{{ route('blog.show', $firstPost->slug) }}" class="absolute inset-0 z-10" aria-label="Read {{ $firstPost->title }}"></a>
                    @php($firstImage = $firstPost->imageSrc())
                    @if($firstImage)
                        <div class="aspect-video md:aspect-auto md:h-full md:col-span-5 bg-white dark:bg-[#1E293B] border-b-[3px] md:border-b-0 md:border-r-[3px] border-black dark:border-[#CBD5E1] overflow-hidden">
                            <img src="{{ $firstImage }}" alt="{{ $firstPost->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                        </div>
                    @endif
                    <div class="{{ $firstImage ? 'md:col-span-7' : 'md:col-span-12' }} p-6 sm:p-8 flex flex-col justify-between flex-1">
                        <div>
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                <span class="brutal-badge brutal-badge--yellow text-xs font-black">★ {{ __('messages.ux_pages.featured_post') }}</span>
                                @php($displayDate = $firstPost->published_at ?? $firstPost->updated_at ?? $firstPost->created_at)
                                @if($displayDate)
                                    <span class="inline-flex font-mono text-xs font-black uppercase tracking-widest bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-2.5 py-1 rounded-full border-2 border-black">{{ $displayDate->format('M j, Y') }}</span>
                                @endif
                                <span class="inline-flex font-mono text-xs font-bold uppercase bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-full px-2.5 py-1">{{ ceil(str_word_count(strip_tags($firstPost->content)) / 200) }} min</span>
                                @if($firstPost->category)<span class="brutal-badge brutal-badge--cyan text-xs">{{ $firstPost->category->name }}</span>@endif
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-[-0.04em] leading-tight text-black dark:text-white group-hover:underline decoration-[3px] underline-offset-4">{{ $firstPost->title }}</h2>
                            <p class="mt-3 text-sm sm:text-base leading-6 sm:leading-7 font-medium text-[#2a2a2a] dark:text-[#d6d6d6]">{{ $firstPost->excerpt ?: Str::limit(strip_tags($firstPost->content), 240) }}</p>
                        </div>
                        <div class="mt-6 pt-2">
                            <span class="inline-flex items-center gap-1 bg-black dark:bg-[#FFFBF0] text-white dark:text-black font-black uppercase text-xs tracking-tight px-4 py-2 min-h-[40px] rounded-[8px] border-2 border-black">{{ __('messages.blog.read_more') }} <span class="group-hover:translate-x-1 transition-transform">→</span></span>
                        </div>
                    </div>
                </article>

                @if($posts->count() > 1)
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                        @foreach($posts->slice(1) as $post)
                            <article class="group relative brutal-card p-0 overflow-hidden flex flex-col h-full">
                                <a href="{{ route('blog.show', $post->slug) }}" class="absolute inset-0 z-10" aria-label="Read {{ $post->title }}"></a>
                                @php($postImage = $post->imageSrc())
                                @if($postImage)
                                    <div class="aspect-video w-full bg-white dark:bg-[#1E293B] border-b-[3px] border-black dark:border-[#CBD5E1] overflow-hidden">
                                        <img src="{{ $postImage }}" alt="{{ $post->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                                    </div>
                                @endif
                                <div class="p-5 sm:p-6 flex flex-col flex-1">
                                    @php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)
                                    <div class="mb-3 flex flex-wrap items-center gap-2">
                                        @if($displayDate)
                                            <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-widest bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-2 py-1 rounded-full border-2 border-black">{{ $displayDate->format('M j, Y') }}</span>
                                        @endif
                                        <span class="inline-flex font-mono text-[11px] font-bold uppercase bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-full px-2 py-1">{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min</span>
                                        @if($post->category)<span class="brutal-badge brutal-badge--yellow text-[11px]">{{ $post->category->name }}</span>@endif
                                    </div>
                                    <h2 class="text-xl font-black tracking-[-0.04em] leading-tight text-black dark:text-white group-hover:underline decoration-[3px] underline-offset-4">{{ $post->title }}</h2>
                                    <p class="mt-2 line-clamp-3 text-sm leading-6 font-medium text-[#2a2a2a] dark:text-[#d6d6d6]">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 180) }}</p>
                                    <div class="mt-auto pt-4">
                                        <span class="inline-flex items-center gap-1 bg-black dark:bg-[#FFFBF0] text-white dark:text-black font-black uppercase text-xs tracking-tight px-3 py-1.5 min-h-[40px] rounded-[8px] border-2 border-black">{{ __('messages.blog.read_more') }} <span class="group-hover:translate-x-1 transition-transform">→</span></span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if($posts->hasPages())
                    <div class="mt-4 mb-8">{{ $posts->links() }}</div>
                @endif
            @else
                <div class="brutal-card p-10 text-center font-black uppercase mb-8">{{ __('messages.blog.no_posts_yet') }}</div>
            @endif

            <div class="mt-6 brutal-card p-6 sm:p-7 bg-[#111] dark:bg-[#1E293B] text-[#FFFBF0] relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-[3px] border-black dark:border-[#CBD5E1] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)]">
                <div class="max-w-xl relative z-10">
                    <span class="font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#2563EB] text-white inline-flex px-2 py-1 rounded border-2 border-black">{{ __('messages.blog.subscribe') }}</span>
                    <h3 class="mt-2 text-xl sm:text-2xl font-black tracking-[-0.03em] text-white">{{ __('messages.blog.stay_updated') }}</h3>
                    <p class="mt-1 text-sm font-medium text-[#d6d6d6]">{{ __('messages.blog.subscribe_desc') }}</p>
                </div>
                <div class="relative z-10 shrink-0">
                    <a href="{{ route('contact') }}" class="brutal-btn bg-white text-black border-black text-xs sm:text-sm px-5 py-3 min-h-[44px] inline-flex items-center gap-2 hover:bg-[#00D1FF] transition-colors">
                        {{ __('messages.blog.contact_for_updates') }} →
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>