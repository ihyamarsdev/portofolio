<x-layouts.app title="Home">
    @php
        $cache = app(\App\Services\PortfolioCacheService::class);
        $homeSettings = $homeSettings ?? $cache->settings();
        $profile = $profile ?? $cache->primaryProfile();
        $resumeTemplate = $profile?->cv_template ?: 'ats';
        $projects = $featuredProjects;
        $posts = $latestPosts;
        $experiences = $latestExperiences;
    @endphp

    <!-- Hero -->
    <section class="border-b border-line">
        <div class="section-container py-16 sm:py-24 lg:py-28">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-8">
                <div class="lg:col-span-8">
                    @if($homeSettings->availability_active)
                        <p class="inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.14em] text-ink-2">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-accent"></span>
                            {{ $homeSettings->availability_status }}
                        </p>
                    @endif

                    <h1 class="mt-6 text-4xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink sm:text-6xl">
                        <span class="block text-ink-3">{{ $homeSettings->hero_greeting }}</span>
                        <span class="block">{{ $homeSettings->site_title }}</span>
                    </h1>

                    <div class="measure mt-7 border-l-2 border-accent pl-5 text-base leading-relaxed text-ink-2 sm:text-lg">
                        {!! $homeSettings->hero_description !!}
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <x-ui.button :href="route('contact')">{{ $homeSettings->cta_primary_button_text }}</x-ui.button>
                        <x-ui.button :href="route('cv.preview', ['template' => $resumeTemplate])" variant="secondary" target="_blank" rel="noopener">{{ $homeSettings->cta_secondary_button_text }}</x-ui.button>
                    </div>
                </div>

                <div class="lg:col-span-4 lg:pt-2">
                    <div class="border-t border-line pt-4">
                        <p class="font-mono text-[11px] uppercase tracking-[0.16em] text-ink-3">00 / {{ $homeSettings->site_brand }}</p>
                        <dl class="mt-5 divide-y divide-line font-mono text-xs">
                            <div class="flex justify-between gap-4 py-3">
                                <dt class="text-ink-3">{{ __('messages.about.experience') }}</dt>
                                <dd class="text-right text-ink">{{ $experiences->count() }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-3">
                                <dt class="text-ink-3">{{ __('messages.projects.title') }}</dt>
                                <dd class="text-right text-ink">{{ $projectCount }}</dd>
                            </div>
                            <div class="flex justify-between gap-4 py-3">
                                <dt class="text-ink-3">{{ __('messages.nav.contact') }}</dt>
                                <dd class="truncate text-right text-ink">{{ $profile?->location ?? __('Indonesia') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Selected projects -->
    @if($projects->isNotEmpty())
        <section class="section-container py-16 sm:py-20">
            <x-ui.section-header
                :index="__('messages.home.featured_projects')"
                :title="$homeSettings->featured_projects_title"
                :description="$homeSettings->featured_projects_description">
                <x-slot:action>
                    <x-ui.button :href="route('projects')" variant="ghost" size="sm">
                        {{ __('messages.home.view_all_projects') }} <span class="transition-transform duration-150 group-hover:translate-x-1">→</span>
                    </x-ui.button>
                </x-slot:action>
            </x-ui.section-header>

            <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($projects as $project)
                    <x-ui.project-item :project="$project" :index="str_pad($loop->iteration, 2, '0', STR_PAD_LEFT)" layout="card" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Experience & stack -->
    <section class="border-t border-line bg-surface">
        <div class="section-container py-16 sm:py-20">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-10">
                <div class="lg:col-span-4">
                    <x-ui.section-header :index="'01 / '.__('messages.about.experience')" :title="$homeSettings->tech_stack_title" :description="$homeSettings->tech_stack_description" />
                </div>

                <div class="lg:col-span-8">
                    @if($experiences->isNotEmpty())
                        <div class="divide-y divide-line border-y border-line">
                            @foreach($experiences as $experience)
                                <div class="grid gap-2 py-5 sm:grid-cols-12 sm:gap-4">
                                    <div class="font-mono text-[11px] uppercase tracking-wider text-ink-3 sm:col-span-3">
                                        {{ $experience->start_date?->format('Y') }} — {{ $experience->end_date?->format('Y') ?? __('messages.about.open_opportunities') }}
                                    </div>
                                    <div class="sm:col-span-9">
                                        <h3 class="font-semibold tracking-[-0.01em] text-ink">{{ $experience->position }}</h3>
                                        <p class="mt-0.5 text-sm text-ink-2">{{ $experience->company }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($stacks->isNotEmpty())
                        <div class="mt-10">
                            <p class="font-mono text-[11px] uppercase tracking-[0.16em] text-ink-3">{{ __('messages.home.tech_stack') }}</p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($stacks as $stack)
                                    <x-ui.tag>{{ $stack->name }}</x-ui.tag>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Recent writing -->
    @if($posts->isNotEmpty())
        <section class="section-container py-16 sm:py-20">
            <x-ui.section-header
                :index="__('messages.ux_home.blog_section_badge')"
                :title="__('messages.ux_home.blog_section_title')"
                :description="__('messages.ux_home.blog_section_desc')">
                <x-slot:action>
                    <x-ui.button :href="route('blog')" variant="ghost" size="sm">
                        {{ __('messages.ux_home.view_all_posts') }} <span class="transition-transform duration-150 group-hover:translate-x-1">→</span>
                    </x-ui.button>
                </x-slot:action>
            </x-ui.section-header>

            <div class="mt-8 border-t border-line">
                @foreach($posts as $post)
                    <x-ui.post-row :post="$post" :index="str_pad($loop->iteration, 2, '0', STR_PAD_LEFT)" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Contact CTA -->
    <section class="border-t border-line">
        <div class="section-container py-16 sm:py-20">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-8">
                    <p class="font-mono text-[11px] uppercase tracking-[0.16em] text-accent">{{ __('messages.ux_home.contact_cta_badge') }}</p>
                    <h2 class="mt-4 max-w-2xl text-3xl font-semibold leading-[1.08] tracking-[-0.03em] text-ink sm:text-4xl">{{ $homeSettings->cta_title }}</h2>
                    <p class="measure mt-4 text-base leading-relaxed text-ink-2">{{ $homeSettings->cta_description }}</p>
                </div>
                <div class="flex flex-wrap gap-3 lg:col-span-4 lg:justify-end">
                    <x-ui.button :href="route('contact')">{{ $homeSettings->cta_primary_button_text ?: __('messages.ux_home.contact_cta_button') }}</x-ui.button>
                    <x-ui.button :href="route('projects')" variant="secondary">{{ __('messages.ux_home.explore_projects') }}</x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
