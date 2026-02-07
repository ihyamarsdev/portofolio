<x-layouts.app title="Home">
    @php
        $homeSettings = \App\Models\HomeSettings::getSettings();
    @endphp

    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center overflow-hidden bg-bg-primary">
        <!-- Background Grid Pattern -->
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,var(--color-border)_1px,transparent_1px),linear-gradient(to_bottom,var(--color-border)_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-20">
        </div>

        <!-- Gradient Orbs -->
        <div
            class="absolute top-1/4 -left-32 w-96 h-96 bg-[--color-accent-emerald] rounded-full blur-[128px] opacity-20">
        </div>
        <div
            class="absolute bottom-1/4 -right-32 w-96 h-96 bg-[--color-accent-cyan] rounded-full blur-[128px] opacity-20">
        </div>

        <div class="section-container relative z-10">
            <div class="max-w-4xl">
                @if($homeSettings->availability_active)
                    <!-- Terminal Prompt -->
                    <div
                        class="mb-6 inline-flex items-center gap-2 px-4 py-2 bg-[--color-bg-secondary] rounded-full border border-[--color-border]">
                        <span class="status-dot operational"></span>
                        <span
                            class="text-sm font-mono text-[--color-text-secondary]">{{ $homeSettings->availability_status }}</span>
                    </div>
                @endif

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold leading-tight mb-6">
                    <span class="text-[--color-text-primary]">{{ $homeSettings->hero_greeting }}</span><br>
                    <span class="gradient-text">{{ $profile->title ?? 'DevOps Engineer' }}</span>
                </h1>

                <!-- Terminal Command -->
                @if($homeSettings->terminal_commands && count($homeSettings->terminal_commands) > 0)
                    <div
                        class="inline-flex items-center gap-2 px-4 py-3 bg-[--color-bg-card] rounded-lg border border-[--color-border] mb-8">
                        <span class="text-[--color-accent-emerald] font-mono">$</span>
                        <span class="font-mono text-[--color-text-primary] terminal-text" id="terminal-text"></span>
                        <span class="terminal-cursor"></span>
                    </div>

                    <script>
                        const commands = {!! Illuminate\Support\Js::from($homeSettings->terminal_commands) !!};

                        let commandIndex = 0;
                        let charIndex = 0;
                        let isDeleting = false;
                        const terminalText = document.getElementById('terminal-text');

                        function typeCommand() {
                            const currentCommand = commands[commandIndex];

                            if (isDeleting) {
                                terminalText.textContent = currentCommand.substring(0, charIndex - 1);
                                charIndex--;
                            } else {
                                terminalText.textContent = currentCommand.substring(0, charIndex + 1);
                                charIndex++;
                            }

                            let typeSpeed = isDeleting ? 50 : 100;

                            if (!isDeleting && charIndex === currentCommand.length) {
                                typeSpeed = 2000;
                                isDeleting = true;
                            } else if (isDeleting && charIndex === 0) {
                                isDeleting = false;
                                commandIndex = (commandIndex + 1) % commands.length;
                                typeSpeed = 500;
                            }

                            setTimeout(typeCommand, typeSpeed);
                        }

                        typeCommand();
                    </script>
                @endif

                <!-- Description -->
                <p class="text-lg sm:text-xl text-[--color-text-secondary] max-w-2xl mb-10">
                    {!! $homeSettings->hero_description !!}
                </p>

                <!-- CTAs -->
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('projects') }}" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                        View Projects
                    </a>
                    <a href="{{ route('cv.download', ['template' => 'modern']) }}" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download CV
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="py-20 bg-bg-primary border-t border-[--color-border]">
        <div class="section-container">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-[--color-text-primary] mb-4">{{ $homeSettings->tech_stack_title }}
                </h2>
                <p class="text-[--color-text-secondary]">{{ $homeSettings->tech_stack_description }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $stacks = \App\Models\Stack::take(12)->get();
                @endphp

                @forelse($stacks as $stack)
                    <div
                        class="glass-card p-4 flex flex-col items-center gap-2 hover:border-[--color-accent-emerald]/50 transition-colors">
                        @if($stack->icon)
                            <span class="text-3xl">{{ $stack->icon }}</span>
                        @else
                            <span class="text-3xl">🔧</span>
                        @endif
                        <span class="font-mono text-sm text-[--color-text-primary]">{{ $stack->name }}</span>
                        <span class="text-xs text-[--color-text-muted]">{{ ucfirst($stack->category) }}</span>
                    </div>
                @empty
                    @foreach(['Docker', 'Kubernetes', 'Terraform', 'Ansible', 'AWS', 'GitHub Actions'] as $tech)
                        <div
                            class="glass-card p-4 flex flex-col items-center gap-2 hover:border-[--color-accent-emerald]/50 transition-colors">
                            <span class="text-3xl">🔧</span>
                            <span class="font-mono text-sm text-[--color-text-primary]">{{ $tech }}</span>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Projects Section -->
    <section id="projects" class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-[--color-text-primary] mb-4">
                        {{ $homeSettings->featured_projects_title }}
                    </h2>
                    <p class="text-[--color-text-secondary]">{{ $homeSettings->featured_projects_description }}</p>
                </div>
                <a href="{{ route('projects') }}"
                    class="hidden sm:inline-flex items-center gap-2 text-[--color-accent-emerald] hover:underline">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $projects = \App\Models\Project::where('is_featured', true)->take(3)->get();
                @endphp

                @forelse($projects as $project)
                    <article class="project-card group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-[--color-bg-tertiary] rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex gap-2">
                                @if($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank"
                                        class="p-2 text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                        </svg>
                                    </a>
                                @endif
                                @if($project->live_url)
                                    <a href="{{ $project->live_url }}" target="_blank"
                                        class="p-2 text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <h3
                            class="text-xl font-semibold text-[--color-text-primary] mb-2 group-hover:text-[--color-accent-emerald] transition-colors">
                            {{ $project->title }}
                        </h3>

                        <p class="text-[--color-text-secondary] text-sm mb-4 line-clamp-2">
                            {{ Str::limit(strip_tags($project->description), 120) }}
                        </p>

                        @if($project->tech_stack)
                            <div class="flex flex-wrap gap-2">
                                @foreach(array_slice($project->tech_stack, 0, 4) as $tech)
                                    <span class="tech-badge">{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-[--color-bg-tertiary] rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-[--color-text-muted]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-[--color-text-primary] mb-2">No Project Yet, Stay Tune</h3>
                        <p class="text-[--color-text-secondary]">We are working on something amazing.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-b from-[--color-bg-primary] to-[--color-bg-secondary]">
        <div class="section-container text-center">
            <h2 class="text-3xl font-bold text-[--color-text-primary] mb-4">{{ $homeSettings->cta_title }}</h2>
            <p class="text-[--color-text-secondary] max-w-2xl mx-auto mb-8">
                {!! $homeSettings->cta_description !!}
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact') }}" class="btn-primary">
                    {{ $homeSettings->cta_primary_button_text }}
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>