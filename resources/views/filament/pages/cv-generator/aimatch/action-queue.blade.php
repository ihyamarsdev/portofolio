@php
    $queue = $actionableSuggestions ?? [];
    $applicable = array_values(array_filter($queue, fn ($s) => in_array($s['action_type'] ?? '', App\Services\ApplyCvSuggestionService::APPLICABLE_ACTIONS, true) && ($s['state'] ?? 'pending') !== 'applied'));
    $selectableIds = array_column($applicable, 'id');
@endphp

<div x-data="{
        selected: @js($selectedSuggestionIds ?? []),
        drafts: {},
        initDrafts() {
            @foreach($queue as $s)
                this.drafts['{{ $s['id'] }}'] = @js($s['suggested_text'] ?? '');
            @endforeach
        },
        toggle(id) {
            const i = this.selected.indexOf(id);
            if (i >= 0) this.selected.splice(i, 1); else this.selected.push(id);
        },
        applyOne(id) {
            $wire.applySuggestion(id, this.drafts[id] ?? '');
        },
        applyBulk() {
            $wire.applySelectedSuggestions(this.selected);
            this.selected = [];
        }
     }"
     x-init="initDrafts()"
     class="space-y-4">

    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('cv-aimatch.studio.queue.title') }}</h3>
            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.queue.hint') }}</p>
        </div>
        @if($selectableIds !== [])
            <label class="inline-flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400 cursor-pointer shrink-0">
                <input type="checkbox"
                       @change="$wire.set('selectedSuggestionIds', $event.target.checked ? @js($selectableIds) : []); selected = $event.target.checked ? @js($selectableIds) : []"
                       class="rounded border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 focus:ring-zinc-500/20">
                <span>{{ __('cv-aimatch.studio.queue.select_all') }}</span>
            </label>
        @endif
    </div>

    @forelse($queue as $s)
        @php
            $applied = ($s['state'] ?? '') === 'applied';
            $dismissed = ($s['state'] ?? '') === 'dismissed';
            $advisory = ($s['action_type'] ?? '') === 'advisory';
            $isSkill = ($s['action_type'] ?? '') === 'add_skill';
            $impact = $s['impact'] ?? 'medium';
            $impactClass = $impact === 'high'
                ? 'border-rose-200 dark:border-rose-500/30 bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400'
                : ($impact === 'low'
                    ? 'border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400'
                    : 'border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400');
        @endphp
        <div class="rounded-lg border bg-white dark:bg-zinc-900 transition-opacity {{ $applied || $dismissed ? 'border-zinc-200 dark:border-zinc-800 opacity-60' : 'border-zinc-200 dark:border-zinc-700' }}"
             wire:key="am-sug-{{ $s['id'] }}">
            {{-- Card header --}}
            <div class="flex items-start gap-3 px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
                @if(! $advisory && ! $applied)
                    <input type="checkbox"
                           :checked="selected.includes('{{ $s['id'] }}')"
                           @change="toggle('{{ $s['id'] }}')"
                           aria-label="{{ __('cv-aimatch.studio.queue.select') }}"
                           class="mt-0.5 rounded border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 focus:ring-zinc-500/20 shrink-0">
                @endif

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                            {{ __('cv-aimatch.studio.queue.action.'.($s['action_type'] ?? 'advisory')) }}
                        </span>
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold border {{ $impactClass }}">
                            {{ __('cv-aimatch.studio.queue.impact.'.$impact) }}
                        </span>
                        @if($applied)
                            <span class="inline-flex items-center gap-1 rounded px-1.5 py-0.5 text-[10px] font-semibold border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                <x-heroicon-s-check-circle class="w-3 h-3" /> {{ __('cv-aimatch.studio.queue.applied') }}
                            </span>
                        @elseif($dismissed)
                            <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-medium border border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400">
                                {{ __('cv-aimatch.studio.queue.dismissed') }}
                            </span>
                        @endif
                    </div>
                    @if(! empty($s['target_label']))
                        <p class="mt-1.5 text-xs font-medium text-zinc-800 dark:text-zinc-200">{{ $s['target_label'] }}</p>
                    @endif
                    @if(! empty($s['rationale']))
                        <p class="mt-1 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">
                            <span class="font-medium text-zinc-600 dark:text-zinc-300">{{ __('cv-aimatch.studio.queue.rationale') }}:</span>
                            {{ $s['rationale'] }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Diff body --}}
            <div class="px-4 py-3.5 grid grid-cols-1 md:grid-cols-2 gap-3">
                @if(! empty($s['before_text']))
                    <div class="rounded-md border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-3">
                        <span class="block text-[10px] font-semibold uppercase tracking-wide text-zinc-400 mb-1.5">{{ __('cv-aimatch.studio.queue.before') }}</span>
                        <p class="text-xs leading-relaxed text-zinc-600 dark:text-zinc-400 whitespace-pre-line break-words">{{ $s['before_text'] }}</p>
                    </div>
                @endif
                <div class="rounded-md border {{ $applied ? 'border-emerald-200 dark:border-emerald-500/30' : 'border-zinc-200 dark:border-zinc-700' }} bg-white dark:bg-zinc-900 p-3">
                    <label for="draft-{{ $s['id'] }}" class="block text-[10px] font-semibold uppercase tracking-wide text-zinc-400 mb-1.5">
                        {{ $applied ? __('cv-aimatch.studio.queue.applied') : __('cv-aimatch.studio.queue.after') }}
                    </label>
                    @if($applied)
                        <p class="text-xs leading-relaxed text-zinc-700 dark:text-zinc-300 whitespace-pre-line break-words">{{ $s['applied_content'] ?: ($s['suggested_text'] ?? '') }}</p>
                    @else
                        <textarea id="draft-{{ $s['id'] }}" x-model="drafts['{{ $s['id'] }}']" rows="{{ $isSkill ? 1 : 4 }}"
                                  aria-label="{{ __('cv-aimatch.studio.queue.inline_label') }}"
                                  class="w-full rounded border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-xs leading-relaxed text-zinc-800 dark:text-zinc-200 focus:border-zinc-500 focus:ring-zinc-500/20 resize-y">{{ $s['suggested_text'] ?? '' }}</textarea>
                    @endif
                </div>
            </div>

            {{-- Footer / actions --}}
            <div class="flex items-center justify-between gap-3 px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">
                @if($isSkill && ! $applied)
                    <p class="flex items-center gap-1.5 text-[11px] text-amber-700 dark:text-amber-400">
                        <x-heroicon-o-exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
                        {{ __('cv-aimatch.studio.queue.skill_note') }}
                    </p>
                @else
                    <span></span>
                @endif

                <div class="flex items-center gap-2 shrink-0">
                    @if($applied)
                        <button type="button" wire:click="undoSuggestion('{{ $s['id'] }}')"
                                class="inline-flex items-center gap-1 rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2.5 py-1.5 text-xs font-medium text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                            <x-heroicon-o-arrow-uturn-left class="w-3.5 h-3.5" />
                            {{ __('cv-aimatch.studio.queue.undo') }}
                        </button>
                    @else
                        @if(! $advisory)
                            <button type="button" wire:click="dismissSuggestion('{{ $s['id'] }}')"
                                    class="inline-flex items-center gap-1 rounded-md px-2 py-1.5 text-xs font-medium text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200 transition-colors">
                                {{ __('cv-aimatch.studio.queue.dismiss') }}
                            </button>
                            <button type="button" @click="applyOne('{{ $s['id'] }}')"
                                    class="inline-flex items-center gap-1.5 rounded-md bg-zinc-800 dark:bg-zinc-200 px-3 py-1.5 text-xs font-semibold text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-white transition-colors">
                                <x-heroicon-o-check class="w-3.5 h-3.5" />
                                {{ __('cv-aimatch.studio.queue.apply') }}
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-lg border border-dashed border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-6 py-10 text-center">
            <x-heroicon-o-inbox class="w-7 h-7 mx-auto text-zinc-300 dark:text-zinc-600" />
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">{{ __('cv-aimatch.studio.queue.empty') }}</p>
        </div>
    @endforelse

    {{-- Sticky bulk bar --}}
    <div x-show="selected.length > 0" x-cloak
         class="sticky bottom-4 z-10 flex items-center justify-between gap-3 rounded-lg border border-zinc-800 dark:border-zinc-600 bg-zinc-900 dark:bg-zinc-100 px-4 py-3 shadow-lg">
        <span class="text-xs font-medium text-zinc-200 dark:text-zinc-800"
              x-text="'{{ __('cv-aimatch.studio.queue.bulk_bar') }}'.replace(':count', selected.length)"></span>
        <div class="flex items-center gap-2">
            <button type="button" wire:click="viewUpdatedPreview"
                    class="inline-flex items-center gap-1.5 rounded-md border border-zinc-600 dark:border-zinc-400 px-3 py-1.5 text-xs font-medium text-zinc-200 dark:text-zinc-800 hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors">
                <x-heroicon-o-eye class="w-3.5 h-3.5" />
                {{ __('cv-aimatch.studio.queue.view_preview') }}
            </button>
            <button type="button" @click="applyBulk()"
                    class="inline-flex items-center gap-1.5 rounded-md bg-white dark:bg-zinc-900 px-3 py-1.5 text-xs font-semibold text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                <x-heroicon-o-check class="w-3.5 h-3.5" />
                <span x-text="'{{ __('cv-aimatch.studio.queue.bulk_apply') }}'.replace(':count', selected.length)"></span>
            </button>
        </div>
    </div>
</div>
