<x-layouts.app title="About">
    @php
        $skillsByCategory = \App\Models\Skill::with(['category', 'stacks'])->orderBy('years_experience', 'desc')->orderBy('name')->get()->groupBy(fn ($skill) => $skill->category?->name ?? 'Other');
    @endphp
    <section class="bg-bg-primary">
        <div class="section-container py-16 sm:py-24">
            <div class="grid gap-8 lg:grid-cols-[22rem_1fr]">
                <aside class="lg:sticky lg:top-24 lg:self-start">
                    <div class="rounded-3xl bg-bg-card p-6 text-center shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                        <div class="mx-auto h-32 w-32 overflow-hidden rounded-full bg-gradient-to-br from-sky-500 via-violet-500 to-emerald-500 p-1">
                            <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-full bg-bg-primary text-5xl">
                                @if($profile?->avatar)<img src="{{ Storage::url($profile->avatar) }}" alt="{{ $profile->name }}" class="h-full w-full object-cover">@else 👨‍💻 @endif
                            </div>
                        </div>
                        <h1 class="mt-5 text-2xl font-semibold tracking-[-.04em] text-text-primary">{{ $profile?->name ?? 'DevOps Engineer' }}</h1>
                        <p class="mt-2 text-sm text-text-secondary">{{ $profile?->title ?? 'Building scalable infrastructure' }}</p>
                        <div class="mt-6 space-y-3 text-left text-sm text-text-secondary">
                            <p class="rounded-xl bg-bg-secondary p-3">📍 {{ $profile?->location ?? 'Indonesia' }}</p>
                            <p class="rounded-xl bg-bg-secondary p-3">⚡ Open for opportunities</p>
                        </div>
                        <a href="{{ route('contact') }}" class="mt-6 inline-flex w-full justify-center rounded-lg bg-text-primary px-4 py-3 text-sm font-semibold text-bg-primary">Get in touch</a>
                    </div>
                </aside>

                <div class="space-y-10">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-widest text-accent-sky">About</p>
                        <h2 class="mt-2 text-4xl font-semibold tracking-[-.05em] text-text-primary sm:text-6xl">Engineer focused on resilient systems.</h2>
                        <div class="prose mt-6 max-w-none text-text-secondary dark:prose-invert prose-p:leading-8">
                            @if($profile?->biography)
                                {!! Str::markdown($profile->biography) !!}
                            @else
                                <p>I'm a DevOps Engineer passionate about automating everything and building reliable, scalable infrastructure.</p>
                                <p>My focus areas include infrastructure as code, Kubernetes, observability, CI/CD, and backend systems.</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-5 text-2xl font-semibold tracking-[-.04em] text-text-primary">Skills matrix</h3>
                        <div class="grid gap-5 md:grid-cols-2">
                            @forelse($skillsByCategory as $category => $skills)
                                <section class="rounded-2xl bg-bg-card p-5 shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                                    <div class="mb-4 flex items-center justify-between gap-3">
                                        <h4 class="font-semibold text-text-primary">{{ $category }}</h4>
                                        <span class="rounded-full bg-sky-500/10 px-2 py-1 text-xs text-accent-sky">{{ $skills->count() }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($skills as $skill)
                                            <span class="rounded-full bg-bg-secondary px-3 py-1 text-sm text-text-secondary">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                </section>
                            @empty
                                <div class="rounded-2xl bg-bg-card p-8 text-text-secondary shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">Skills will appear here.</div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-5 text-2xl font-semibold tracking-[-.04em] text-text-primary">Experience</h3>
                        <div class="space-y-6">
                            @php
                                $experiences = \App\Models\Experience::orderBy('order', 'asc')
                                    ->orderByDesc('start_date')
                                    ->get();
                            @endphp
                            @forelse($experiences as $index => $experience)
                                <div class="relative pl-8 border-l-2 {{ $index === 0 ? 'border-sky-500' : 'border-slate-200 dark:border-slate-800' }}">
                                    <div class="absolute left-0 top-1.5 w-4 h-4 -translate-x-[9px] {{ $index === 0 ? 'bg-sky-500 ring-4 ring-sky-500/10' : 'bg-slate-200 dark:bg-slate-800' }} rounded-full"></div>
                                    <div class="rounded-2xl bg-bg-card p-6 shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                                        <div class="flex flex-wrap justify-between gap-2 mb-2">
                                            <div>
                                                <h4 class="font-semibold text-text-primary text-lg">{{ $experience->position }}</h4>
                                                <p class="text-sm text-text-secondary font-medium">{{ $experience->company }}</p>
                                            </div>
                                            <div class="text-left sm:text-right">
                                                <span class="text-xs font-mono text-accent-sky bg-sky-500/10 px-2 py-1 rounded-md">
                                                    {{ $experience->start_date->format('M Y') }} —
                                                    {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                                                </span>
                                                @if($experience->location)
                                                    <p class="text-xs text-text-secondary mt-2">📍 {{ $experience->location }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if($experience->description)
                                            <p class="text-text-secondary text-sm mb-3 leading-relaxed">{{ $experience->description }}</p>
                                        @endif
                                        @if($experience->achievements && count($experience->achievements) > 0)
                                            <ul class="text-sm text-text-secondary space-y-1.5 list-disc pl-4 mb-3">
                                                @foreach($experience->achievements as $achievement)
                                                    <li>{{ $achievement }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-bg-card p-8 text-text-secondary text-center shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">No experiences added yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
