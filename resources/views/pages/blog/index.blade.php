<x-layouts.app title="Blog">
    <section class="bg-bg-primary">
        <div class="section-container py-16 sm:py-24">
            <div class="mb-10 max-w-3xl">
                <p class="font-mono text-xs uppercase tracking-widest text-accent-sky">Writing</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-[-.05em] text-text-primary sm:text-6xl">Blog, notes, field reports.</h1>
                <p class="mt-5 text-base leading-8 text-text-secondary sm:text-lg">Technical articles, tutorials, and case studies about DevOps, infrastructure, automation, and product delivery.</p>
            </div>
            <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
                <div class="grid gap-5">
                    @forelse($posts as $post)
                        <article class="group rounded-2xl bg-bg-card p-5 shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition hover:-translate-y-1 dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)] sm:p-6">
                            <div class="mb-4 flex flex-wrap items-center gap-3 text-xs text-text-muted">
                                <time datetime="{{ $post->published_at->toISOString() }}">{{ $post->published_at->format('M j, Y') }}</time>
                                <span>•</span>
                                <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                                @if($post->category)<span class="rounded-full bg-sky-500/10 px-2 py-1 text-accent-sky">{{ $post->category->name }}</span>@endif
                            </div>
                            <a href="{{ route('blog.show', $post->slug) }}"><h2 class="text-2xl font-semibold tracking-[-.04em] text-text-primary group-hover:text-accent-sky">{{ $post->title }}</h2></a>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-text-secondary">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 220) }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="mt-5 inline-flex text-sm font-semibold text-accent-sky">Read more →</a>
                        </article>
                    @empty
                        <div class="rounded-2xl bg-bg-card p-10 text-center text-text-secondary shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">No posts yet.</div>
                    @endforelse
                    <div>{{ $posts->links() }}</div>
                </div>
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="rounded-2xl bg-text-primary p-6 text-bg-primary">
                        <p class="font-mono text-xs uppercase tracking-widest opacity-70">Subscribe</p>
                        <h3 class="mt-2 text-xl font-semibold tracking-[-.03em]">Stay updated.</h3>
                        <p class="mt-3 text-sm leading-6 opacity-75">New articles on infrastructure, automation, and Laravel engineering.</p>
                        <a href="{{ route('contact') }}" class="mt-5 inline-flex rounded-lg bg-bg-primary px-4 py-2 text-sm font-semibold text-text-primary">Contact for updates</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
