<x-layouts.app title="About">
    <section class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 sticky top-24">
                        <div
                            class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-[--color-accent-emerald] to-[--color-accent-cyan] rounded-full flex items-center justify-center overflow-hidden">
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
                            <a href="{{ route('contact') }}" class="btn-primary w-full justify-center">
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
                            $skillsByCategory = \App\Models\Skill::with('category')
                                ->orderBy('proficiency', 'desc')
                                ->get()
                                ->groupBy(fn($skill) => $skill->category?->name ?? 'Other');
                        @endphp

                        @if($skillsByCategory->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($skillsByCategory as $category => $skills)
                                    <div class="glass-card p-5">
                                        <h3 class="font-semibold text-[--color-accent-emerald] mb-4">{{ $category }}</h3>
                                        <div class="space-y-4">
                                            @foreach($skills as $skill)
                                                <div>
                                                    <div class="flex justify-between mb-1">
                                                        <span class="text-sm text-[--color-text-primary]">{{ $skill->name }}</span>
                                                        <span
                                                            class="text-xs text-[--color-text-muted]">{{ $skill->years_experience }}+
                                                            years</span>
                                                    </div>
                                                    <div class="skill-bar">
                                                        <div class="skill-bar-fill" style="width: {{ $skill->proficiency * 20 }}%">
                                                        </div>
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
                                    <div class="glass-card p-5">
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
                                $experiences = \App\Models\Experience::orderBy('order')->orderByDesc('start_date')->get();
                            @endphp
                            @if($experiences->isNotEmpty())
                                @foreach($experiences as $index => $experience)
                                    <div class="relative pl-8 border-l-2 {{ $index === 0 ? 'border-[--color-accent-emerald]' : 'border-[--color-border]' }}">
                                        <div
                                            class="absolute left-0 top-0 w-4 h-4 -translate-x-[9px] {{ $index === 0 ? 'bg-[--color-accent-emerald]' : 'bg-[--color-border]' }} rounded-full">
                                        </div>
                                        <div class="glass-card p-5">
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
                                                <ul class="text-sm text-[--color-text-secondary] space-y-1">
                                                    @foreach($experience->achievements as $achievement)
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-[--color-accent-emerald] mt-1">•</span>
                                                            <span>{{ $achievement }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="glass-card p-5 text-center">
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