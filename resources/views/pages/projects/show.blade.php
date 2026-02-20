<x-layouts.app :title="$project->title">
    <section class="py-20 bg-bg-primary">
        <div class="section-container">
            <!-- Back Button -->
            <a href="{{ route('projects') }}"
                class="inline-flex items-center gap-2 text-[--color-text-secondary] hover:text-[--color-accent-emerald] transition-colors mb-8">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Projects
            </a>

            <div class="max-w-4xl mx-auto">
                <!-- Project Header -->
                <div class="mb-8">
                    @if($project->getFirstMediaUrl('images'))
                        <div class="aspect-video rounded-lg overflow-hidden mb-6 bg-[--color-bg-tertiary]">
                            <img src="{{ $project->getFirstMediaUrl('images') }}" alt="{{ $project->title }}"
                                class="w-full h-full object-cover">
                        </div>
                    @endif

                    <h1 class="text-4xl font-bold text-[--color-text-primary] mb-4">{{ $project->title }}</h1>

                    <div class="flex flex-wrap gap-4 mb-6">
                        @if($project->repository_url)
                            <a href="{{ $project->repository_url }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[--color-bg-tertiary] rounded-lg text-[--color-text-primary] hover:opacity-90 transition-opacity">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                </svg>
                                Repository
                            </a>
                        @endif
                        @if($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[--color-accent-emerald] rounded-lg text-white hover:opacity-90 transition-opacity">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                Live Demo
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($project->description)
                    <div class="glass-card p-6 mb-8">
                        <h2 class="text-xl font-semibold text-[--color-text-primary] mb-4">Description</h2>
                        <div class="prose prose-invert max-w-none text-[--color-text-secondary]">
                            {!! Str::markdown($project->description) !!}
                        </div>
                    </div>
                @endif

                <!-- Skills -->
                @if($project->skills && $project->skills->count() > 0)
                    <div class="glass-card p-6 mb-8">
                        <h2 class="text-xl font-semibold text-[--color-text-primary] mb-4">Tech Stack</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->skills as $skill)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md border border-[--color-accent-emerald]/20 bg-gradient-to-br from-[--color-accent-emerald]/10 to-[--color-accent-cyan]/10 text-xs text-[--color-text-primary] hover:border-[--color-accent-emerald]/40 hover:scale-105 transition-all duration-200">
                                    <span class="w-1 h-1 rounded-full bg-[--color-accent-emerald] mr-1.5"></span>
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Architecture Diagram -->
                @if($project->getFirstMediaUrl('diagrams') || $project->architecture_diagram)
                    <div class="glass-card p-6">
                        <h2 class="text-xl font-semibold text-[--color-text-primary] mb-4">Architecture Diagram</h2>
                        <div class="bg-white rounded-lg p-4 overflow-x-auto">
                            @if($project->getFirstMediaUrl('diagrams'))
                                {{-- Display pre-rendered SVG --}}
                                <img src="{{ $project->getFirstMediaUrl('diagrams') }}"
                                    alt="Architecture Diagram for {{ $project->title }}" class="max-w-full h-auto mx-auto">
                            @elseif($project->architecture_diagram)
                                {{-- Fallback to Mermaid.js rendering --}}
                                <div class="mermaid">
                                    {{ $project->architecture_diagram }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if($project->architecture_diagram && !$project->getFirstMediaUrl('diagrams'))
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mermaid.initialize({
                        startOnLoad: true,
                        theme: 'default',
                        securityLevel: 'loose',
                        flowchart: {
                            useMaxWidth: true,
                            htmlLabels: true,
                            curve: 'basis'
                        }
                    });
                });
            </script>
        @endpush
    @endif
</x-layouts.app>