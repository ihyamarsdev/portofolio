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
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
