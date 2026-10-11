            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-academic-cap class="w-5 h-5 text-cyan-400" />
                        <span>{{ __('cv-workspace.education.title') }}</span>
                    </h3>
                    <x-filament::button wire:click="openEducationModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        {{ __('cv-workspace.education.add') }}
                    </x-filament::button>
                </div>

                @if($educations && count(is_countable($educations) ? $educations : []) > 0)
                    <div class="space-y-4 relative before:absolute before:left-6 before:top-4 before:bottom-4 before:w-[2px] before:bg-[#2d2d3a]">
                        @foreach($educations as $edu)
                            <div class="glass-card p-5 relative pl-14 hover:border-cyan-500/30 transition-all group">
                                <div class="absolute left-[18px] top-6 w-3 h-3 rounded-full bg-[#2d2d3a] border border-[#12121a] group-hover:bg-cyan-400 group-hover:shadow-[0_0_8px_#06b6d4] transition-all"></div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-cyan-400 transition-colors">{{ __('cv-workspace.education.degree_in', ['degree' => $edu->degree, 'field' => $edu->field_of_study]) }}</h4>
                                        <p class="text-sm font-semibold text-slate-300 mt-0.5">{{ $edu->institution }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-400 font-mono bg-slate-800/50 border border-slate-700 px-2 py-0.5 rounded">
                                            {{ $edu->start_date->format('Y') }} - {{ $edu->end_date ? $edu->end_date->format('Y') : __('cv-workspace.education.present') }}
                                        </span>
                                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="openEducationModal({{ $edu->id }})" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded hover:text-white" title="{{ __('cv-workspace.education.edit') }}" aria-label="{{ __('cv-workspace.education.edit') }}">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </button>
                                            <button wire:click="deleteEducation({{ $edu->id }})" wire:confirm="{{ __('cv-workspace.education.confirm_delete') }}" class="p-1.5 bg-slate-800 hover:bg-rose-950 text-slate-400 rounded hover:text-rose-400" title="{{ __('cv-workspace.education.delete') }}" aria-label="{{ __('cv-workspace.education.delete') }}">
                                                <x-heroicon-o-trash class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 text-sm text-slate-400 flex flex-col md:flex-row gap-4">
                                    @if($edu->grade)
                                        <div>
                                            <span class="font-bold text-slate-300">{{ __('cv-workspace.education.grade') }}</span>
                                            <span class="text-cyan-400 font-mono">{{ $edu->grade }}</span>
                                        </div>
                                    @endif
                                    @if($edu->activities)
                                        <div class="flex-1">
                                            <span class="font-bold text-slate-300 block mb-0.5">{{ __('cv-workspace.education.activities') }}</span>
                                            <p class="text-xs text-slate-400 leading-relaxed">{{ $edu->activities }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-academic-cap class="w-12 h-12 text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-400">{{ __('cv-workspace.education.empty.description') }}</p>
                        <x-filament::button wire:click="openEducationModal()" color="primary" class="mt-4" size="sm">
                            {{ __('cv-workspace.education.empty.action') }}
                        </x-filament::button>
                    </div>
                @endif
            </div>
