<x-layouts.app title="Home">
    @php
        $homeSettings = $homeSettings ?? \App\Models\HomeSettings::getSettings();
        $projects = \App\Models\Project::query()->where('is_featured', true)->orderBy('order')->latest()->take(3)->get();
        $posts = \App\Models\Post::published()->latest('published_at')->take(3)->get();
    @endphp

    <section class="relative overflow-hidden bg-bg-primary">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,.18),transparent_32rem),radial-gradient(circle_at_top_right,rgba(167,139,250,.16),transparent_28rem)]"></div>
        <div class="section-container relative py-16 sm:py-24 lg:py-32">
            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_.9fr]">
                <div>
                    @if($homeSettings->availability_active)
                        <div class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full bg-bg-card px-4 py-2 text-xs font-medium text-text-secondary shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            <span class="truncate">{{ $homeSettings->availability_status }}</span>
                        </div>
                    @endif

                    <h1 class="max-w-4xl text-4xl font-semibold leading-[1.05] tracking-[-.06em] text-text-primary sm:text-6xl lg:text-7xl">
                        {{ $homeSettings->hero_greeting }}
                        <span class="block bg-gradient-to-r from-sky-500 via-violet-500 to-emerald-500 bg-clip-text text-transparent">{{ $homeSettings->site_title }}</span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-8 text-text-secondary sm:text-xl">
                        {!! $homeSettings->hero_description !!}
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-lg bg-text-primary px-5 py-3 text-sm font-semibold text-bg-primary transition hover:opacity-90">{{ $homeSettings->cta_primary_button_text }}</a>
                        <a href="{{ route('projects') }}" class="inline-flex items-center justify-center rounded-lg bg-bg-card px-5 py-3 text-sm font-semibold text-text-primary shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition hover:bg-bg-secondary dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">{{ $homeSettings->cta_secondary_button_text }}</a>
                    </div>
                </div>

                <div class="rounded-2xl bg-bg-card p-4 shadow-[0_0_0_1px_rgba(0,0,0,.08),0_12px_40px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08),0_12px_40px_rgba(0,0,0,.35)]">
                    <div class="rounded-xl bg-slate-950 p-4 font-mono text-sm text-slate-100">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div class="flex gap-2"><span class="h-3 w-3 rounded-full bg-rose-400"></span><span class="h-3 w-3 rounded-full bg-amber-400"></span><span class="h-3 w-3 rounded-full bg-emerald-400"></span></div>
                            <span class="text-xs text-slate-500">terminal</span>
                        </div>
                        <div class="min-h-44 space-y-3 sm:min-h-52">
                            <p class="text-emerald-300">$ whoami</p>
                            <p>{{ $homeSettings->site_brand }}</p>
                            <p class="pt-2 text-sky-300">$ <span id="terminal-text" class="break-all"></span><span class="terminal-cursor ml-1 inline-block h-4 w-2 translate-y-0.5 bg-emerald-300"></span></p>
                            <div class="rounded-lg bg-white/5 p-3 text-xs leading-6 text-slate-300 sm:text-sm">
                                <p>role: {{ $homeSettings->site_title }}</p>
                                <p>focus: {{ $homeSettings->meta_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-container py-12 sm:py-20">
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="font-mono text-xs uppercase tracking-widest text-accent-sky">{{ $homeSettings->tech_stack_title }}</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-[-.04em] text-text-primary sm:text-4xl">{{ $homeSettings->featured_projects_title }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-text-secondary">{{ $homeSettings->featured_projects_description }}</p>
            </div>
            <a href="{{ route('projects') }}" class="text-sm font-semibold text-accent-sky">View all →</a>
        </div>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group rounded-2xl bg-bg-card p-5 shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition hover:-translate-y-1 hover:shadow-[0_0_0_1px_rgba(0,0,0,.08),0_16px_40px_rgba(0,0,0,.1)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                    <h3 class="text-xl font-semibold tracking-[-.03em] text-text-primary group-hover:text-accent-sky">{{ $project->title }}</h3>
                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-text-secondary">{{ Str::limit(strip_tags($project->description), 150) }}</p>
                </a>
            @empty
                <div class="rounded-2xl bg-bg-card p-8 text-text-secondary shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">No featured projects yet.</div>
            @endforelse
        </div>
    </section>

    <section class="section-container pb-16 sm:pb-24">
        <div class="rounded-3xl bg-text-primary p-6 text-bg-primary sm:p-10 lg:p-12">
            <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
                <div>
                    <p class="font-mono text-xs uppercase tracking-widest opacity-70">{{ $homeSettings->infrastructure_title }}</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-[-.04em] sm:text-4xl">{{ $homeSettings->cta_title }}</h2>
                    <p class="mt-4 text-sm leading-6 opacity-70">{{ $homeSettings->cta_description }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    @forelse($posts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="rounded-2xl bg-white/10 p-4 transition hover:bg-white/15">
                            <p class="text-xs opacity-70">{{ $post->published_at->format('M j, Y') }}</p>
                            <h3 class="mt-2 text-sm font-semibold leading-6">{{ $post->title }}</h3>
                        </a>
                    @empty
                        <p class="text-sm opacity-70">No posts yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const terminalText = document.getElementById('terminal-text');
                const commands = @json($homeSettings->terminal_commands ?? []);

                if (! terminalText || commands.length === 0) {
                    return;
                }

                let commandIndex = 0;
                let charIndex = 0;
                let isDeleting = false;

                function typeCommand() {
                    const currentCommand = commands[commandIndex] ?? '';
                    terminalText.textContent = currentCommand.substring(0, charIndex + (isDeleting ? -1 : 1));
                    charIndex += isDeleting ? -1 : 1;

                    let delay = isDeleting ? 35 : 80;

                    if (! isDeleting && charIndex === currentCommand.length) {
                        delay = 1800;
                        isDeleting = true;
                    }

                    if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        commandIndex = (commandIndex + 1) % commands.length;
                        delay = 450;
                    }

                    window.setTimeout(typeCommand, delay);
                }

                typeCommand();
            });
        </script>
    @endpush
</x-layouts.app>
