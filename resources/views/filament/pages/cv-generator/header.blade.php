        {{-- Unified Header --}}
        <div class="glass-card premium-workspace-header p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="pulse-dot"></span>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-wide">{{ __('cv-workspace.header.title') }}</h2>
                </div>
                <p class="text-sm text-slate-400 mt-1">{{ __('cv-workspace.header.subtitle') }}</p>
            </div>
            
            <div class="flex items-center gap-3">
                @if($activeTab !== 'aimatch')
                    <x-filament::button wire:click="analyze" color="primary" :loading="$isAnalyzing" class="btn-workspace-action glow-cyan-hover">
                        <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                        <span>{{ __('cv-workspace.header.run_analysis') }}</span>
                    </x-filament::button>
                @endif

                <x-filament::button wire:click="downloadCv" color="success" class="btn-workspace-action glow-green-hover">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-1.5" />
                    <span>{{ __('cv-workspace.header.download_pdf') }}</span>
                </x-filament::button>
            </div>
        </div>
