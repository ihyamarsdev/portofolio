<x-layouts.app title="About">
    @php
        $skillsByCategory = \App\Models\Skill::with(['category', 'stacks'])->orderByDesc('proficiency')->orderByDesc('years_experience')->orderBy('name')->get()->groupBy(fn ($skill) => $skill->category?->name ?? 'Other');
    @endphp
    <section class="bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container py-10 sm:py-14">
            <div class="grid gap-8 lg:grid-cols-[22rem_1fr]">
                <aside class="lg:sticky lg:top-[84px] lg:self-start">
                    <div class="brutal-card p-6 text-center bg-white dark:bg-[#1E293B]">
                        <div class="mx-auto h-32 w-32 overflow-hidden rounded-full border-[3px] border-black dark:border-[#CBD5E1] shadow-[4px_4px_0_0_#111] dark:shadow-[4px_4px_0_0_rgba(203,213,225,0.9)] bg-[#2563EB] p-1">
                            <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-[#FFFBF0] dark:bg-[#1E293B] text-5xl">
                                @if($profile?->avatar)<img src="{{ Storage::url($profile->avatar) }}" alt="{{ $profile->name }}" class="h-full w-full object-cover">@else 👨‍💻 @endif
                            </div>
                        </div>
                        <h1 class="mt-4 text-2xl font-black tracking-[-0.04em] text-black dark:text-white leading-none">{{ $profile?->name ?? 'DevOps Engineer' }}</h1>
                        <p class="mt-1.5 inline-flex font-mono text-[11px] font-black uppercase tracking-widest bg-black dark:bg-[#3B82F6] text-white dark:text-white px-2.5 py-1 rounded-full border-2 border-black">{{ $profile?->title ?? 'Building scalable infra' }}</p>
                        <div class="mt-5 space-y-2.5 text-left">
                            <p class="brutal-card brutal-card--flat p-3 text-sm font-bold flex items-center gap-2 !shadow-[3px_3px_0_0_#111] dark:!shadow-[3px_3px_0_0_rgba(203,213,225,0.9)]"><span class="w-8 h-8 inline-flex items-center justify-center bg-[#2563EB] border-2 border-black rounded-full text-sm">📍</span> {{ $profile?->location ?? __('Indonesia') }}</p>
                            <p class="brutal-card brutal-card--flat p-3 text-sm font-bold flex items-center gap-2 !shadow-[3px_3px_0_0_#111] dark:!shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] bg-[#B8FF66] dark:bg-[#1E293B]"><span class="w-2 h-2 bg-black dark:bg-[#B8FF66] rounded-full animate-pulse"></span> {{ __('messages.about.open_opportunities') }}</p>
                        </div>
                        <div class="mt-5 space-y-2.5">
                            <a href="{{ route('cv.download') }}" class="brutal-btn brutal-btn--white w-full py-3.5 text-sm min-h-[44px] inline-flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ __('messages.ux_pages.download_cv') }}
                            </a>
                            <a href="{{ route('contact') }}" class="brutal-btn brutal-btn--primary w-full py-3.5 text-sm min-h-[44px] inline-flex items-center justify-center">{{ __('messages.about.get_in_touch') }} →</a>
                        </div>
                    </div>
                </aside>

                <div class="space-y-8">
                    <div class="brutal-card p-6 sm:p-8">
                        <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-black dark:bg-[#FFFBF0] text-white dark:text-black px-2.5 py-1 rounded-[6px] border-2 border-black">{{ __('messages.about.eyebrow') }} — 01</span>
                        <h2 class="mt-3 text-3xl sm:text-5xl font-black tracking-[-0.06em] leading-[0.9] text-black dark:text-white">{{ __('messages.about.heading') }}</h2>
                        <div class="prose mt-6 max-w-none">
                            @if($profile?->biography)
                                {!! Str::markdown($profile->biography) !!}
                            @else
                                <p>I'm a DevOps Engineer passionate about automating everything and building reliable, scalable infrastructure.</p>
                                <p>My focus areas include infrastructure as code, Kubernetes, observability, CI/CD, and backend systems.</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        @php
                            $proficiencyLabels = [
                                1 => 'Beginner', 2 => 'Developing', 3 => 'Intermediate',
                                4 => 'Advanced', 5 => 'Expert',
                            ];
                            $totalSkills = $skillsByCategory->flatten()->count();
                        @endphp
                        <div class="flex flex-wrap items-end justify-between gap-3 mb-5">
                            <h3 class="text-2xl font-black tracking-[-0.04em] text-black dark:text-white flex items-center gap-2"><span class="w-1.5 h-7 bg-[#00D1FF] border-2 border-black rounded-full"></span> {{ __('messages.about.skills_matrix') }}</h3>
                            <div class="flex gap-2">
                                <span class="font-mono text-xs font-black uppercase bg-[#2563EB] text-white border-2 border-black px-2 py-1 rounded-full">{{ $skillsByCategory->count() }} categories</span>
                                <span class="font-mono text-xs font-black uppercase bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] px-2 py-1 rounded-full">{{ $totalSkills }} skills</span>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            @forelse($skillsByCategory as $category => $skills)
                                @php
                                    $cat = $skills->first()?->category;
                                    $catColor = $cat?->color ?? '#2563EB';
                                    $sorted = $skills->sortByDesc(fn ($s) => $s->proficiency)->values();
                                @endphp
                                <section class="brutal-card p-5">
                                    <div class="mb-4 flex items-center justify-between gap-3 border-b-2 border-black dark:border-[#CBD5E1] pb-3">
                                        <h4 class="font-black tracking-tight text-black dark:text-white uppercase text-sm flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 border-2 border-black rounded-full shrink-0" style="background: {{ $catColor }}"></span>
                                            {{ $category }}
                                        </h4>
                                        <span class="brutal-badge brutal-badge--cyan text-[11px]">{{ $sorted->count() }}</span>
                                    </div>
                                    <div class="space-y-3.5">
                                        @foreach($sorted as $skill)
                                            @php
                                                $pct = max(5, min(100, (int) round($skill->proficiency * 20)));
                                            @endphp
                                            <div>
                                                <div class="flex items-center justify-between gap-3 mb-1">
                                                    <span class="text-sm font-black uppercase tracking-tight text-black dark:text-white">{{ $skill->name }}</span>
                                                    <span class="flex items-center gap-2 shrink-0">
                                                        <span class="font-mono text-xs font-bold text-[#2a2a2a] dark:text-[#d6d6d6]">{{ number_format((float) $skill->years_experience, 1) }}y</span>
                                                        <span class="font-mono text-xs font-black uppercase px-2 py-0.5 rounded border-2 border-black bg-black text-white dark:bg-white dark:text-black">{{ $proficiencyLabels[$skill->proficiency] ?? $skill->proficiency }}</span>
                                                    </span>
                                                </div>
                                                <div class="h-3 w-full border-2 border-black dark:border-[#CBD5E1] rounded-sm bg-white dark:bg-[#111] overflow-hidden">
                                                    <div class="h-full transition-all" style="width: {{ $pct }}%; background: {{ $catColor }}"></div>
                                                </div>
                                                @if($skill->stacks->isNotEmpty())
                                                    <div class="mt-1.5 flex flex-wrap gap-1">
                                                        @foreach($skill->stacks->take(4) as $stack)
                                                            <span class="inline-flex items-center gap-1 font-mono text-xs font-bold uppercase tracking-tight bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-full px-2 py-0.5">
                                                                @if(filter_var($stack->icon, FILTER_VALIDATE_URL))
                                                                    <img src="{{ $stack->icon }}" class="w-3.5 h-3.5 object-contain" alt="">
                                                                @elseif(str_starts_with($stack->icon ?? '', 'devicon-'))
                                                                    <i class="{{ $stack->icon }} text-xs"></i>
                                                                @endif
                                                                {{ $stack->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            @empty
                                <div class="brutal-card p-8 text-center font-black uppercase text-[#6b6b6b] md:col-span-2">{{ __('messages.about.skills_empty') }}</div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-4 text-2xl font-black tracking-[-0.04em] text-black dark:text-white flex items-center gap-2"><span class="w-1.5 h-7 bg-[#FF90E8] border-2 border-black rounded-full"></span> {{ __('messages.about.experience') }}</h3>
                        <div class="space-y-5">
                            @php
                                $experiences = \App\Models\Experience::orderBy('order', 'asc')->orderByDesc('start_date')->get();
                            @endphp
                            @forelse($experiences as $index => $experience)
                                <div class="relative pl-7 border-l-[3px] {{ $index === 0 ? 'border-black dark:border-[#2563EB]' : 'border-black/20 dark:border-white/20' }}">
                                    <div class="absolute left-0 top-2 w-4 h-4 -translate-x-[9px] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-full {{ $index === 0 ? 'bg-[#2563EB] shadow-[2px_2px_0_0_#111]' : 'bg-white dark:bg-[#1E293B]' }}"></div>
                                    <div class="brutal-card p-5 sm:p-6 {{ $index === 0 ? 'bg-[#2563EB] dark:bg-[#1E293B]' : '' }}">
                                        <div class="flex flex-wrap justify-between gap-2 mb-2">
                                            <div>
                                                <h4 class="font-black text-black dark:text-white text-lg leading-none tracking-tight">{{ $experience->position }}</h4>
                                                <p class="text-sm font-bold text-[#2a2a2a] dark:text-[#d6d6d6]">{{ $experience->company }}</p>
                                            </div>
                                            <div class="text-left sm:text-right">
                                                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-widest bg-black dark:bg-white text-white dark:text-black px-2 py-1 rounded border-2 border-black">
                                                    {{ $experience->start_date->format('M Y') }} — {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                                                </span>
                                                @if($experience->location)
                                                    <p class="text-xs font-bold mt-1.5">📍 {{ $experience->location }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($experience->description)
                                            <p class="text-sm leading-6 font-medium text-[#2a2a2a] dark:text-[#d6d6d6]">{{ $experience->description }}</p>
                                        @endif
                                        @php($achievements = $experience->achievements ?? [])
                                        @if(count($achievements) > 0)
                                            <div class="mt-4">
                                                <ul class="space-y-3 text-sm font-medium list-disc pl-4 marker:font-black leading-relaxed text-[#2a2a2a] dark:text-[#d6d6d6]">
                                                    @foreach(array_slice($achievements, 0, 4) as $achievement)
                                                        <li>{{ $achievement }}</li>
                                                    @endforeach
                                                </ul>
                                                @if(count($achievements) > 4)
                                                    <details class="group mt-3">
                                                        <summary class="cursor-pointer font-mono text-xs font-black uppercase tracking-wider text-black dark:text-white bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-[6px] px-3 py-2 min-h-[44px] shadow-[2px_2px_0_0_#111] dark:shadow-[2px_2px_0_0_rgba(203,213,225,0.9)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none transition-all list-none inline-flex items-center gap-2 select-none">
                                                            <span class="inline-block transition-transform duration-200 group-open:rotate-90">▶</span>
                                                            <span class="group-open:hidden">{{ __('messages.ux_pages.show_more_achievements', ['count' => count($achievements) - 4]) }}</span>
                                                            <span class="hidden group-open:inline">{{ __('messages.ux_pages.show_less') }}</span>
                                                        </summary>
                                                        <ul class="mt-3 space-y-3 text-sm font-medium list-disc pl-4 marker:font-black leading-relaxed text-[#2a2a2a] dark:text-[#d6d6d6]">
                                                            @foreach(array_slice($achievements, 4) as $achievement)
                                                                <li>{{ $achievement }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </details>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="brutal-card p-8 text-center font-black uppercase md:col-span-2">{{ __('messages.about.experience_empty') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>