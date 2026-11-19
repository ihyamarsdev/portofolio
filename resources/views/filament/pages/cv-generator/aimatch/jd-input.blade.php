{{-- Collapsible JD input card --}}
<div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
    <button type="button" wire:click="toggleJdInput"
            class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
        <span class="flex items-center gap-2.5 min-w-0">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-md border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 shrink-0">
                <x-heroicon-o-briefcase class="w-4 h-4" />
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.input.title') }}</span>
                <span class="block text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ __('cv-aimatch.input.hint') }}</span>
            </span>
        </span>
        <x-heroicon-o-chevron-down class="w-4 h-4 text-zinc-400 shrink-0 transition-transform {{ $showJdInput ? 'rotate-180' : '' }}" />
    </button>

    @if($showJdInput)
        <div class="border-t border-zinc-200 dark:border-zinc-700 px-4 py-4 space-y-4">
            <div>
                <label for="am-target-role" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('cv-aimatch.input.target_role') }}</label>
                <select id="am-target-role" wire:model.live="targetRole"
                        class="w-full rounded-md border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm text-zinc-900 dark:text-zinc-100 focus:border-zinc-500 focus:ring-zinc-500/20">
                    <option value="Devops Engineer">{{ __('cv-aimatch.input.roles.devops') }}</option>
                    <option value="Devsecops Engineer">{{ __('cv-aimatch.input.roles.devsecops') }}</option>
                    <option value="Cloud Engineer">{{ __('cv-aimatch.input.roles.cloud') }}</option>
                    <option value="Site Reliability Engineer">{{ __('cv-aimatch.input.roles.sre') }}</option>
                </select>
            </div>

            <div>
                <label for="am-jobdesc" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                    {{ __('cv-aimatch.input.jobdesc') }}
                    <span class="font-normal text-zinc-400">({{ __('cv-aimatch.input.optional') }})</span>
                </label>
                <textarea id="am-jobdesc" wire:model="jobDescription" rows="8"
                          placeholder="{{ __('cv-aimatch.input.jobdesc_placeholder') }}"
                          class="w-full rounded-md border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-400 focus:border-zinc-500 focus:ring-zinc-500/20 font-mono text-xs resize-y"></textarea>
                <p class="mt-1.5 text-[11px] leading-snug text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.input.jobdesc_hint') }}</p>
            </div>

            <x-filament::button wire:click="analyzeJobMatch" color="primary" class="w-full" :loading="$isAnalyzing">
                <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                <span>{{ __('cv-aimatch.input.run_analysis') }}</span>
            </x-filament::button>
        </div>
    @else
        <div class="border-t border-zinc-200 dark:border-zinc-700 px-4 py-2.5 flex items-center justify-between gap-3">
            <span class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $targetRole }}</span>
            <button type="button" wire:click="toggleJdInput" class="text-xs font-medium text-zinc-700 dark:text-zinc-300 hover:underline shrink-0">
                {{ __('cv-aimatch.studio.edit_jd') }}
            </button>
        </div>
    @endif
</div>
