<x-filament-panels::page>
    @include('filament.pages.cv-generator.gradients')

    <div class="cv-workspace-container space-y-8 font-space-grotesk text-slate-700 dark:text-slate-300">
        @include('filament.pages.cv-generator.header')
        @include('filament.pages.cv-generator.tabs')

        {{-- Tab Panels Container --}}
        <div class="relative min-h-[400px]">
            {{-- Panel loading indicator --}}
            <div wire:loading.delay wire:target="switchTab" class="absolute inset-x-0 top-0 z-20 flex justify-center pt-6 pointer-events-none">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 dark:bg-slate-950/80 backdrop-blur border border-cyan-500/30 text-cyan-400 text-xs font-mono shadow-lg shadow-cyan-500/10">
                    <x-filament::loading-indicator class="w-3.5 h-3.5 text-cyan-400" />
                    <span>{{ __('cv-workspace.page.loading') }}</span>
                </div>
            </div>

            <div wire:loading.class="opacity-60 transition-opacity" wire:target="switchTab">
                @if($activeTab === 'overview')
                    <div id="tabpanel-overview" role="tabpanel" aria-labelledby="tab-overview" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-overview')
                    </div>
                @endif

                @if($activeTab === 'profile')
                    <div id="tabpanel-profile" role="tabpanel" aria-labelledby="tab-profile" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-profile')
                    </div>
                @endif

                @if($activeTab === 'experience')
                    <div id="tabpanel-experience" role="tabpanel" aria-labelledby="tab-experience" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-experience')
                    </div>
                @endif

                @if($activeTab === 'education')
                    <div id="tabpanel-education" role="tabpanel" aria-labelledby="tab-education" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-education')
                    </div>
                @endif

                @if($activeTab === 'skills')
                    <div id="tabpanel-skills" role="tabpanel" aria-labelledby="tab-skills" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-skills')
                    </div>
                @endif

                @if($activeTab === 'certificates')
                    <div id="tabpanel-certificates" role="tabpanel" aria-labelledby="tab-certificates" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-certificates')
                    </div>
                @endif

                @if($activeTab === 'aimatch')
                    <div id="tabpanel-aimatch" role="tabpanel" aria-labelledby="tab-aimatch" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-aimatch')
                    </div>
                @endif

                @if($activeTab === 'preview')
                    <div id="tabpanel-preview" role="tabpanel" aria-labelledby="tab-preview" tabindex="0" class="outline-none">
                        @include('filament.pages.cv-generator.tab-preview')
                    </div>
                @endif
            </div>
        </div>
        @include('filament.pages.cv-generator.modals')
    </div>
</x-filament-panels::page>
