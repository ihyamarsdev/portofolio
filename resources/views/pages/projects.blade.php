<x-layouts.app title="{{ __('messages.projects.title') }}">
    @php($projects = \App\Models\Project::with(['skills', 'media'])->orderBy('order')->latest()->get())
    <section class="bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container py-12 sm:py-16">
            <div class="mb-8 max-w-3xl">
                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-2.5 py-1 rounded-[6px] border-2 border-black">Index — {{ str_pad($projects->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <h1 class="mt-3 text-4xl font-black tracking-[-0.06em] leading-none text-black dark:text-[#FFFBF0] sm:text-6xl">
                    {{ __('messages.projects.title') }}
                    <span class="inline-block bg-[#2563EB] border-[3px] border-black px-2 py-1 shadow-[5px_5px_0_0_#111] rotate-[-0.5deg] ml-1 text-3xl sm:text-5xl">.</span>
                </h1>
                <p class="mt-4 text-base leading-7 text-[#2a2a2a] dark:text-[#d6d6d6] font-medium border-l-[4px] border-black dark:border-[#CBD5E1] pl-4 sm:text-lg">{{ __('messages.projects.subtitle') }}</p>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($projects as $project)
                    <article class="group relative brutal-card overflow-hidden flex flex-col p-0 h-full">
                        <a href="{{ route('projects.show', $project->slug) }}" class="absolute inset-0 z-10" aria-label="Open {{ $project->title }}"></a>
                        @php($imageSrc = $project->imageSrc('card'))
                        @if($imageSrc)
                            <div class="aspect-video w-full bg-white dark:bg-[#1E293B] border-b-[3px] border-black dark:border-[#CBD5E1] overflow-hidden">
                                <img src="{{ $imageSrc }}" alt="{{ $project->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                            </div>
                        @else
                            <div class="aspect-video w-full border-b-[3px] border-black dark:border-[#CBD5E1] bg-[#2563EB] flex items-center justify-center">
                                <span class="font-black text-3xl tracking-tighter text-white">{{ Str::substr($project->title,0,2) }}</span>
                            </div>
                        @endif
                        <div class="p-5 flex flex-col flex-1">
                            <div class="mb-3 flex flex-wrap gap-2">
                                @if($project->is_featured)<span class="brutal-badge">★ {{ __('messages.projects.featured') }}</span>@endif
                                @if($project->architecture_diagram)<span class="brutal-badge brutal-badge--cyan">{{ __('messages.projects.architecture_diagram') }}</span>@endif
                                <span class="brutal-badge brutal-badge--white font-mono">#{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>
                            </div>
                            <h2 class="text-xl font-black tracking-[-0.03em] leading-tight text-black dark:text-white group-hover:underline decoration-[3px] underline-offset-4">{{ $project->title }}</h2>
                            <p class="mt-2 line-clamp-3 text-sm leading-6 text-[#2a2a2a] dark:text-[#d6d6d6] font-medium">{{ Str::limit(strip_tags($project->description), 160) }}</p>
                            <div class="relative z-20 mt-auto pt-4 flex flex-wrap items-center gap-2 text-sm font-black uppercase tracking-tight">
                                <span class="inline-flex items-center gap-1 bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-3.5 py-2 rounded-[8px] border-2 border-black min-h-[44px]">Details <span class="group-hover:translate-x-1 transition-transform">→</span></span>
                                @if($project->repository_url)<a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="underline decoration-2 underline-offset-4 hover:bg-[#2563EB] hover:text-white px-3 py-2 min-h-[44px] min-w-[44px] inline-flex items-center justify-center">Repo</a>@endif
                                @if($project->live_url)<a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="underline decoration-2 underline-offset-4 hover:bg-[#00D1FF] hover:text-black px-3 py-2 min-h-[44px] min-w-[44px] inline-flex items-center justify-center">Live</a>@endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="brutal-card p-10 text-center font-black uppercase tracking-tight sm:col-span-2 xl:col-span-3">{{ __('messages.projects.no_projects_found') }}</div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>