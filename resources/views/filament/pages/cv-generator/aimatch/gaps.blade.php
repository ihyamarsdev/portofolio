@php
    $detail = is_array($analysisResult['job_match_detail'] ?? null) ? $analysisResult['job_match_detail'] : [];
    $requirements = is_array($detail['requirements'] ?? null) ? $detail['requirements'] : [];
    $missing = array_values(array_filter($requirements, fn ($r) => ($r['status'] ?? '') === 'missing'));
    $missingKeywords = is_array($analysisResult['keywords']['missing'] ?? null) ? $analysisResult['keywords']['missing'] : [];
    $weakKeywords = is_array($analysisResult['keywords']['weak_evidence'] ?? null) ? $analysisResult['keywords']['weak_evidence'] : [];
    $criticalGaps = is_array($analysisResult['critical_gaps'] ?? null) ? $analysisResult['critical_gaps'] : [];
@endphp

<div class="space-y-3">
    <div>
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.studio.gaps.title') }}</h3>
        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.gaps.hint') }}</p>
    </div>

    <div class="rounded-md border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-2.5">
        <x-heroicon-o-information-circle class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
        <p class="text-xs leading-relaxed text-amber-800 dark:text-amber-300">{{ __('cv-aimatch.studio.gaps.hint') }}</p>
    </div>

    @if($missing !== [])
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 divide-y divide-zinc-100 dark:divide-zinc-800">
            @foreach($missing as $req)
                <div class="px-4 py-3 flex items-start gap-2.5">
                    <x-heroicon-o-x-circle class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $req['requirement'] ?? '' }}</p>
                        @if(! empty($req['reasoning']))
                            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $req['reasoning'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($missingKeywords !== [] || $weakKeywords !== [])
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @if($missingKeywords !== [])
                <div class="rounded-lg border border-rose-200 dark:border-rose-500/30 bg-rose-50 dark:bg-rose-500/10 p-4">
                    <h4 class="text-xs font-semibold text-rose-700 dark:text-rose-400 mb-2">{{ __('cv-aimatch.studio.gaps.missing_kw') }} ({{ count($missingKeywords) }})</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($missingKeywords as $kw)
                            <span class="inline-flex rounded px-1.5 py-0.5 text-[11px] font-medium border border-rose-200 dark:border-rose-500/30 bg-white dark:bg-rose-500/10 text-rose-700 dark:text-rose-400">{{ $kw }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($weakKeywords !== [])
                <div class="rounded-lg border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 p-4">
                    <h4 class="text-xs font-semibold text-amber-700 dark:text-amber-400 mb-2">{{ __('cv-aimatch.studio.gaps.weak_kw') }} ({{ count($weakKeywords) }})</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($weakKeywords as $kw)
                            @php $label = is_array($kw) ? ($kw['keyword'] ?? '') : (string) $kw; @endphp
                            @if(trim($label) !== '')
                                <span class="inline-flex rounded px-1.5 py-0.5 text-[11px] font-medium border border-amber-200 dark:border-amber-500/30 bg-white dark:bg-amber-500/10 text-amber-700 dark:text-amber-400">{{ $label }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if($criticalGaps !== [])
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <h4 class="text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">{{ __('cv-aimatch.strengths_gaps.critical_gaps') }}</h4>
            <ul class="space-y-1.5">
                @foreach($criticalGaps as $gap)
                    <li class="flex items-start gap-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                        <span class="text-rose-500 mt-1">•</span><span>{{ $gap }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($missing === [] && $missingKeywords === [] && $weakKeywords === [] && $criticalGaps === [])
        <div class="rounded-lg border border-dashed border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-6 py-10 text-center">
            <x-heroicon-o-check-badge class="w-7 h-7 mx-auto text-emerald-500" />
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.gaps.empty') }}</p>
        </div>
    @endif
</div>
