@props([
    'project',
    'index' => null,
    'layout' => 'row',
])

@php
    $image = $project->imageSrc('card');
    $hasSkills = $project->relationLoaded('skills') && $project->skills->isNotEmpty();
    $excerpt = \Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 150);
@endphp

@if($layout === 'card')
    <a href="{{ route('projects.show', $project->slug) }}" class="group flex h-full flex-col border border-line bg-surface transition-colors duration-150 hover:border-ink">
        @if($image)
            <div class="aspect-[16/9] overflow-hidden border-b border-line bg-subtle">
                <img src="{{ $image }}" alt="{{ $project->title }}" width="800" height="450" loading="lazy" decoding="async" class="h-full w-full object-cover">
            </div>
        @endif
        <div class="flex flex-1 flex-col p-5">
            <div class="flex items-start justify-between gap-3">
                @if($index)
                    <span class="font-mono text-[11px] tracking-[0.12em] text-ink-3">{{ $index }}</span>
                @endif
                @if($project->is_featured)
                    <span class="font-mono text-[10px] uppercase tracking-[0.12em] text-accent">{{ __('messages.projects.featured') }}</span>
                @endif
            </div>
            <h3 class="mt-3 text-lg font-semibold leading-snug tracking-[-0.02em] text-ink transition-colors duration-150 group-hover:text-accent">{{ $project->title }}</h3>
            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-ink-2">{{ $excerpt }}</p>
            @if($hasSkills)
                <div class="mt-4 flex flex-wrap gap-x-3 gap-y-1">
                    @foreach($project->skills as $skill)
                        <span class="font-mono text-[10px] uppercase tracking-[0.1em] text-ink-3">{{ $skill->name }}</span>
                    @endforeach
                </div>
            @endif
            <span class="mt-auto pt-5 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2 transition-colors duration-150 group-hover:text-accent">
                {{ __('messages.ux_home.details') }} <span class="inline-block transition-transform duration-150 group-hover:translate-x-1">→</span>
            </span>
        </div>
    </a>
@else
    <a href="{{ route('projects.show', $project->slug) }}" class="group grid grid-cols-12 gap-x-4 gap-y-2 border-b border-line py-6 transition-colors duration-150 hover:bg-subtle">
        <div class="col-span-2 pt-1 font-mono text-[11px] tracking-[0.12em] text-ink-3 sm:col-span-1">{{ $index }}</div>
        <div class="col-span-10 sm:col-span-7">
            <h3 class="text-lg font-semibold leading-snug tracking-[-0.02em] text-ink transition-colors duration-150 group-hover:text-accent">{{ $project->title }}</h3>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-ink-2">{{ $excerpt }}</p>
            @if($hasSkills)
                <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1">
                    @foreach($project->skills as $skill)
                        <span class="font-mono text-[10px] uppercase tracking-[0.1em] text-ink-3">{{ $skill->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="col-span-12 flex items-end justify-between sm:col-span-4 sm:justify-end sm:gap-6">
            @if($project->is_featured)
                <span class="font-mono text-[10px] uppercase tracking-[0.12em] text-accent">{{ __('messages.projects.featured') }}</span>
            @endif
            <span class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2 transition-colors duration-150 group-hover:text-accent">
                {{ __('messages.ux_home.details') }} <span class="inline-block transition-transform duration-150 group-hover:translate-x-1">→</span>
            </span>
        </div>
    </a>
@endif
