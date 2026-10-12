<x-layouts.app :title="$post->title">
    <article class="py-10 sm:py-14 bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container">
            <div class="max-w-3xl mx-auto">
                <header class="mb-8">
                    <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 font-black uppercase text-xs tracking-widest bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-full px-4 py-2.5 min-h-[44px] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none transition-all mb-6">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg> {{ __('messages.blog.back_to') }}
                    </a>

                    <span class="inline-flex font-mono text-xs font-black uppercase tracking-wider bg-[#2563EB] text-white px-2.5 py-1 rounded-full border-2 border-black mb-3">Article</span>
                    <h1 class="text-3xl sm:text-5xl font-black tracking-[-0.05em] leading-[0.95] text-black dark:text-[#FFFBF0]">{{ $post->title }}</h1>

                    @php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @if($displayDate)
                            <span class="inline-flex font-mono text-xs font-black bg-[#1E3A8A] dark:bg-[#FFFBF0] text-white dark:text-black px-2.5 py-1 rounded-full border-2 border-black">{{ $displayDate->format('F j, Y') }}</span>
                        @endif
                        <span class="inline-flex font-mono text-xs font-bold bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-full px-2.5 py-1">{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} {{ __('messages.blog.min_read') }}</span>
                    </div>
                </header>

                @php($imageSrc = $post->imageSrc())
                @if($imageSrc)
                    <div class="aspect-video overflow-hidden bg-white dark:bg-[#1E293B] border-[3px] border-black dark:border-[#CBD5E1] rounded-[16px] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)] mb-8">
                        <img src="{{ $imageSrc }}" alt="{{ $post->title }}" fetchpriority="high" decoding="async" class="h-full w-full object-cover">
                    </div>
                @endif

                <div class="brutal-card p-6 sm:p-8">
                    <div class="prose max-w-none prose-p:font-medium">
                        {!! Illuminate\Support\Str::markdown($post->content ?? '') !!}
                    </div>
                </div>

                <footer class="mt-6 brutal-card p-5 flex flex-wrap justify-between items-center gap-4 !bg-[#2563EB] dark:!bg-[#1E293B]">
                    <div class="text-sm font-bold text-white">
                        Thanks for reading! Have questions? <a href="{{ route('contact') }}" class="underline decoration-[3px] underline-offset-4 bg-[#172554] text-white dark:bg-[#2563EB] dark:text-white px-1">Get in touch</a>.
                    </div>
                    <div class="flex gap-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="w-11 h-11 min-w-[44px] min-h-[44px] inline-flex items-center justify-center bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[8px] shadow-[2px_2px_0_0_#111] dark:shadow-[2px_2px_0_0_rgba(203,213,225,0.9)] hover:bg-[#172554] hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors" title="Share on X">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="w-11 h-11 min-w-[44px] min-h-[44px] inline-flex items-center justify-center bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[8px] shadow-[2px_2px_0_0_#111] dark:shadow-[2px_2px_0_0_rgba(203,213,225,0.9)] hover:bg-[#00D1FF] hover:text-black transition-colors" title="Share on LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>
                </footer>
            </div>
        </div>
    </article>
</x-layouts.app>