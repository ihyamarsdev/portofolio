            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5 text-cyan-400" />
                        <span>{{ __('cv-workspace.skills.title') }}</span>
                    </h3>
                    <x-filament::button wire:click="openSkillModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        {{ __('cv-workspace.skills.add') }}
                    </x-filament::button>
                </div>

                @if($skills && count(is_countable($skills) ? $skills : []) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($skills->groupBy('category_id') as $catId => $categorySkills)
                            @php
                                $categoryName = $categorySkills->first()?->category?->name ?? __('cv-workspace.skills.uncategorized');
                            @endphp
                            <div class="glass-card p-5 space-y-4">
                                <h4 class="text-md font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-[#2d2d3a]/50 pb-2">
                                    <span class="w-1.5 h-4 bg-cyan-400 rounded-sm"></span>
                                    <span>{{ $categoryName }}</span>
                                </h4>

                                <div class="space-y-3.5">
                                    @foreach($categorySkills as $skill)
                                        @php
                                            $pct = max(5, min(100, (int) round($skill->proficiency * 20)));
                                            $profText = match((int) $skill->proficiency) {
                                                1 => __('cv-workspace.skills.levels.beginner'),
                                                2 => __('cv-workspace.skills.levels.developing'),
                                                3 => __('cv-workspace.skills.levels.intermediate'),
                                                4 => __('cv-workspace.skills.levels.advanced'),
                                                5 => __('cv-workspace.skills.levels.expert'),
                                            };
                                        @endphp
                                        <div class="group relative pr-16">
                                            <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                                <span class="text-slate-200 group-hover:text-cyan-400 transition-colors">{{ $skill->name }}</span>
                                                <span class="text-slate-400 font-mono">{{ __('cv-workspace.skills.years_exp', ['years' => $skill->years_experience]) }} <span class="text-slate-600">|</span> {{ $profText }}</span>
                                            </div>
                                            <div class="w-full bg-[#1e1e2d] h-2 rounded-full overflow-hidden border border-[#2d2d3a]/40">
                                                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                            
                                            <div class="absolute right-0 top-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button wire:click="openSkillModal({{ $skill->id }})" class="p-1 bg-slate-800 text-slate-400 hover:text-white rounded" title="{{ __('cv-workspace.skills.edit') }}" aria-label="{{ __('cv-workspace.skills.edit') }}">
                                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                                </button>
                                                <button wire:click="deleteSkill({{ $skill->id }})" wire:confirm="{{ __('cv-workspace.skills.confirm_delete') }}" class="p-1 bg-slate-800 text-slate-400 hover:text-rose-400 rounded" title="{{ __('cv-workspace.skills.delete') }}" aria-label="{{ __('cv-workspace.skills.delete') }}">
                                                    <x-heroicon-o-trash class="w-3.5 h-3.5" />
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-bolt class="w-12 h-12 text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-400">{{ __('cv-workspace.skills.empty.description') }}</p>
                        <x-filament::button wire:click="openSkillModal()" color="primary" class="mt-4" size="sm">
                            {{ __('cv-workspace.skills.empty.action') }}
                        </x-filament::button>
                    </div>
                @endif
            </div>
