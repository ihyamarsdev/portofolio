<x-layouts.app title="{{ __('messages.blog.title') }}">
    <div class="section-container py-14 sm:py-20">
        <x-ui.section-header
            :index="__('messages.blog.eyebrow')"
            :as="'h1'"
            :title="__('messages.blog.heading')"
            :description="__('messages.blog.subtitle')" />

        @if($posts->isNotEmpty())
            @php($firstPost = $posts->first())
            @php($firstImage = $firstPost->imageSrc())
            @php($firstDate = $firstPost->published_at ?? $firstPost->updated_at ?? $firstPost->created_at)

            <a href="{{ route('blog.show', $firstPost->slug) }}" class="group mt-10 grid gap-6 border border-line bg-surface p-5 transition-colors duration-150 hover:border-ink sm:p-7 md:grid-cols-12 md:items-center">
                <div class="md:col-span-7">
                    <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-accent">{{ __('messages.ux_pages.featured_post') }}</p>
                    <h2 class="mt-3 text-2xl font-semibold leading-[1.15] tracking-[-0.02em] text-ink transition-colors duration-150 group-hover:text-accent sm:text-3xl">{{ $firstPost->title }}</h2>
                    <p class="measure mt-3 text-sm leading-relaxed text-ink-2">{{ $firstPost->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($firstPost->content ?? ''), 240) }}</p>
                    <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 font-mono text-[11px] uppercase tracking-wider text-ink-3">
                        @if($firstDate)<time datetime="{{ $firstDate->toDateString() }}">{{ $firstDate->format('M j, Y') }}</time>@endif
                        @if($firstPost->category)<span>{{ $firstPost->category->name }}</span>@endif
                        <span>{{ ceil(str_word_count(strip_tags($firstPost->content ?? '')) / 200) }} {{ __('messages.blog.min_read') }}</span>
                    </div>
                </div>
                @if($firstImage)
                    <div class="aspect-video overflow-hidden border border-line bg-subtle md:col-span-5">
                        <img src="{{ $firstImage }}" alt="{{ $firstPost->title }}" width="800" height="450" loading="lazy" decoding="async" class="h-full w-full object-cover">
                    </div>
                @endif
            </a>

            @if($posts->count() > 1)
                <div class="mt-10 border-t border-line">
                    @foreach($posts->slice(1) as $post)
                        <x-ui.post-row :post="$post" :index="str_pad($loop->iteration + 1, 2, '0', STR_PAD_LEFT)" />
                    @endforeach
                </div>
            @endif

            @if($posts->hasPages())
                <div class="mt-10">{{ $posts->links() }}</div>
            @endif
        @else
            <p class="mt-10 border border-line bg-surface p-10 text-center text-sm text-ink-2">{{ __('messages.blog.no_posts_yet') }}</p>
        @endif

        <section class="mt-16 flex flex-col gap-6 border-t border-line pt-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-accent">{{ __('messages.blog.subscribe') }}</p>
                <h2 class="mt-3 text-2xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink">{{ __('messages.blog.stay_updated') }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-ink-2">{{ __('messages.blog.subscribe_desc') }}</p>
            </div>
            <x-ui.button :href="route('contact')" variant="secondary" size="sm">{{ __('messages.blog.contact_for_updates') }}</x-ui.button>
        </section>
    </div>
</x-layouts.app>
