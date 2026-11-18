            <div class="tab-pane-content space-y-8 animate-fade-in">
                
                {{-- Score Meters --}}
                @if($analysisResult)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-zinc-500 dark:stroke-zinc-400 fill-none transition-all duration-300" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * $analysisResult['ats_score']) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ $analysisResult['ats_score'] }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-500 dark:text-slate-400 font-bold uppercase">{{ __('cv-workspace.overview.scores.ats') }}</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-zinc-500 dark:stroke-zinc-400 fill-none transition-all duration-300" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * $analysisResult['format_score']) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ $analysisResult['format_score'] }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-500 dark:text-slate-400 font-bold uppercase">{{ __('cv-workspace.overview.scores.format') }}</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-zinc-500 dark:stroke-zinc-400 fill-none transition-all duration-300" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($analysisResult['about_me_score'] ?? 0)) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ $analysisResult['about_me_score'] ?? 0 }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-500 dark:text-slate-400 font-bold uppercase">{{ __('cv-workspace.overview.scores.about_me') }}</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-zinc-500 dark:stroke-zinc-400 fill-none transition-all duration-300" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($analysisResult['job_match_score'] ?? 0)) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ ($analysisResult['job_match_score'] ?? null) === null ? __('cv-workspace.overview.scores.not_available') : $analysisResult['job_match_score'].'%' }}</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-400 font-bold uppercase">{{ __('cv-workspace.overview.scores.job_match') }}</span>
                        </div>
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-chart-pie class="w-12 h-12 text-slate-500 mx-auto mb-3" />
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('cv-workspace.overview.empty.title') }}</h4>
                        <p class="text-sm text-slate-400 mt-1 max-w-md mx-auto">{{ __('cv-workspace.overview.empty.description') }}</p>
                        <x-filament::button wire:click="switchTab('profile')" color="primary" class="mt-4">
                            {{ __('cv-workspace.overview.empty.action') }}
                        </x-filament::button>
                    </div>
                @endif

                {{-- Visual Trend Charts --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass-card p-5">
                        <livewire:is :key="'cv-scores-trend'" :component="'App\\Filament\\Widgets\\CvScoresTrendChartWidget'" />
                    </div>
                    <div class="glass-card p-5">
                        <livewire:is :key="'cv-requirement-status'" :component="'App\\Filament\\Widgets\\CvRequirementStatusWidget'" />
                    </div>
                </div>

                {{-- History Table --}}
                @if($history && count(is_countable($history) ? $history : []) > 0)
                    <div class="glass-card p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <x-heroicon-o-clock class="w-5 h-5 text-cyan-400" />
                                <span>{{ __('cv-workspace.overview.history.title') }}</span>
                            </h3>
                            <button wire:click="switchTab('aimatch')" type="button" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-slate-600 rounded-lg text-xs font-semibold text-slate-200 transition-colors inline-flex items-center gap-1">
                                <x-heroicon-o-sparkles class="w-3.5 h-3.5" />
                                <span>{{ __('cv-workspace.overview.history.open_ai_match') }}</span>
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-300">
                                <thead class="text-xs uppercase text-slate-500 border-b border-[#2d2d3a]">
                                    <tr>
                                        <th class="py-3 px-4">{{ __('cv-workspace.overview.history.col_date') }}</th>
                                        <th class="py-3 px-4">{{ __('cv-workspace.overview.history.col_ats') }}</th>
                                        <th class="py-3 px-4">{{ __('cv-workspace.overview.history.col_format') }}</th>
                                        <th class="py-3 px-4">{{ __('cv-workspace.overview.history.col_about_me') }}</th>
                                        <th class="py-3 px-4">{{ __('cv-workspace.overview.history.col_job_match') }}</th>
                                        <th class="py-3 px-4">{{ __('cv-workspace.overview.history.col_target_role') }}</th>
                                        <th class="py-3 px-4 text-right">{{ __('cv-workspace.overview.history.col_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#1e1e2d]">
                                    @foreach($history as $item)
                                        <tr class="hover:bg-slate-800/20 group">
                                            <td class="py-3 px-4 text-slate-900 dark:text-white font-medium">{{ $item->created_at->translatedFormat('M j, Y H:i') }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2.5 py-0.5 rounded text-xs font-bold font-mono {{ $item->ats_score >= 80 ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30' : ($item->ats_score >= 50 ? 'bg-amber-950 text-amber-400 border border-amber-500/30' : 'bg-rose-950 text-rose-400 border border-rose-500/30') }}">
                                                    {{ $item->ats_score }}%
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="px-2.5 py-0.5 rounded text-xs font-bold font-mono {{ $item->format_score >= 80 ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30' : ($item->format_score >= 50 ? 'bg-amber-950 text-amber-400 border border-amber-500/30' : 'bg-rose-950 text-rose-400 border border-rose-500/30') }}">
                                                    {{ $item->format_score }}%
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($item->about_me_score !== null)
                                                    <span class="px-2.5 py-0.5 rounded text-xs font-bold font-mono {{ $item->about_me_score >= 80 ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30' : ($item->about_me_score >= 50 ? 'bg-amber-950 text-amber-400 border border-amber-500/30' : 'bg-rose-950 text-rose-400 border border-rose-500/30') }}">
                                                        {{ $item->about_me_score }}%
                                                    </span>
                                                @else
                                                    <span class="text-slate-500 font-mono">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($item->job_match_score !== null)
                                                    <span class="px-2.5 py-0.5 rounded text-xs font-bold font-mono {{ $item->job_match_score >= 80 ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30' : ($item->job_match_score >= 50 ? 'bg-amber-950 text-amber-400 border border-amber-500/30' : 'bg-rose-950 text-rose-400 border border-rose-500/30') }}">
                                                        {{ $item->job_match_score }}%
                                                    </span>
                                                @else
                                                    <span class="text-slate-500 font-mono">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-slate-400 max-w-[180px] truncate">{{ $item->job_title_targeted ?? '-' }}</td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button type="button" wire:click="loadAnalysisIntoWorkspace({{ $item->id }})" class="p-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded hover:text-white inline-flex" title="{{ __('cv-workspace.overview.history.load_insights') }}" aria-label="{{ __('cv-workspace.overview.history.load_insights') }}">
                                                        <x-heroicon-o-eye class="w-4 h-4" />
                                                    </button>
                                                    <button wire:click="deleteAnalysis({{ $item->id }})" wire:confirm="{{ __('cv-workspace.overview.history.confirm_delete') }}" class="p-1 bg-slate-800 hover:bg-rose-950 text-slate-400 rounded hover:text-rose-400 inline-flex" title="{{ __('cv-workspace.overview.history.delete_run') }}" aria-label="{{ __('cv-workspace.overview.history.delete_run') }}">
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
