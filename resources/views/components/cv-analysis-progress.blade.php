@php
    $steps = [
        ['title' => __('cv-aimatch.progress.steps.profile.title'), 'desc' => __('cv-aimatch.progress.steps.profile.desc')],
        ['title' => __('cv-aimatch.progress.steps.requirements.title'), 'desc' => __('cv-aimatch.progress.steps.requirements.desc')],
        ['title' => __('cv-aimatch.progress.steps.evidence.title'), 'desc' => __('cv-aimatch.progress.steps.evidence.desc')],
        ['title' => __('cv-aimatch.progress.steps.scoring.title'), 'desc' => __('cv-aimatch.progress.steps.scoring.desc')],
    ];
@endphp

<div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4"
     x-data="{
         analyzing: $wire.entangle('isAnalyzing'),
         hasResult: !! $wire.analysisResult,
         step: 0,
         timer: null,
         total: {{ count($steps) }},
         init() {
             this.hasResult = !! $wire.analysisResult;
             this.step = this.hasResult ? this.total : 0;
             this.$watch('analyzing', (value) => {
                 if (value) { this.step = 0; this.start(); }
                 else { this.stop(); this.hasResult = !! $wire.analysisResult; this.step = this.hasResult ? this.total : 0; }
             });
         },
         start() {
             this.stop();
             this.timer = setInterval(() => { if (this.step < this.total - 1) this.step++; }, 1600);
         },
         stop() { if (this.timer) { clearInterval(this.timer); this.timer = null; } },
         isDone(index) { return this.analyzing ? index < this.step : this.hasResult; },
         isActive(index) { return this.analyzing && index === this.step; }
     }">
    <div class="flex items-center gap-2.5 mb-3">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
            <x-heroicon-o-sparkles class="w-4 h-4" />
        </span>
        <div>
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.progress.title') }}</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400" x-show="analyzing">{{ __('cv-aimatch.progress.hint_running') }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-400" x-show="! analyzing && hasResult">{{ __('cv-aimatch.progress.hint_done') }}</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-400" x-show="! analyzing && ! hasResult">{{ __('cv-aimatch.progress.hint_ready') }}</p>
        </div>
    </div>

    <div class="h-1 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden mb-4" x-show="analyzing">
        <div class="h-full rounded-full bg-zinc-700 dark:bg-zinc-300 transition-all duration-500"
             :style="'width: ' + Math.round(((step + 1) / total) * 100) + '%'"></div>
    </div>

    <ol class="space-y-3">
        @foreach($steps as $index => $item)
            <li class="flex items-start gap-2.5"
                :class="{ 'opacity-100': isDone({{ $index }}) || isActive({{ $index }}), 'opacity-50': ! isDone({{ $index }}) && ! isActive({{ $index }}) }">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border text-[10px] font-semibold shrink-0 mt-0.5 transition-colors"
                      :class="isDone({{ $index }})
                          ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                          : (isActive({{ $index }})
                              ? 'border-zinc-700 dark:border-zinc-300 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100'
                              : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-400')">
                    <template x-if="isDone({{ $index }})"><x-heroicon-s-check class="w-3 h-3" /></template>
                    <template x-if="! isDone({{ $index }})"><span>{{ $index + 1 }}</span></template>
                </span>
                <span class="flex flex-col gap-0.5 min-w-0">
                    <span class="text-xs font-medium text-zinc-800 dark:text-zinc-200">{{ $item['title'] }}</span>
                    <span class="text-[11px] leading-snug text-zinc-500 dark:text-zinc-400">{{ $item['desc'] }}</span>
                </span>
            </li>
        @endforeach
    </ol>
</div>
