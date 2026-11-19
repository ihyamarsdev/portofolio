@php
    $metricKeys = [
        'ats_score', 'format_score', 'about_me_score', 'impact_score',
        'role_alignment_score', 'security_score', 'reliability_score', 'cloud_score',
    ];
    $detail = is_array($analysisResult['job_match_detail'] ?? null) ? $analysisResult['job_match_detail'] : [];
    $density = is_array($detail['density'] ?? null) ? $detail['density'] : null;
@endphp

<div class="space-y-3">
    <div>
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.studio.rubric.title') }}</h3>
        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.rubric.hint') }}</p>
    </div>

    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 divide-y divide-zinc-100 dark:divide-zinc-800">
        @foreach($metricKeys as $key)
            @php
                $value = is_numeric($analysisResult[$key] ?? null) ? (int) $analysisResult[$key] : null;
                $label = __("cv-aimatch.metrics.items.{$key}.label");
                $desc = __("cv-aimatch.metrics.items.{$key}.desc");
            @endphp
            <div class="px-4 py-3">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-zinc-800 dark:text-zinc-200">{{ $label }}</p>
                        <p class="text-[11px] text-zinc-500 dark:text-zinc-400">{{ $desc }}</p>
                    </div>
                    <span class="text-sm font-semibold tabular-nums shrink-0 {{ $value !== null && $value >= 70 ? 'text-emerald-600 dark:text-emerald-400' : ($value !== null && $value >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                        {{ $value ?? '—' }}@if($value !== null)<span class="text-xs">%</span>@endif
                    </span>
                </div>
                <div class="mt-2 h-1 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                    <div class="h-full rounded-full {{ $value !== null && $value >= 70 ? 'bg-emerald-500' : ($value !== null && $value >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                         style="width: {{ $value ?? 0 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    @if($density)
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between gap-3">
            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('cv-aimatch.exec.density') }}</span>
            <span class="text-xs text-zinc-500 dark:text-zinc-400">
                {{ $density['label'] ?? '' }} · {{ __('cv-aimatch.exec.words', ['count' => $density['words'] ?? 0]) }}
            </span>
        </div>
    @endif

    @if(! empty($analysisResult['summary']))
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <h4 class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('cv-workspace.modals.analysis_detail.summary_heading') }}</h4>
            <p class="text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $analysisResult['summary'] }}</p>
        </div>
    @endif
</div>
