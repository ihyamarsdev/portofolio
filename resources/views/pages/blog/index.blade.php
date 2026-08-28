<x-layouts.app title="Blog">
    <section class="bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container py-12 sm:py-16">
            <div class="mb-8 max-w-3xl">
                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#FF90E8] text-black px-2.5 py-1 rounded-[6px] border-[2.5px] border-black">Writing — Field Notes</span>
                <h1 class="mt-3 text-4xl font-black tracking-[-0.06em] leading-none text-black dark:text-[#FFFBF0] sm:text-6xl">Blog, notes,<br><span class="bg-[#2563EB] text-white px-2 border-[3px] border-black shadow-[5px_5px_0_0_#111] inline-block rotate-[-0.5deg]">field reports.</span></h1>
                <p class="mt-4 text-base leading-7 font-medium text-[#2a2a2a] dark:text-[#d6d6d6] border-l-[4px] border-black dark:border-[#CBD5E1] pl-4 sm:text-lg">Technical articles, tutorials, and case studies about DevOps, infrastructure, automation, and product delivery.</p>
            </div>
            <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
                <div class="grid gap-5">
                    @forelse($posts as $post)
                        <article class="group relative brutal-card p-5 sm:p-6 flex flex-col">
                            <a href="{{ route('blog.show', $post->slug) }}" class="absolute inset-0 z-10 rounded-[12px]" aria-label="Read {{ $post->title }}"></a>
                            @php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                @if($displayDate)
                                    <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-widest bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-2 py-1 rounded-full border-2 border-black">{{ $displayDate->format('M j, Y') }}</span>
                                @endif
                                <span class="inline-flex font-mono text-[11px] font-bold uppercase bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-full px-2 py-1">{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min</span>
                                @if($post->category)<span class="brutal-badge brutal-badge--yellow text-[11px]">{{ $post->category->name }}</span>@endif
                            </div>
                            <h2 class="text-2xl font-black tracking-[-0.04em] leading-tight text-black dark:text-white group-hover:underline decoration-[3px] underline-offset-4">{{ $post->title }}</h2>
                            <p class="mt-2 line-clamp-3 text-sm leading-6 font-medium text-[#2a2a2a] dark:text-[#d6d6d6]">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 220) }}</p>
                            <span class="mt-4 inline-flex self-start bg-black dark:bg-[#FFFBF0] text-white dark:text-black font-black uppercase text-xs tracking-tight px-3 py-1 rounded-[8px] border-2 border-black">Read more <span class="group-hover:translate-x-1 transition-transform">→</span></span>
                        </article>
                    @empty
                        <div class="brutal-card p-10 text-center font-black uppercase">No posts yet.</div>
                    @endforelse
                    <div class="mt-2">{{ $posts->links() }}</div>
                </div>
                <aside class="lg:sticky lg:top-[84px] lg:self-start">
                    <div class="bg-[#111] dark:bg-[#1E293B] border-[3px] border-black dark:border-[#CBD5E1] rounded-[16px] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)] p-6 text-[#FFFBF0] relative overflow-hidden">
                        <div class="absolute -top-4 -right-4 w-16 h-16 bg-[#2563EB] border-[3px] border-black rounded-full"></div>
                        <p class="font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#2563EB] text-white inline-flex px-2 py-1 rounded border-2 border-black">Subscribe</p>
                        <h3 class="mt-3 text-xl font-black tracking-[-0.03em] relative">Stay updated.</h3>
                        <p class="mt-2 text-sm leading-6 font-medium text-[#d6d6d6] relative">New articles on infrastructure, automation, and Laravel engineering.</p>
                        <a href="{{ route('contact') }}" class="mt-5 inline-flex brutal-btn bg-white text-black border-black text-xs relative">Contact for updates →</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>