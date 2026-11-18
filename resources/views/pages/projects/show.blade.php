<x-layouts.app :title="$project->title">
    @php
        $imageSrc = $project->imageSrc();
        $allProjects = \App\Models\Project::orderBy('order')->get();
        $currentIndex = $allProjects->search(fn ($item) => $item->id === $project->id);
        $previous = $currentIndex !== false ? $allProjects->get($currentIndex - 1) : null;
        $next = $currentIndex !== false ? $allProjects->get($currentIndex + 1) : null;
    @endphp

    <article class="section-container py-12 sm:py-16">
        <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2 transition-colors duration-150 hover:text-accent">
            <span class="transition-transform duration-150 group-hover:-translate-x-1">←</span> {{ __('messages.nav.projects') }}
        </a>

        <header class="mt-8 border-b border-line pb-8">
            <p class="font-mono text-[11px] uppercase tracking-[0.16em] text-accent">{{ __('messages.ux_home.details') }} / {{ str_pad(($currentIndex !== false ? $currentIndex : 0) + 1, 2, '0', STR_PAD_LEFT) }}</p>
            <h1 class="mt-4 text-3xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink sm:text-5xl">{{ $project->title }}</h1>

            <dl class="mt-8 grid gap-x-6 gap-y-4 border-t border-line pt-6 font-mono text-[11px] sm:grid-cols-3">
                @if($project->skills->isNotEmpty())
                    <div>
                        <dt class="text-ink-3">{{ __('messages.home.tech_stack') }}</dt>
                        <dd class="mt-1.5 leading-relaxed text-ink">{{ $project->skills->pluck('name')->join(', ') }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-ink-3">{{ __('messages.nav.projects') }}</dt>
                    <dd class="mt-1.5 text-ink">{{ $project->is_featured ? __('messages.projects.featured') : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-ink-3">Links</dt>
                    <dd class="mt-1.5 flex flex-wrap gap-3">
                        @if($project->repository_url)
                            <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-ink transition-colors duration-150 hover:text-accent">{{ __('messages.projects.view_repository') }} ↗</a>
                        @endif
                        @if($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="text-ink transition-colors duration-150 hover:text-accent">{{ __('messages.projects.live_demo') }} ↗</a>
                        @endif
                    </dd>
                </div>
            </dl>
        </header>

        @if($imageSrc)
            <div class="mt-8 overflow-hidden border border-line bg-subtle">
                <img src="{{ $imageSrc }}" alt="{{ $project->title }}" fetchpriority="high" decoding="async" class="aspect-video w-full object-cover">
            </div>
        @endif

        @if($project->description)
            <div class="prose measure mt-10">
                {!! Str::markdown($project->description) !!}
            </div>
        @endif

        @if($project->architecture_diagram)
            <section class="mt-12 border-t border-line pt-8">
                <h2 class="font-mono text-[11px] uppercase tracking-[0.16em] text-ink-3">{{ __('messages.projects.architecture_diagram') }}</h2>
                <div class="mt-4 overflow-x-auto border border-line bg-surface p-4">
                    <div class="mermaid">{{ $project->architecture_diagram }}</div>
                </div>
            </section>
        @endif

        @if($previous || $next)
            <nav class="mt-14 grid gap-4 border-t border-line pt-6 sm:grid-cols-2">
                @if($previous)
                    <a href="{{ route('projects.show', $previous->slug) }}" class="group">
                        <span class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-3">← Previous</span>
                        <p class="mt-1 font-medium text-ink transition-colors duration-150 group-hover:text-accent">{{ $previous->title }}</p>
                    </a>
                @endif
                @if($next)
                    <a href="{{ route('projects.show', $next->slug) }}" class="group sm:text-right">
                        <span class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-3">Next →</span>
                        <p class="mt-1 font-medium text-ink transition-colors duration-150 group-hover:text-accent">{{ $next->title }}</p>
                    </a>
                @endif
            </nav>
        @endif
    </article>

    @if($project->architecture_diagram)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mermaid.initialize({ startOnLoad: true, theme: 'default', securityLevel: 'loose', flowchart: { useMaxWidth: true, htmlLabels: true, curve: 'basis' } });
                });
            </script>
        @endpush
    @endif
</x-layouts.app>
