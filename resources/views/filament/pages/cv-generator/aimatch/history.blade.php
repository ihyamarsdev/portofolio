@if($history && count(is_countable($history) ? $history : []) > 0)
    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden">
        <div class="flex items-center gap-2 px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
            <x-heroicon-o-clock class="w-4 h-4 text-zinc-400" />
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.history.title') }}</h3>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.history.hint') }}</p>
            </div>
        </div>
        <div class="p-2 space-y-1">
            @foreach($history as $item)
                @php
                    $histRole = $item->job_title_targeted ?? __('cv-aimatch.history.default_title');
                    $histDate = $item->created_at->translatedFormat('d M Y, H:i');
                    $isActive = $currentAnalysis?->id === $item->id;
                @endphp
                <button type="button" wire:key="am-hist-{{ $item->id }}"
                        wire:click="loadAnalysisIntoWorkspace({{ $item->id }})"
                        aria-label="{{ __('cv-aimatch.history.load_aria', ['role' => $histRole, 'date' => $histDate]) }}"
                        class="w-full flex items-center justify-between gap-2 text-left rounded-md px-2.5 py-2 transition-colors {{ $isActive ? 'bg-zinc-100 dark:bg-zinc-800' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/50' }}">
                    <span class="min-w-0 flex flex-col gap-0.5">
                        <span class="text-xs font-medium text-zinc-800 dark:text-zinc-200 truncate">{{ $histRole }}</span>
                        <span class="text-[10px] font-mono text-zinc-500 dark:text-zinc-400">{{ $histDate }}</span>
                    </span>
                    @if($item->job_match_score !== null)
                        @php
                            $score = (int) $item->job_match_score;
                            $tone = $score >= 70 ? 'emerald' : ($score >= 50 ? 'amber' : 'rose');
                        @endphp
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold border shrink-0
                            {{ $tone === 'emerald' ? 'border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : ($tone === 'amber' ? 'border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400' : 'border-rose-200 dark:border-rose-500/30 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400') }}">
                            {{ $score }}%
                        </span>
                    @else
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-medium border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 shrink-0">
                            ATS {{ $item->ats_score }}%
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
@endif
