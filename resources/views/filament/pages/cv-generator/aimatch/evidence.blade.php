@php
    $detail = is_array($analysisResult['job_match_detail'] ?? null) ? $analysisResult['job_match_detail'] : [];
    $requirements = is_array($detail['requirements'] ?? null) ? $detail['requirements'] : [];
    $matched = array_values(array_filter($requirements, fn ($r) => ($r['status'] ?? 'missing') !== 'missing'));
@endphp

<div class="space-y-3">
    <div>
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.studio.evidence.title') }}</h3>
        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.evidence.hint') }}</p>
    </div>

    @forelse($matched as $req)
        @php
            $verified = ($req['status'] ?? '') === 'verified';
        @endphp
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $req['requirement'] ?? '' }}</p>
                <span class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[10px] font-semibold border shrink-0
                    {{ $verified ? 'border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' : 'border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400' }}">
                    <x-heroicon-s-{{ $verified ? 'check-circle' : 'exclamation-triangle' }} class="w-3 h-3" />
                    {{ $verified ? __('cv-aimatch.studio.evidence.verified') : __('cv-aimatch.studio.evidence.claimed') }}
                </span>
            </div>
            @if(! empty($req['evidence_quote']))
                <blockquote class="mt-2 border-l-2 border-zinc-300 dark:border-zinc-600 pl-3 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400 italic">
                    {{ $req['evidence_quote'] }}
                </blockquote>
            @endif
            @if(! empty($req['reasoning']))
                <p class="mt-2 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $req['reasoning'] }}</p>
            @endif
        </div>
    @empty
        <div class="rounded-lg border border-dashed border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-6 py-10 text-center">
            <x-heroicon-o-magnifying-glass class="w-7 h-7 mx-auto text-zinc-300 dark:text-zinc-600" />
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.evidence.empty') }}</p>
        </div>
    @endforelse
</div>
