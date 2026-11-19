@php
    $detail = is_array($analysisResult['job_match_detail'] ?? null) ? $analysisResult['job_match_detail'] : [];
    $readiness = is_numeric($analysisResult['ats_score'] ?? null) ? (int) $analysisResult['ats_score'] : null;
    $ats = $readiness;
    $coverage = is_numeric($detail['overall'] ?? null) ? (int) $detail['overall']
        : (is_numeric($analysisResult['job_match_score'] ?? null) ? (int) $analysisResult['job_match_score'] : null);
    $appliedCount = count(array_filter($actionableSuggestions ?? [], fn ($s) => ($s['state'] ?? '') === 'applied'));
    $totalCount = count($actionableSuggestions ?? []);
@endphp

<div class="grid grid-cols-2 lg:grid-cols-4 gap-px overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-200 dark:bg-zinc-700">
    @foreach([
        ['label' => __('cv-aimatch.studio.exec.readiness'), 'value' => $readiness],
        ['label' => __('cv-aimatch.studio.exec.ats'), 'value' => $ats],
        ['label' => __('cv-aimatch.studio.exec.coverage'), 'value' => $coverage],
    ] as $stat)
        <div class="bg-white dark:bg-zinc-900 px-4 py-3.5">
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-semibold tabular-nums {{ $stat['value'] !== null && $stat['value'] >= 70 ? 'text-emerald-600 dark:text-emerald-400' : ($stat['value'] !== null && $stat['value'] >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                    {{ $stat['value'] ?? '—' }}@if($stat['value'] !== null)<span class="text-sm">%</span>@endif
                </span>
            </div>
            <p class="mt-0.5 text-[11px] font-medium text-zinc-500 dark:text-zinc-400">{{ $stat['label'] }}</p>
        </div>
    @endforeach
    <div class="bg-white dark:bg-zinc-900 px-4 py-3.5">
        <div class="flex items-baseline gap-1">
            <span class="text-2xl font-semibold tabular-nums text-zinc-900 dark:text-zinc-100">{{ $appliedCount }}<span class="text-base text-zinc-400"> / {{ $totalCount }}</span></span>
        </div>
        <p class="mt-0.5 text-[11px] font-medium text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.exec.applied') }}</p>
    </div>
</div>
