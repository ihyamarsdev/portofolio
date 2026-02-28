<x-layouts.app title="About">
    <section class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 sticky top-24 gradient-card">
                        <div
                            class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-emerald-500 via-cyan-500 to-violet-500 rounded-full flex items-center justify-center overflow-hidden shadow-lg glow-violet">
                            @if($profile && $profile->avatar)
                                <img src="{{ Storage::url($profile->avatar) }}" alt="{{ $profile->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl">👨‍💻</span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-bold text-[--color-text-primary] text-center mb-2">
                            {{ $profile ? $profile->name : 'DevOps Engineer' }}</h1>
                        <p class="text-[--color-text-secondary] text-center mb-6">
                            {{ $profile ? $profile->title : 'Building scalable infrastructure' }}</p>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-3 text-[--color-text-secondary]">
                                <svg class="w-5 h-5 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $profile ? $profile->location : 'Indonesia' }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-[--color-text-secondary]">
                                <svg class="w-5 h-5 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Open for opportunities</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-[--color-border]">
                            <a href="{{ route('contact') }}" class="btn-primary-sunset w-full justify-center">
                                Get In Touch
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- About -->
                    <div>
                        <h2 class="text-2xl font-bold text-[--color-text-primary] mb-4">About Me</h2>
                        <div class="prose prose-invert max-w-none text-[--color-text-secondary]">
                            @if($profile && $profile->biography)
                                {!! Str::markdown($profile->biography) !!}
                            @else
                                <p>
                                    I'm a DevOps Engineer passionate about automating everything and building reliable,
                                    scalable infrastructure.
                                    With expertise in cloud platforms, container orchestration, and CI/CD pipelines, I help
                                    teams ship faster and more reliably.
                                </p>
                                <p>
                                    My focus areas include infrastructure as code, Kubernetes orchestration, and building
                                    robust monitoring solutions.
                                    I believe in the philosophy of "everything as code" and continuously improving
                                    deployment processes.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Skills Matrix -->
                    <div>
                        <h2 class="text-2xl font-bold text-[--color-text-primary] mb-6">Skills Matrix</h2>

                        @php
                            $skillsByCategory = \App\Models\Skill::with(['category', 'stacks'])
                                ->orderBy('years_experience', 'desc')
                                ->orderBy('name')
                                ->get()
                                ->groupBy(fn($skill) => $skill->category?->name ?? 'Other');
                        @endphp

                        @if($skillsByCategory->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($skillsByCategory as $category => $skills)
                                    <div class="glass-card p-6 gradient-card">
                                        <div class="text-center mb-5">
                                            <h3 class="font-semibold text-lg text-[--color-text-primary] mb-2">{{ $category }}</h3>
                                            <span class="inline-block text-xs text-[--color-text-muted] bg-[--color-bg-secondary] px-2 py-1 rounded-full">
                                                {{ count($skills) }} {{ count($skills) == 1 ? 'skill' : 'skills' }}
                                            </span>
                                        </div>
                                        <div class="space-y-3">
                                            @foreach($skills as $skill)
                                                <div class="group">
                                                    <div class="flex items-start justify-between py-2.5 px-3 rounded-lg hover:bg-[--color-bg-secondary] transition-all duration-200 gap-3">
                                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                                            <div class="w-1.5 h-1.5 rounded-full bg-[--color-accent-emerald] group-hover:scale-150 transition-transform flex-shrink-0 mt-1.5"></div>
                                                            <div class="flex-1 min-w-0">
                                                                <span class="text-sm text-[--color-text-primary] group-hover:text-[--color-accent-emerald] font-medium transition-colors">
                                                                    {{ $skill->name }}
                                                                </span>
                                                                {{-- Tech Stack Badges --}}
                                                                @if($skill->stacks && $skill->stacks->isNotEmpty())
                                                                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                                        @foreach($skill->stacks as $stack)
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-medium border bg-opacity-10"
                                                                                style="background-color: {{ $stack->color ?? '#6366f1' }}20; border-color: {{ $stack->color ?? '#6366f1' }}40; color: {{ $stack->color ?? '#6366f1' }};">
                                                                                {{ $stack->name }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($skill->years_experience > 0)
                                                            @php
                                                                $years = floatval($skill->years_experience);
                                                                $isHalfYear = fmod($years, 1) > 0;
                                                                $displayYears = $isHalfYear ? number_format($years, 1) : intval($years);
                                                            @endphp
                                                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                                                <svg class="w-3.5 h-3.5 text-[--color-accent-cyan]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="text-xs font-semibold text-[--color-accent-emerald]">
                                                                    {{ $displayYears }}
                                                                </span>
                                                                <span class="text-xs text-[--color-text-muted]">
                                                                    {{ $years == 1 ? 'yr' : 'yrs' }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach(['Cloud Platforms', 'Container & Orchestration', 'CI/CD', 'Infrastructure as Code'] as $category)
                                    <div class="glass-card p-5 gradient-card">
                                        <h3 class="font-semibold text-[--color-accent-emerald] mb-4">{{ $category }}</h3>
                                        <p class="text-sm text-[--color-text-secondary]">Add skills through the admin panel.</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Experience Timeline -->
                    <div>
                        <h2 class="text-2xl font-bold text-[--color-text-primary] mb-6">Experience</h2>
                        <div class="space-y-6">
                            @php
                                $experiences = \App\Models\Experience::with('skills.category')
                                    ->orderBy('order')
                                    ->orderByDesc('start_date')
                                    ->get();
                            @endphp
                            @if($experiences->isNotEmpty())
                                @foreach($experiences as $index => $experience)
                                    <div class="relative pl-8 border-l-2 {{ $index === 0 ? 'border-violet-500' : 'border-[--color-border]' }}">
                                        <div
                                            class="absolute left-0 top-0 w-4 h-4 -translate-x-[9px] {{ $index === 0 ? 'bg-gradient-to-br from-violet-500 to-purple-500 glow-violet' : 'bg-[--color-border]' }} rounded-full">
                                        </div>
                                        <div class="glass-card p-5 gradient-card">
                                            <div class="flex flex-wrap justify-between gap-2 mb-2">
                                                <div>
                                                    <h3 class="font-semibold text-[--color-text-primary]">{{ $experience->position }}</h3>
                                                    <p class="text-sm text-[--color-text-secondary]">{{ $experience->company }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm {{ $experience->end_date ? 'text-[--color-text-muted]' : 'text-[--color-accent-emerald]' }}">
                                                        {{ $experience->start_date->format('Y') }}
                                                        {{ $experience->end_date ? '- ' . $experience->end_date->format('Y') : '- Present' }}
                                                    </span>
                                                    @if($experience->location)
                                                        <p class="text-xs text-[--color-text-muted] mt-1">{{ $experience->location }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($experience->description)
                                                <p class="text-[--color-text-secondary] text-sm mb-3">{{ $experience->description }}</p>
                                            @endif
                                            @if($experience->achievements && count($experience->achievements) > 0)
                                                <ul class="text-sm text-[--color-text-secondary] space-y-2 mb-6">
                                                    @foreach($experience->achievements as $achievement)
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-[--color-accent-emerald]">•</span>
                                                            <span class="flex-1">{{ $achievement }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            
                                            {{-- Skills Used --}}
                                            @if($experience->skills->isNotEmpty())
                                                <div class="mt-5 pt-4 border-t border-[--color-border]">
                                                    <div class="flex items-center gap-2 mb-3">
                                                        <svg class="w-4 h-4 text-[--color-accent-cyan]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                        </svg>
                                                        <span class="text-xs font-semibold text-[--color-text-muted] uppercase tracking-wide">Skills Used</span>
                                                    </div>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($experience->skills as $skill)
                                                            <span class="group relative inline-flex items-center gap-1.5 py-1 px-2 rounded-lg bg-gradient-to-br from-[--color-accent-emerald]/10 to-[--color-accent-cyan]/10 border border-[--color-accent-emerald]/20 hover:border-[--color-accent-emerald]/40 transition-all duration-200 hover:scale-105">
                                                                <span class="text-xs font-medium text-[--color-text-primary]">{{ $skill->name }}</span>
                                                                @if($skill->category)
                                                                    <span class="text-xs text-[--color-text-muted]">·</span>
                                                                    <span class="text-xs text-[--color-text-muted]">{{ $skill->category->name }}</span>
                                                                @endif
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="glass-card p-5 text-center gradient-card">
                                    <p class="text-[--color-text-secondary]">No experiences added yet. Add experiences through the admin panel.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>