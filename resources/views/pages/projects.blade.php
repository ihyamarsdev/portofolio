<x-layouts.app title="{{ __('messages.projects.title') }}">
    @php($projects = \App\Models\Project::with('skills')->orderBy('order')->latest()->get())
    <section class="bg-bg-primary">
        <div class="section-container py-16 sm:py-24">
            <div class="mb-10 max-w-3xl">
                <p class="font-mono text-xs uppercase tracking-widest text-accent-sky">{{ __('messages.nav.projects') }}</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-[-.05em] text-text-primary sm:text-6xl">{{ __('messages.projects.title') }}</h1>
                <p class="mt-5 text-base leading-8 text-text-secondary sm:text-lg">{{ __('messages.projects.subtitle') }}</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($projects as $project)
                    <article class="group relative overflow-hidden rounded-2xl bg-bg-card shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition hover:-translate-y-1 hover:shadow-[0_0_0_1px_rgba(0,0,0,.08),0_18px_45px_rgba(0,0,0,.1)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                        <a href="{{ route('projects.show', $project->slug) }}" class="absolute inset-0 z-10" aria-label="Open {{ $project->title }}"></a>
                        @if($project->image_url || $project->getFirstMediaUrl('images'))
                            <img src="{{ $project->image_url ?: $project->getFirstMediaUrl('images') }}" alt="{{ $project->title }}" class="aspect-video w-full bg-sky-50 object-contain p-3 dark:bg-slate-950/40">
                        @else
                            <div class="aspect-video bg-gradient-to-br from-sky-500/20 via-violet-500/20 to-emerald-500/20"></div>
                        @endif
                        <div class="p-5">
                            <div class="mb-3 flex flex-wrap gap-2">
                                @if($project->is_featured)<span class="rounded-full bg-sky-500/10 px-3 py-1 text-xs font-medium text-accent-sky">{{ __('messages.projects.featured') }}</span>@endif
                                @if($project->architecture_diagram)<span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-500">{{ __('messages.projects.architecture_diagram') }}</span>@endif
                            </div>
                            <h2 class="text-xl font-semibold tracking-[-.03em] text-text-primary group-hover:text-accent-sky">{{ $project->title }}</h2>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-text-secondary">{{ Str::limit(strip_tags($project->description), 160) }}</p>
                            <div class="relative z-20 mt-5 flex flex-wrap items-center gap-3 text-sm">
                                <span class="font-semibold text-accent-sky">Details →</span>
                                @if($project->repository_url)<a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-text-muted hover:text-text-primary">{{ __('messages.projects.view_repository') }}</a>@endif
                                @if($project->live_url)<a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="text-text-muted hover:text-text-primary">{{ __('messages.projects.live_demo') }}</a>@endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl bg-bg-card p-10 text-center text-text-secondary shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)] sm:col-span-2 xl:col-span-3">{{ __('messages.projects.no_projects_found') }}</div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
