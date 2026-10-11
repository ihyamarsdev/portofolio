<x-layouts.app title="Home">
    @php
        $homeSettings = $homeSettings ?? \App\Models\HomeSettings::getSettings();
        $profile = \App\Models\Profile::query()->where('is_primary', true)->first() ?? \App\Models\Profile::query()->first();
        $resumeTemplate = $profile?->cv_template ?: 'ats';
        $projects = \App\Models\Project::query()->with('media')->where('is_featured', true)->orderBy('order')->latest()->take(3)->get();
        $posts = \App\Models\Post::published()->latest('published_at')->take(3)->get();
    @endphp

    <!-- HERO — brutal minimalist -->
    <section class="relative overflow-hidden bg-[#FFFBF0] dark:bg-[#0F172A] border-b-[3px] border-black dark:border-[#CBD5E1]">
        <div class="absolute inset-0 brutal-grid opacity-[0.7]"></div>
        <!-- decorative brutal shapes -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#2563EB] border-[3px] border-black dark:border-[#CBD5E1] rounded-[24px] rotate-12 hidden lg:block"></div>
        <div class="absolute top-32 -left-6 w-16 h-16 bg-[#00D1FF] border-[3px] border-black rounded-full hidden lg:block"></div>
        <div class="absolute bottom-6 right-[45%] w-8 h-8 bg-[#FF90E8] border-[2.5px] border-black rotate-45 hidden lg:block"></div>

        <div class="section-container relative py-12 sm:py-16 lg:py-20">
            <div class="grid items-start gap-8 lg:grid-cols-[1.15fr_.85fr] lg:gap-10">
                <div class="min-w-0">
                    @if($homeSettings->availability_active)
                        <div class="mb-5 inline-flex max-w-full items-center gap-2 bg-[#B8FF66] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-full px-3.5 py-1.5 shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)]">
                            <span class="w-2.5 h-2.5 shrink-0 rounded-full bg-black border border-black animate-pulse"></span>
                            <span class="font-mono text-xs font-black uppercase tracking-widest text-black truncate min-w-0">{{ $homeSettings->availability_status }}</span>
                        </div>
                    @endif
                    <h1 class="max-w-4xl text-[2.5rem] font-black leading-[0.9] tracking-[-0.06em] text-black dark:text-[#FFFBF0] sm:text-6xl lg:text-[4.5rem]">
                        <span class="block">{{ $homeSettings->hero_greeting }}</span>
                        <span class="inline-block mt-2 bg-[#2563EB] dark:bg-[#3B82F6] text-white px-3 py-1 border-[3px] border-black dark:border-[#CBD5E1] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)] rotate-[-1deg]">{{ $homeSettings->site_title }}</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-[15px] leading-7 text-[#2a2a2a] dark:text-[#d6d6d6] sm:text-[17px] font-medium border-l-[4px] border-black dark:border-[#CBD5E1] pl-4">
                        {!! $homeSettings->hero_description !!}
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="brutal-btn brutal-btn--primary text-sm px-6 py-3.5 shadow-[4px_4px_0_0_#111] dark:shadow-[4px_4px_0_0_rgba(203,213,225,0.9)]">
                            {{ $homeSettings->cta_primary_button_text }} →
                        </a>
                        <a href="{{ route('cv.preview', ['template' => $resumeTemplate]) }}" target="_blank" rel="noopener" class="brutal-btn brutal-btn--white text-sm px-6 py-3.5">
                            {{ $homeSettings->cta_secondary_button_text }}
                        </a>
                    </div>

                    <div class="mt-6 flex items-center gap-3 font-mono text-[11px] font-bold uppercase tracking-widest text-[#6b6b6b] dark:text-[#9a9a9a]">
                        <span class="w-8 h-[2.5px] bg-black dark:bg-[#FFFBF0]"></span>
                        DevOps • Cloud • Automation
                    </div>
                </div>

                <!-- Terminal — brutal window -->
                <div class="min-w-0 bg-white dark:bg-[#1E293B] border-[3px] border-black dark:border-[#CBD5E1] rounded-[16px] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)] p-3 sm:p-4">
                    <div class="bg-[#111] dark:bg-[#0F172A] border-[2.5px] border-black rounded-[12px] p-4 font-mono text-sm text-[#FFFBF0] overflow-hidden">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex gap-1.5"><span class="h-3 w-3 rounded-full bg-[#FF5F56] border border-black"></span><span class="h-3 w-3 rounded-full bg-[#FFBD2E] border border-black"></span><span class="h-3 w-3 rounded-full bg-[#27C93F] border border-black"></span></div>
                            <span class="text-[11px] font-black uppercase tracking-widest bg-[#2563EB] text-white px-2 py-0.5 rounded border border-black">terminal</span>
                        </div>
                        <div class="min-h-[11rem] space-y-2.5 sm:min-h-[12rem]">
                            <p class="text-[#B8FF66] font-bold">$ whoami</p>
                            <p class="font-bold">{{ $homeSettings->site_brand }}</p>
                            <p class="pt-1 text-[#00D1FF] font-bold">$ <span id="terminal-text" class="break-all text-white"></span><span class="terminal-cursor"></span></p>
                            <div class="mt-3 bg-white/10 border border-white/20 rounded-[8px] p-3 text-xs leading-6 text-[#d6d6d6] sm:text-sm font-medium">
                                <p><span class="text-[#2563EB]">role</span>: {{ $homeSettings->site_title }}</p>
                                <p><span class="text-[#00D1FF]">focus</span>: {{ $homeSettings->meta_description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2 justify-center">
                        <span class="w-3 h-3 rounded-full bg-[#2563EB] border-2 border-black"></span>
                        <span class="w-3 h-3 rounded-full bg-[#00D1FF] border-2 border-black"></span>
                        <span class="w-3 h-3 rounded-full bg-[#FF90E8] border-2 border-black"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Marquee — brutal flat bar -->
    @if($stacks->isNotEmpty())
        <section class="relative z-10 overflow-hidden py-0">
            <div class="bg-[#2563EB] dark:bg-[#1E40AF] border-y-[3px] border-black dark:border-[#CBD5E1] py-4 overflow-hidden">
                <div class="flex gap-6 animate-marquee whitespace-nowrap">
                    <div class="flex gap-6 shrink-0 items-center">
                        @foreach($stacks as $stack)
                            <div class="flex min-w-[7rem] flex-col items-center justify-center gap-2 bg-white dark:bg-[#1E293B] border-[2.5px] border-black rounded-[12px] px-5 py-3.5 shadow-[3px_3px_0_0_#111] sm:min-w-[8rem]">
                                @if(filter_var($stack->icon, FILTER_VALIDATE_URL))
                                    <img src="{{ $stack->icon }}" alt="{{ $stack->name }}" class="h-8 w-8 object-contain sm:h-9 sm:w-9">
                                @elseif(str_starts_with($stack->icon, 'devicon-'))
                                    <i class="{{ $stack->icon }} text-3xl sm:text-4xl"></i>
                                @else
                                    <span class="text-3xl sm:text-4xl">{{ $stack->icon }}</span>
                                @endif
                                <span class="font-black text-xs uppercase tracking-tight text-black dark:text-white text-center leading-none">{{ $stack->name }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex gap-6 shrink-0 items-center" aria-hidden="true">
                        @foreach($stacks as $stack)
                            <div class="flex min-w-[7rem] flex-col items-center justify-center gap-2 bg-white dark:bg-[#1E293B] border-[2.5px] border-black rounded-[12px] px-5 py-3.5 shadow-[3px_3px_0_0_#111] sm:min-w-[8rem]">
                                @if(filter_var($stack->icon, FILTER_VALIDATE_URL))
                                    <img src="{{ $stack->icon }}" alt="{{ $stack->name }}" class="h-8 w-8 object-contain sm:h-9 sm:w-9">
                                @elseif(str_starts_with($stack->icon, 'devicon-'))
                                    <i class="{{ $stack->icon }} text-3xl sm:text-4xl"></i>
                                @else
                                    <span class="text-3xl sm:text-4xl">{{ $stack->icon }}</span>
                                @endif
                                <span class="font-black text-xs uppercase tracking-tight text-black dark:text-white text-center leading-none">{{ $stack->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="bg-black dark:bg-white text-white dark:text-black py-2 text-center font-mono text-[11px] font-black uppercase tracking-[0.18em]">
                ● STACK ● TOOLS ● INFRA ● AUTOMATION ● STACK ● TOOLS ● INFRA ●
            </div>
        </section>
    @endif

    <!-- Featured Projects -->
    <section class="section-container py-12 sm:py-16">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-2.5 py-1 rounded-[6px] border-2 border-black">Featured Work</span>
                <h2 class="mt-3 text-3xl font-black tracking-[-0.05em] text-black dark:text-[#FFFBF0] sm:text-4xl leading-none">{{ $homeSettings->featured_projects_title }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-[#2a2a2a] dark:text-[#d6d6d6] font-medium">{{ $homeSettings->featured_projects_description }}</p>
            </div>
            <a href="{{ route('projects') }}" class="inline-flex items-center min-h-[44px] gap-1.5 brutal-btn brutal-btn--white text-xs px-5 py-2.5 self-start sm:self-auto">View all <span>→</span></a>
        </div>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group brutal-card overflow-hidden flex flex-col p-0 hover:bg-[#FFFBF0] dark:hover:bg-[#1E293B]">
                    @php($cardImage = $project->imageSrc('card'))
                    @if($cardImage)
                        <div class="aspect-video w-full bg-white dark:bg-[#1E293B] border-b-[3px] border-black dark:border-[#CBD5E1] overflow-hidden">
                            <img src="{{ $cardImage }}" alt="{{ $project->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                        </div>
                    @else
                        <div class="aspect-video w-full border-b-[3px] border-black dark:border-[#CBD5E1] bg-[#2563EB] flex items-center justify-center text-white">
                            <span class="font-black text-3xl tracking-tighter">{{ Str::substr($project->title, 0, 2) }}</span>
                        </div>
                    @endif
                    <div class="p-5 flex flex-col flex-grow">
                        <span class="inline-flex self-start font-mono text-[10px] font-black uppercase tracking-widest bg-[#2563EB] text-white border-2 border-black px-2 py-0.5 rounded-full">Project — 0{{ $loop->iteration }}</span>
                        <h3 class="mt-3 text-xl font-black tracking-[-0.03em] text-black dark:text-white group-hover:underline decoration-[3px] underline-offset-4 leading-tight">{{ $project->title }}</h3>
                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-[#2a2a2a] dark:text-[#d6d6d6] font-medium">{{ Str::limit(strip_tags($project->description), 150) }}</p>
                        <span class="mt-4 pt-2 inline-flex items-center gap-1 text-sm font-black uppercase tracking-tight text-black dark:text-white">{{ __('messages.ux_home.details') }} <span class="group-hover:translate-x-1 transition-transform">→</span></span>
                    </div>
                </a>
            @empty
                <div class="brutal-card p-8 text-center font-bold text-[#6b6b6b] md:col-span-3">No featured projects yet.</div>
            @endforelse
        </div>

    <!-- Contact CTA Block — brutal punchy call-to-action -->
    <section class="section-container pb-12 sm:pb-16">
        <div class="bg-[#111] dark:bg-[#1E293B] border-[3px] border-black dark:border-[#CBD5E1] rounded-[16px] shadow-[8px_8px_0_0_#111] dark:shadow-[6px_6px_0_0_rgba(203,213,225,0.9)] p-6 sm:p-8 lg:p-10 text-[#FFFBF0] overflow-hidden relative">
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-[#2563EB] border-[3px] border-black rounded-full hidden sm:block"></div>
            <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-[#00D1FF] border-[3px] border-black rotate-12 hidden sm:block"></div>
            <div class="relative max-w-3xl">
                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-widest bg-[#2563EB] text-white px-2.5 py-1 rounded-[6px] border-2 border-black">{{ $homeSettings->infrastructure_title ?: __('messages.ux_home.contact_cta_badge') }}</span>
                <h2 class="mt-4 text-3xl font-black tracking-[-0.04em] leading-tight sm:text-4xl lg:text-5xl">{{ $homeSettings->cta_title }}</h2>
                <p class="mt-4 max-w-2xl text-base leading-7 text-[#d6d6d6] font-medium">{{ $homeSettings->cta_description }}</p>
                <div class="mt-7 flex flex-wrap items-center gap-3">
                    <a href="{{ route('contact') }}" class="brutal-btn min-h-[44px] bg-[#2563EB] text-white border-2 border-black shadow-[4px_4px_0_0_#fff] hover:bg-white hover:text-black text-sm px-6 py-3">
                        {{ $homeSettings->cta_primary_button_text ?: __('messages.ux_home.contact_cta_button') }} →
                    </a>
                    <a href="{{ route('projects') }}" class="brutal-btn min-h-[44px] bg-white text-black border-2 border-black shadow-[4px_4px_0_0_#fff] hover:bg-[#2563EB] hover:text-white text-sm px-6 py-3">
                        {{ __('messages.ux_home.explore_projects') }} →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest from the blog — standalone compact cards (only shown when published posts exist) -->
    @if($posts->isNotEmpty())
        <section class="section-container pb-12 sm:pb-16">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#00D1FF] text-black px-2.5 py-1 rounded-[6px] border-2 border-black">{{ __('messages.ux_home.blog_section_badge') }}</span>
                    <h2 class="mt-3 text-3xl font-black tracking-[-0.05em] text-black dark:text-[#FFFBF0] sm:text-4xl leading-none">{{ __('messages.ux_home.blog_section_title') }}</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-[#2a2a2a] dark:text-[#d6d6d6] font-medium">{{ __('messages.ux_home.blog_section_desc') }}</p>
                </div>
                <a href="{{ route('blog') }}" class="inline-flex items-center min-h-[44px] gap-1.5 brutal-btn brutal-btn--white text-xs px-5 py-2.5 self-start sm:self-auto">{{ __('messages.ux_home.view_all_posts') }} <span>→</span></a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    @php($displayDate = $post->published_at ?? $post->updated_at ?? $post->created_at)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group brutal-card p-5 flex flex-col justify-between hover:bg-[#FFFBF0] dark:hover:bg-[#1E293B]">
                        <div>
                            @if($displayDate)
                                <p class="font-mono text-[10px] font-black uppercase tracking-widest bg-[#2563EB] text-white px-2 py-0.5 rounded border border-black inline-block">{{ $displayDate->format('M j, Y') }}</p>
                            @endif
                            <h3 class="mt-3 text-lg font-black leading-snug text-black dark:text-white group-hover:underline decoration-[3px] underline-offset-4 line-clamp-2">{{ $post->title }}</h3>
                            <p class="mt-2 line-clamp-3 text-xs leading-5 text-[#2a2a2a] dark:text-[#d6d6d6] font-medium">{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 120) }}</p>
                        </div>
                        <span class="mt-4 pt-2 inline-flex items-center gap-1 text-xs font-black uppercase tracking-tight text-black dark:text-white">{{ __('messages.ux_home.read_more') }} <span class="group-hover:translate-x-1 transition-transform">→</span></span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const terminalText = document.getElementById('terminal-text');
                const commands = @json($homeSettings->terminal_commands ?? []);
                if (! terminalText || commands.length === 0) return;
                let commandIndex = 0, charIndex = 0, isDeleting = false;
                function typeCommand() {
                    const currentCommand = commands[commandIndex] ?? '';
                    terminalText.textContent = currentCommand.substring(0, charIndex + (isDeleting ? -1 : 1));
                    charIndex += isDeleting ? -1 : 1;
                    let delay = isDeleting ? 35 : 80;
                    if (! isDeleting && charIndex === currentCommand.length) { delay = 1800; isDeleting = true; }
                    if (isDeleting && charIndex === 0) { isDeleting = false; commandIndex = (commandIndex + 1) % commands.length; delay = 450; }
                    window.setTimeout(typeCommand, delay);
                }
                typeCommand();
            });
        </script>
    @endpush
</x-layouts.app>