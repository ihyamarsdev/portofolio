<x-layouts.app title="About">
    <div class="section-container py-14 sm:py-20">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-10">
            <!-- Sticky profile sidebar -->
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24">
                    @if($profile?->avatar)
                        <div class="h-28 w-28 overflow-hidden border border-line bg-subtle">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($profile->avatar) }}" alt="{{ $profile->name }}" width="112" height="112" loading="lazy" decoding="async" class="h-full w-full object-cover">
                        </div>
                    @endif

                    <h1 class="mt-6 text-3xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink">{{ $profile?->name ?? __('messages.about.title') }}</h1>
                    <p class="mt-2 font-mono text-xs uppercase tracking-[0.12em] text-accent">{{ $profile?->title ?? __('messages.about.profile_title_fallback') }}</p>

                    @if($homeSettings->availability_active)
                        <p class="mt-5 inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-accent"></span>
                            {{ $homeSettings->availability_status }}
                        </p>
                    @endif

                    <dl class="mt-8 divide-y divide-line border-y border-line font-mono text-xs">
                        @if($profile?->location)
                            <div class="flex justify-between gap-4 py-3">
                                <dt class="text-ink-3">{{ __('messages.contact.info_location') }}</dt>
                                <dd class="text-right text-ink">{{ $profile->location }}</dd>
                            </div>
                        @endif
                        @if($profile?->email)
                            <div class="flex justify-between gap-4 py-3">
                                <dt class="text-ink-3">{{ __('messages.contact.info_email') }}</dt>
                                <dd class="break-all text-right text-ink">{{ $profile->email }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <x-ui.button :href="route('cv.download')" variant="secondary" size="sm">{{ __('messages.ux_pages.download_cv') }}</x-ui.button>
                        <x-ui.button :href="route('contact')" size="sm">{{ __('messages.about.get_in_touch') }}</x-ui.button>
                    </div>
                </div>
            </aside>

            <div class="space-y-14 lg:col-span-8">
                <!-- Bio -->
                <section>
                    <x-ui.section-header :index="'01 / '.__('messages.about.eyebrow')" :title="__('messages.about.heading')" />
                    <div class="prose measure mt-6">
                        @if($profile?->biography)
                            {!! Str::markdown($profile->biography) !!}
                        @else
                            <p>{{ __('messages.about.subtitle') }}</p>
                        @endif
                    </div>
                </section>

                <!-- Skills -->
                <section>
                    <x-ui.section-header
                        :index="'02 / '.__('messages.about.skills')"
                        :title="__('messages.about.skills_matrix')"
                        :description="__('messages.about.skills_intro')" />

                    @if($coreSkills->isNotEmpty())
                        <div class="mt-6 border border-line bg-surface p-5">
                            <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-3">{{ __('messages.about.core_stack') }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($coreSkills as $coreSkill)
                                    <x-ui.tag :accent="true">{{ $coreSkill->name }}</x-ui.tag>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 grid gap-x-8 gap-y-8 sm:grid-cols-2">
                        @forelse($skillGroups as $category => $groupSkills)
                            <section>
                                <div class="flex items-baseline justify-between gap-3 border-b border-line pb-3">
                                    <h3 class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink">{{ $category }}</h3>
                                    <span class="font-mono text-[11px] text-ink-3">{{ $groupSkills->count() }}</span>
                                </div>
                                <ul class="mt-3 divide-y divide-line">
                                    @foreach($groupSkills as $skill)
                                        <li class="flex items-baseline justify-between gap-3 py-2.5">
                                            <span class="text-sm text-ink">{{ $skill->name }}</span>
                                            @if((float) $skill->years_experience > 0)
                                                <span class="shrink-0 font-mono text-[11px] text-ink-3">{{ trans_choice('messages.about.years', (float) $skill->years_experience, ['count' => rtrim(rtrim(number_format((float) $skill->years_experience, 1), '0'), '.')]) }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        @empty
                            <p class="text-sm text-ink-2 sm:col-span-2">{{ __('messages.about.skills_empty') }}</p>
                        @endforelse
                    </div>
                </section>

                <!-- Experience -->
                <section>
                    <x-ui.section-header :index="'03 / '.__('messages.about.experience')" :title="__('messages.about.experience')" />

                    @php($experiences = \App\Models\Experience::orderBy('order')->orderByDesc('start_date')->get())
                    <div class="mt-6 divide-y divide-line border-y border-line">
                        @forelse($experiences as $experience)
                            <div id="experience-{{ $experience->id }}" class="scroll-mt-24 py-6">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                                    <div>
                                        <h3 class="font-semibold tracking-[-0.01em] text-ink">{{ $experience->position }}</h3>
                                        <p class="mt-0.5 text-sm text-ink-2">{{ $experience->company }}</p>
                                    </div>
                                    <p class="shrink-0 font-mono text-[11px] uppercase tracking-wider text-ink-3">
                                        {{ $experience->start_date?->format('M Y') }} — {{ $experience->end_date?->format('M Y') ?? 'Present' }}
                                    </p>
                                </div>

                                @if($experience->description)
                                    <p class="measure mt-3 text-sm leading-relaxed text-ink-2">{{ $experience->description }}</p>
                                @endif

                                @php($achievements = $experience->achievements ?? [])
                                @if(count($achievements) > 0)
                                    <ul class="measure mt-4 space-y-2 text-sm leading-relaxed text-ink-2">
                                        @foreach(array_slice($achievements, 0, 4) as $achievement)
                                            <li class="flex gap-3"><span class="text-accent">—</span><span>{{ $achievement }}</span></li>
                                        @endforeach
                                    </ul>
                                    @if(count($achievements) > 4)
                                        <details class="group mt-3">
                                            <summary class="inline-flex cursor-pointer list-none items-center gap-2 font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2 transition-colors duration-150 hover:text-accent">
                                                <span class="inline-block transition-transform duration-150 group-open:rotate-90">→</span>
                                                <span class="group-open:hidden">{{ __('messages.ux_pages.show_more_achievements', ['count' => count($achievements) - 4]) }}</span>
                                                <span class="hidden group-open:inline">{{ __('messages.ux_pages.show_less') }}</span>
                                            </summary>
                                            <ul class="measure mt-3 space-y-2 text-sm leading-relaxed text-ink-2">
                                                @foreach(array_slice($achievements, 4) as $achievement)
                                                    <li class="flex gap-3"><span class="text-accent">—</span><span>{{ $achievement }}</span></li>
                                                @endforeach
                                            </ul>
                                        </details>
                                    @endif
                                @endif
                            </div>
                        @empty
                            <p class="py-6 text-sm text-ink-2">{{ __('messages.about.experience_empty') }}</p>
                        @endforelse
                    </div>
                </section>

                <!-- Education -->
                @php($educations = \App\Models\Education::orderBy('order')->orderByDesc('start_date')->get())
                @if($educations->isNotEmpty())
                    <section>
                        <x-ui.section-header :index="'04 / '.__('messages.about.education')" :title="__('messages.about.education')" />
                        <div class="mt-6 divide-y divide-line border-y border-line">
                            @foreach($educations as $education)
                                <div class="flex flex-col gap-2 py-5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                                    <div>
                                        <h3 class="font-semibold tracking-[-0.01em] text-ink">{{ $education->degree }} {{ $education->field_of_study }}</h3>
                                        <p class="mt-0.5 text-sm text-ink-2">{{ $education->institution }}</p>
                                    </div>
                                    <p class="shrink-0 font-mono text-[11px] uppercase tracking-wider text-ink-3">
                                        {{ $education->start_date?->format('Y') }} — {{ $education->end_date?->format('Y') ?? '—' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
