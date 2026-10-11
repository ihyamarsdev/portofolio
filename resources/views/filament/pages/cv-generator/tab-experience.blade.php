            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-400" />
                        <span>{{ __('cv-workspace.experience.title') }}</span>
                    </h3>
                    <x-filament::button wire:click="openExperienceModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        {{ __('cv-workspace.experience.add') }}
                    </x-filament::button>
                </div>

                @if($experiences && count(is_countable($experiences) ? $experiences : []) > 0)
                    <div class="space-y-4 relative before:absolute before:left-6 before:top-4 before:bottom-4 before:w-[2px] before:bg-[#2d2d3a]">
                        @foreach($experiences as $exp)
                            <div class="glass-card p-5 relative pl-14 hover:border-cyan-500/30 transition-all group transition-all duration-200"
                                 draggable="true"
                                 x-data="{ dragging: false }"
                                 x-on:dragstart="dragging = true; event.dataTransfer.setData('text/plain', {{ $exp->id }})"
                                 x-on:dragend="dragging = false"
                                 x-on:dragover.prevent
                                 x-on:drop="
                                     dragging = false;
                                     const dragId = event.dataTransfer.getData('text/plain');
                                     const dropId = {{ $exp->id }};
                                     if (dragId && dragId != dropId) {
                                         $wire.reorderExperiences(dragId, dropId);
                                     }
                                 "
                                 :class="{ 'opacity-40 border-dashed border-cyan-500/50 bg-cyan-950/10 shadow-inner scale-[0.98]': dragging }">
                                <div class="absolute left-[18px] top-6 w-3 h-3 rounded-full bg-[#2d2d3a] border border-[#12121a] group-hover:bg-cyan-400 group-hover:shadow-[0_0_8px_#06b6d4] transition-all"></div>
                                
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b border-[#2d2d3a]/50 pb-3 mb-3">
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-cyan-400 transition-colors">{{ $exp->position }}</h4>
                                        <p class="text-sm font-semibold text-slate-300 mt-0.5">{{ $exp->company }} <span class="text-slate-500">•</span> <span class="text-slate-400 text-xs font-normal font-mono">{{ $exp->employment_type }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-400 font-mono bg-slate-800/50 border border-slate-700 px-2 py-0.5 rounded">
                                            {{ $exp->start_date->translatedFormat('M Y') }} - {{ $exp->end_date ? $exp->end_date->translatedFormat('M Y') : __('cv-workspace.experience.present') }}
                                        </span>
                                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="p-1.5 text-slate-500 cursor-grab active:cursor-grabbing hover:text-cyan-400" title="{{ __('cv-workspace.experience.drag_hint') }}" aria-label="{{ __('cv-workspace.experience.drag_hint') }}">
                                                <x-heroicon-o-bars-3 class="w-4 h-4" />
                                            </div>
                                            <button wire:click="openExperienceModal({{ $exp->id }})" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded hover:text-white" title="{{ __('cv-workspace.experience.edit') }}" aria-label="{{ __('cv-workspace.experience.edit') }}">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </button>
                                            <button wire:click="deleteExperience({{ $exp->id }})" wire:confirm="{{ __('cv-workspace.experience.confirm_delete') }}" class="p-1.5 bg-slate-800 hover:bg-rose-950 text-slate-400 rounded hover:text-rose-400" title="{{ __('cv-workspace.experience.delete') }}" aria-label="{{ __('cv-workspace.experience.delete') }}">
                                                <x-heroicon-o-trash class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-sm text-slate-300 space-y-3 leading-relaxed">
                                    @if($exp->description)
                                        <div class="prose prose-invert prose-sm max-w-none">
                                            {!! Str::markdown($exp->description) !!}
                                        </div>
                                    @endif

                                    @if($exp->achievements && count(is_countable($exp->achievements) ? $exp->achievements : []) > 0)
                                        <ul class="list-disc pl-5 space-y-1 text-slate-300 text-xs">
                                            @foreach((is_array($exp->achievements) ? $exp->achievements : []) as $achievement)
                                                <li>{{ $achievement }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if($exp->skills && count(is_countable($exp->skills) ? $exp->skills : []) > 0)
                                        <div class="flex flex-wrap gap-1.5 pt-2">
                                            @foreach($exp->skills as $skill)
                                                <span class="text-[10px] font-mono font-semibold bg-cyan-950/30 text-cyan-400 border border-cyan-500/20 px-2 py-0.5 rounded">{{ $skill->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-briefcase class="w-12 h-12 text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-400">{{ __('cv-workspace.experience.empty.description') }}</p>
                        <x-filament::button wire:click="openExperienceModal()" color="primary" class="mt-4" size="sm">
                            {{ __('cv-workspace.experience.empty.action') }}
                        </x-filament::button>
                    </div>
                @endif
            </div>
