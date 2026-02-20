<x-layouts.app title="Projects">
    <section class="py-20 bg-bg-primary ">
        <div class="section-container">
            <div class="max-w-3xl mb-12">
                <h1 class="text-4xl font-bold text-[--color-text-primary] mb-4">Projects</h1>
                <p class="text-lg text-[--color-text-secondary]">
                    A collection of infrastructure, automation, and DevOps projects I've worked on.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $projects = \App\Models\Project::orderBy('order')->orderBy('created_at', 'desc')->get();
                @endphp

                @forelse($projects as $project)
                    <article class="project-card group relative">
                        {{-- Link to detail page - covers entire card --}}
                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="absolute inset-0 z-0 after:content-[''] after:absolute after:inset-0">
                        </a>

                        <div class="relative z-10 pointer-events-none">
                            @if($project->getFirstMediaUrl('images'))
                                <div class="aspect-video rounded-lg overflow-hidden mb-4 bg-[--color-bg-tertiary]">
                                    <img src="{{ $project->getFirstMediaUrl('images') }}" alt="{{ $project->title }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="flex items-start justify-between mb-4">
                                <div class="w-10 h-10 bg-[--color-bg-tertiary] rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <h2
                                class="text-xl font-semibold text-[--color-text-primary] mb-2 group-hover:text-[--color-accent-emerald] transition-colors">
                                {{ $project->title }}
                            </h2>

                            <p class="text-[--color-text-secondary] text-sm mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($project->description), 150) }}
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex gap-2">
                                    @if($project->repository_url)
                                        <a href="{{ $project->repository_url }}" target="_blank"
                                           class="pointer-events-auto relative z-20 p-2 text-[--color-text-muted] hover:text-[--color-text-primary] transition-colors hover:scale-110 transform">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if($project->live_url)
                                        <a href="{{ $project->live_url }}" target="_blank"
                                           class="pointer-events-auto relative z-20 p-2 text-[--color-text-muted] hover:text-[--color-accent-emerald] transition-colors hover:scale-110 transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                                @if($project->architecture_diagram)
                                    <span class="text-xs text-[--color-accent-emerald] flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                        Has Diagram
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-[--color-bg-secondary] rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-[--color-text-muted]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-[--color-text-primary] mb-2">No projects yet</h3>
                        <p class="text-[--color-text-secondary]">Projects will appear here once added through the admin
                            panel.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
