        <script>
            if (!window.__lastActiveElementTracker) {
                window.__lastActiveElementTracker = true;
                window.__lastActiveOpener = null;
                window.__lastActiveOpenerSelector = null;
                document.addEventListener('focusin', (e) => {
                    const target = e.target;
                    if (!target.closest('[role="dialog"]')) {
                        window.__lastActiveOpener = target;
                        if (target.id) {
                            window.__lastActiveOpenerSelector = '#' + CSS.escape(target.id);
                        } else if (target.getAttribute && target.getAttribute('wire:click')) {
                            window.__lastActiveOpenerSelector = `[wire\\:click="${target.getAttribute('wire:click')}"]`;
                        }
                    }
                }, true);
                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('button, [role="button"], a');
                    if (btn && !btn.closest('[role="dialog"]')) {
                        window.__lastActiveOpener = btn;
                        if (btn.id) {
                            window.__lastActiveOpenerSelector = '#' + CSS.escape(btn.id);
                        } else if (btn.getAttribute('wire:click')) {
                            window.__lastActiveOpenerSelector = `[wire\\:click="${btn.getAttribute('wire:click')}"]`;
                        }
                    }
                }, true);
            }
        </script>
        {{-- Experience Modal --}}
        <div x-data="{
                open: @entangle('showExperienceModal').live,
                opener: null,
                openerSelector: null,
                close() {
                    this.open = false;
                    Alpine.nextTick(() => {
                        const target = (this.opener && document.body.contains(this.opener))
                            ? this.opener
                            : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                        target?.focus();
                    });
                }
            }"
            x-init="
                $watch('open', (isOpen) => {
                    if (isOpen) {
                        opener = window.__lastActiveOpener || document.activeElement;
                        openerSelector = window.__lastActiveOpenerSelector;
                    } else {
                        Alpine.nextTick(() => {
                            const target = (this.opener && document.body.contains(this.opener))
                                ? this.opener
                                : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                            target?.focus();
                        });
                    }
                });
            "
            @keydown.escape.window="if (open) { close(); }"
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-cloak
            style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="close()"></div>
            
            <div role="dialog"
                 aria-modal="true"
                 aria-labelledby="experience-modal-title"
                 x-trap.noscroll="open"
                 class="glass-card relative w-full max-w-2xl bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 id="experience-modal-title" class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingExperienceId ? __('cv-workspace.modals.experience.title_edit') : __('cv-workspace.modals.experience.title_add') }}</span>
                    </h3>
                    <button type="button" @click="close()" aria-label="{{ __('cv-workspace.modals.experience.close_dialog') }}" class="text-slate-400 hover:text-white transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.company') }}</label>
                            <input type="text" wire:model.defer="exp_company" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.experience.company_placeholder') }}" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.position') }}</label>
                            <input type="text" wire:model.defer="exp_position" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.experience.position_placeholder') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.employment_type') }}</label>
                            <select wire:model.defer="exp_employment_type" class="cyber-select w-full">
                                <option value="Full-time">{{ __('cv-workspace.modals.experience.types.full_time') }}</option>
                                <option value="Part-time">{{ __('cv-workspace.modals.experience.types.part_time') }}</option>
                                <option value="Contract">{{ __('cv-workspace.modals.experience.types.contract') }}</option>
                                <option value="Freelance">{{ __('cv-workspace.modals.experience.types.freelance') }}</option>
                                <option value="Internship">{{ __('cv-workspace.modals.experience.types.internship') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.location') }}</label>
                            <input type="text" wire:model.defer="exp_location" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.experience.location_placeholder') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.start_date') }}</label>
                            <input type="date" wire:model.defer="exp_start_date" class="cyber-input w-full font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.end_date') }}</label>
                            <input type="date" wire:model.defer="exp_end_date" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div class="border-t border-[#2d2d3a]/50 pt-4">
                        <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-300 cursor-pointer">
                            <input type="checkbox" wire:model.defer="exp_is_featured" class="cyber-checkbox" />
                            {{ __('cv-workspace.modals.experience.is_featured') }}
                        </label>
                        <p class="mt-1 text-[11px] text-slate-500">{{ __('cv-workspace.modals.experience.is_featured_hint') }}</p>
                    </div>


                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.experience.description') }}</label>
                        <textarea wire:model.defer="exp_description" rows="4" class="cyber-textarea w-full" placeholder="{{ __('cv-workspace.modals.experience.description_placeholder') }}"></textarea>
                    </div>

                    {{-- Achievements Repeater --}}
                    <div class="space-y-2 border-t border-[#2d2d3a]/50 pt-4">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ __('cv-workspace.modals.experience.achievements_title') }}</label>
                        
                        <div class="flex gap-2">
                            <input type="text" wire:model.defer="new_achievement" wire:keydown.enter.prevent="addAchievement" class="cyber-input flex-1 text-xs" placeholder="{{ __('cv-workspace.modals.experience.achievement_placeholder') }}" />
                            <button type="button" wire:click="addAchievement" class="px-3 bg-cyan-950 border border-cyan-500/30 text-cyan-400 rounded-lg text-xs font-bold hover:bg-cyan-900 transition-colors">
                                {{ __('cv-workspace.modals.experience.achievement_add') }}
                            </button>
                        </div>

                        @if($exp_achievements && count(is_countable($exp_achievements) ? $exp_achievements : []) > 0)
                            <ul class="space-y-1.5 pt-2">
                                @foreach((is_array($exp_achievements) ? $exp_achievements : []) as $idx => $ach)
                                    <li class="bg-[#1a1a24] border border-[#2d2d3a] px-3 py-1.5 rounded-lg flex items-center justify-between text-xs text-slate-300">
                                        <span class="flex-1 leading-relaxed">{{ $ach }}</span>
                                        <button type="button" wire:click="removeAchievement({{ $idx }})" aria-label="{{ __('cv-workspace.modals.experience.remove_achievement') }}" class="text-slate-500 hover:text-rose-400 ml-2">
                                            <x-heroicon-o-x-mark class="w-4.5 h-4.5" />
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Associated Skills --}}
                    <div class="space-y-2 border-t border-[#2d2d3a]/50 pt-4">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ __('cv-workspace.modals.experience.skills_title') }}</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-[150px] overflow-y-auto border border-[#2d2d3a] p-3 rounded-lg bg-[#0e0e13]/60">
                            @foreach($all_skills_list as $sk)
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300 hover:text-white">
                                    <input type="checkbox" wire:model.defer="selected_exp_skills" value="{{ $sk->id }}" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/20" />
                                    <span>{{ $sk->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button type="button" @click="close()" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        {{ __('cv-workspace.modals.experience.cancel') }}
                    </button>
                    <button wire:click="saveExperience" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        {{ __('cv-workspace.modals.experience.save') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Education Modal --}}
        <div x-data="{
                open: @entangle('showEducationModal').live,
                opener: null,
                openerSelector: null,
                close() {
                    this.open = false;
                    Alpine.nextTick(() => {
                        const target = (this.opener && document.body.contains(this.opener))
                            ? this.opener
                            : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                        target?.focus();
                    });
                }
            }"
            x-init="
                $watch('open', (isOpen) => {
                    if (isOpen) {
                        opener = window.__lastActiveOpener || document.activeElement;
                        openerSelector = window.__lastActiveOpenerSelector;
                    } else {
                        Alpine.nextTick(() => {
                            const target = (this.opener && document.body.contains(this.opener))
                                ? this.opener
                                : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                            target?.focus();
                        });
                    }
                });
            "
            @keydown.escape.window="if (open) { close(); }"
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-cloak
            style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="close()"></div>
            
            <div role="dialog"
                 aria-modal="true"
                 aria-labelledby="education-modal-title"
                 x-trap.noscroll="open"
                 class="glass-card relative w-full max-w-xl bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 id="education-modal-title" class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-academic-cap class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingEducationId ? __('cv-workspace.modals.education.title_edit') : __('cv-workspace.modals.education.title_add') }}</span>
                    </h3>
                    <button type="button" @click="close()" aria-label="{{ __('cv-workspace.modals.education.close_dialog') }}" class="text-slate-400 hover:text-white transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.institution') }}</label>
                        <input type="text" wire:model.defer="edu_institution" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.education.institution_placeholder') }}" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.degree') }}</label>
                            <input type="text" wire:model.defer="edu_degree" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.education.degree_placeholder') }}" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.field') }}</label>
                            <input type="text" wire:model.defer="edu_field_of_study" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.education.field_placeholder') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.start_date') }}</label>
                            <input type="date" wire:model.defer="edu_start_date" class="cyber-input w-full font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.end_date') }}</label>
                            <input type="date" wire:model.defer="edu_end_date" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.grade') }}</label>
                            <input type="text" wire:model.defer="edu_grade" class="cyber-input w-full font-mono" placeholder="{{ __('cv-workspace.modals.education.grade_placeholder') }}" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.order') }}</label>
                            <input type="number" wire:model.defer="edu_order" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.education.activities') }}</label>
                        <textarea wire:model.defer="edu_activities" rows="3" class="cyber-textarea w-full text-xs" placeholder="{{ __('cv-workspace.modals.education.activities_placeholder') }}"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button type="button" @click="close()" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        {{ __('cv-workspace.modals.education.cancel') }}
                    </button>
                    <button wire:click="saveEducation" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        {{ __('cv-workspace.modals.education.save') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Skill Modal --}}
        <div x-data="{
                open: @entangle('showSkillModal').live,
                opener: null,
                openerSelector: null,
                close() {
                    this.open = false;
                    Alpine.nextTick(() => {
                        const target = (this.opener && document.body.contains(this.opener))
                            ? this.opener
                            : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                        target?.focus();
                    });
                }
            }"
            x-init="
                $watch('open', (isOpen) => {
                    if (isOpen) {
                        opener = window.__lastActiveOpener || document.activeElement;
                        openerSelector = window.__lastActiveOpenerSelector;
                    } else {
                        Alpine.nextTick(() => {
                            const target = (this.opener && document.body.contains(this.opener))
                                ? this.opener
                                : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                            target?.focus();
                        });
                    }
                });
            "
            @keydown.escape.window="if (open) { close(); }"
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-cloak
            style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="close()"></div>
            
            <div role="dialog"
                 aria-modal="true"
                 aria-labelledby="skill-modal-title"
                 x-trap.noscroll="open"
                 class="glass-card relative w-full max-w-md bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 id="skill-modal-title" class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingSkillId ? __('cv-workspace.modals.skill.title_edit') : __('cv-workspace.modals.skill.title_add') }}</span>
                    </h3>
                    <button type="button" @click="close()" aria-label="{{ __('cv-workspace.modals.skill.close_dialog') }}" class="text-slate-400 hover:text-white transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.skill.name') }}</label>
                        <input type="text" wire:model.defer="skill_name" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.skill.name_placeholder') }}" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.skill.category') }}</label>
                        <select wire:model.defer="skill_category_id" class="cyber-select w-full">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        @php
                            $profModalText = match((int) $skill_proficiency) {
                                1 => __('cv-workspace.modals.skill.levels.1'),
                                2 => __('cv-workspace.modals.skill.levels.2'),
                                3 => __('cv-workspace.modals.skill.levels.3'),
                                4 => __('cv-workspace.modals.skill.levels.4'),
                                5 => __('cv-workspace.modals.skill.levels.5'),
                                default => (string) $skill_proficiency,
                            };
                        @endphp
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.skill.proficiency_label', ['level' => '']) }}<span class="text-cyan-400 font-mono font-bold">{{ $profModalText }}</span></label>
                        <input type="range" wire:model.live="skill_proficiency" min="1" max="5" step="1" class="w-full accent-cyan-500 cursor-pointer h-2 bg-[#1e1e2d] border border-[#2d2d3a] rounded-full" />
                        <div class="flex justify-between text-[10px] text-slate-500 font-mono mt-1 uppercase font-bold">
                            <span>{{ __('cv-workspace.modals.skill.scale_min') }}</span>
                            <span>{{ __('cv-workspace.modals.skill.scale_mid') }}</span>
                            <span>{{ __('cv-workspace.modals.skill.scale_max') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.skill.years') }}</label>
                        <input type="number" step="0.5" wire:model.defer="skill_years_experience" class="cyber-input w-full font-mono" placeholder="{{ __('cv-workspace.modals.skill.years_placeholder') }}" />
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button type="button" @click="close()" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        {{ __('cv-workspace.modals.skill.cancel') }}
                    </button>
                    <button wire:click="saveSkill" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        {{ __('cv-workspace.modals.skill.save') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Certificate Modal --}}
        <div x-data="{
                open: @entangle('showCertificateModal').live,
                opener: null,
                openerSelector: null,
                close() {
                    this.open = false;
                    Alpine.nextTick(() => {
                        const target = (this.opener && document.body.contains(this.opener))
                            ? this.opener
                            : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                        target?.focus();
                    });
                }
            }"
            x-init="
                $watch('open', (isOpen) => {
                    if (isOpen) {
                        opener = window.__lastActiveOpener || document.activeElement;
                        openerSelector = window.__lastActiveOpenerSelector;
                    } else {
                        Alpine.nextTick(() => {
                            const target = (this.opener && document.body.contains(this.opener))
                                ? this.opener
                                : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                            target?.focus();
                        });
                    }
                });
            "
            @keydown.escape.window="if (open) { close(); }"
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-cloak
            style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="close()"></div>
            
            <div role="dialog"
                 aria-modal="true"
                 aria-labelledby="certificate-modal-title"
                 x-trap.noscroll="open"
                 class="glass-card relative w-full max-w-lg bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 id="certificate-modal-title" class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-trophy class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingCertificateId ? __('cv-workspace.modals.certificate.title_edit') : __('cv-workspace.modals.certificate.title_add') }}</span>
                    </h3>
                    <button type="button" @click="close()" aria-label="{{ __('cv-workspace.modals.certificate.close_dialog') }}" class="text-slate-400 hover:text-white transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.certificate.title') }}</label>
                        <input type="text" wire:model.defer="cert_name" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.certificate.title_placeholder') }}" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.certificate.issuer') }}</label>
                        <input type="text" wire:model.defer="cert_issuer" class="cyber-input w-full" placeholder="{{ __('cv-workspace.modals.certificate.issuer_placeholder') }}" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.certificate.credential_id') }}</label>
                            <input type="text" wire:model.defer="cert_credential_id" class="cyber-input w-full font-mono text-xs" placeholder="{{ __('cv-workspace.modals.certificate.credential_id_placeholder') }}" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.certificate.credential_url') }}</label>
                            <input type="text" wire:model.defer="cert_credential_url" class="cyber-input w-full text-xs" placeholder="{{ __('cv-workspace.modals.certificate.credential_url_placeholder') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.certificate.issue_date') }}</label>
                            <input type="date" wire:model.defer="cert_issue_date" class="cyber-input w-full font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">{{ __('cv-workspace.modals.certificate.expiry_date') }}</label>
                            <input type="date" wire:model.defer="cert_expiry_date" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                            <input type="checkbox" wire:model.defer="cert_is_active" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/20" />
                            <span>{{ __('cv-workspace.modals.certificate.active_status') }}</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button type="button" @click="close()" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        {{ __('cv-workspace.modals.certificate.cancel') }}
                    </button>
                    <button wire:click="saveCertificate" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        {{ __('cv-workspace.modals.certificate.save') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Detailed Analysis Modal --}}
        <div x-data="{
                open: @entangle('showAnalysisDetailModal').live,
                opener: null,
                openerSelector: null,
                close() {
                    this.open = false;
                    Alpine.nextTick(() => {
                        const target = (this.opener && document.body.contains(this.opener))
                            ? this.opener
                            : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                        target?.focus();
                    });
                }
            }"
            x-init="
                $watch('open', (isOpen) => {
                    if (isOpen) {
                        opener = window.__lastActiveOpener || document.activeElement;
                        openerSelector = window.__lastActiveOpenerSelector;
                    } else {
                        Alpine.nextTick(() => {
                            const target = (this.opener && document.body.contains(this.opener))
                                ? this.opener
                                : (this.openerSelector ? document.querySelector(this.openerSelector) : null);
                            target?.focus();
                        });
                    }
                });
            "
            @keydown.escape.window="if (open) { close(); }"
            x-show="open"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-cloak
            style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="close()"></div>
            
            <div role="dialog"
                 aria-modal="true"
                 aria-labelledby="analysis-detail-modal-title"
                 x-trap.noscroll="open"
                 class="glass-card relative w-full max-w-4xl bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 id="analysis-detail-modal-title" class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-document-text class="w-5 h-5 text-cyan-400" />
                        <span>{{ __('cv-workspace.modals.analysis_detail.title') }}</span>
                    </h3>
                    <button type="button" @click="close()" aria-label="{{ __('cv-workspace.modals.analysis_detail.close_report') }}" class="text-slate-400 hover:text-white transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                @if($selectedAnalysisDetail)
                    <div class="p-6 overflow-y-auto space-y-6 flex-1 font-space-grotesk text-slate-300">
                        {{-- Meta Details --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#1a1a24]/40 border border-[#2d2d3a]/60 p-4 rounded-xl">
                            <div>
                                <span class="text-xs uppercase text-slate-500 font-semibold">{{ __('cv-workspace.modals.analysis_detail.target_role') }}</span>
                                <h4 class="text-lg font-bold text-white mt-0.5">{{ $selectedAnalysisDetail['job_title_targeted'] }}</h4>
                            </div>
                            <div>
                                <span class="text-xs uppercase text-slate-500 font-semibold">{{ __('cv-workspace.modals.analysis_detail.run_date') }}</span>
                                <p class="text-sm font-mono font-medium text-slate-300 mt-0.5">{{ $selectedAnalysisDetail['date'] }}</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase text-slate-500 font-semibold">{{ __('cv-workspace.modals.analysis_detail.profile_checked') }}</span>
                                <p class="text-sm font-semibold text-cyan-400 mt-0.5">{{ $selectedAnalysisDetail['profile_name'] }}</p>
                            </div>
                        </div>

                        {{-- Scoring Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                            @php
                                $detailScoreCards = [
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.ats'), 'value' => $selectedAnalysisDetail['ats_score'] ?? '-', 'color' => 'text-cyan-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.format'), 'value' => $selectedAnalysisDetail['format_score'] ?? '-', 'color' => 'text-blue-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.about_me'), 'value' => $selectedAnalysisDetail['about_me_score'] ?? '-', 'color' => 'text-purple-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.impact'), 'value' => $selectedAnalysisDetail['impact_score'] ?? '-', 'color' => 'text-emerald-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.role_alignment'), 'value' => $selectedAnalysisDetail['role_alignment_score'] ?? '-', 'color' => 'text-fuchsia-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.security'), 'value' => $selectedAnalysisDetail['security_score'] ?? '-', 'color' => 'text-rose-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.reliability'), 'value' => $selectedAnalysisDetail['reliability_score'] ?? '-', 'color' => 'text-orange-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.cloud'), 'value' => $selectedAnalysisDetail['cloud_score'] ?? '-', 'color' => 'text-sky-400'],
                                    ['label' => __('cv-workspace.modals.analysis_detail.scores.job_match'), 'value' => $selectedAnalysisDetail['job_match_score'] ?? '-', 'color' => 'text-amber-400'],
                                ];
                            @endphp

                            @foreach($detailScoreCards as $card)
                                <div class="bg-[#181824]/60 border border-[#2d2d3a] p-4 rounded-xl text-center">
                                    <div class="text-2xl font-bold font-mono {{ $card['color'] }} mb-1">{{ $card['value'] }}%</div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">{{ $card['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if(!empty($selectedAnalysisDetail['target_seniority']))
                            <div class="inline-flex bg-fuchsia-950/20 border border-fuchsia-500/20 px-3 py-1 rounded-full text-xs font-semibold text-fuchsia-300">
                                {{ __('cv-workspace.modals.analysis_detail.seniority', ['level' => $selectedAnalysisDetail['target_seniority']]) }}
                            </div>
                        @endif

                        {{-- Executive Summary --}}
                        @if($selectedAnalysisDetail['summary'])
                            <div class="space-y-2">
                                <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                    <x-heroicon-o-information-circle class="w-4.5 h-4.5 text-cyan-400" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.summary_heading') }}</span>
                                </h4>
                                <div class="p-4 bg-[#1a1a24]/30 border border-[#2d2d3a] rounded-xl text-sm leading-relaxed text-slate-300">
                                    {{ $selectedAnalysisDetail['summary'] }}
                                </div>
                            </div>
                        @endif

                        {{-- Keywords Audit --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Found Keywords --}}
                            <div class="space-y-2">
                                <h4 class="text-sm font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <x-heroicon-o-check-circle class="w-4.5 h-4.5" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.keywords_found', ['count' => count(is_countable($selectedAnalysisDetail['keywords']['found'] ?? null) ? $selectedAnalysisDetail['keywords']['found'] : [])]) }}</span>
                                </h4>
                                <div class="p-4 bg-emerald-950/10 border border-emerald-900/30 rounded-xl min-h-[120px] max-h-[220px] overflow-y-auto">
                                    @if(!empty($selectedAnalysisDetail['keywords']['found']) && is_array($selectedAnalysisDetail['keywords']['found']))
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($selectedAnalysisDetail['keywords']['found'] as $kw)
                                                <span class="px-2 py-1 bg-emerald-950/40 text-emerald-400 border border-emerald-500/20 text-xs font-semibold rounded font-mono">
                                                    {{ $kw }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-500 text-xs italic">{{ __('cv-workspace.modals.analysis_detail.keywords_found_empty') }}</span>
                                    @endif
                                </div>
                             </div>

                             {{-- Weak Evidence --}}
                             <div class="space-y-2">
                                 <h4 class="text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-exclamation-triangle class="w-4.5 h-4.5" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.weak_evidence', ['count' => count(is_countable($selectedAnalysisDetail['keywords']['weak_evidence'] ?? null) ? $selectedAnalysisDetail['keywords']['weak_evidence'] : [])]) }}</span>
                                 </h4>
                                 <div class="p-4 bg-amber-950/10 border border-amber-900/30 rounded-xl min-h-[120px] max-h-[220px] overflow-y-auto">
                                     @if(!empty($selectedAnalysisDetail['keywords']['weak_evidence']) && is_array($selectedAnalysisDetail['keywords']['weak_evidence']))
                                         <div class="flex flex-wrap gap-1.5">
                                             @foreach($selectedAnalysisDetail['keywords']['weak_evidence'] as $kw)
                                                 @php
                                                     $weakLabel = is_array($kw)
                                                         ? (string) ($kw['keyword'] ?? $kw['reason'] ?? '')
                                                         : (string) $kw;
                                                     $weakReason = is_array($kw) ? (string) ($kw['reason'] ?? '') : '';
                                                 @endphp
                                                 @if(trim($weakLabel) !== '')
                                                     <span class="px-2 py-1 bg-amber-950/40 text-amber-300 border border-amber-500/20 text-xs font-semibold rounded font-mono" @if(trim($weakReason) !== '') title="{{ $weakReason }}" @endif>{{ $weakLabel }}</span>
                                                 @endif
                                             @endforeach
                                         </div>
                                     @else
                                        <span class="text-slate-500 text-xs italic">{{ __('cv-workspace.modals.analysis_detail.weak_evidence_empty') }}</span>
                                     @endif
                                 </div>
                             </div>

                             {{-- Missing Keywords --}}
                             <div class="space-y-2">
                                 <h4 class="text-sm font-bold text-rose-400 uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-x-circle class="w-4.5 h-4.5" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.missing_keywords', ['count' => count(is_countable($selectedAnalysisDetail['keywords']['missing'] ?? null) ? $selectedAnalysisDetail['keywords']['missing'] : [])]) }}</span>
                                 </h4>
                                 <div class="p-4 bg-rose-950/10 border border-rose-900/30 rounded-xl min-h-[120px] max-h-[220px] overflow-y-auto">
                                     @if(!empty($selectedAnalysisDetail['keywords']['missing']) && is_array($selectedAnalysisDetail['keywords']['missing']))
                                         <div class="flex flex-wrap gap-1.5">
                                             @foreach($selectedAnalysisDetail['keywords']['missing'] as $kw)
                                                 <span class="px-2 py-1 bg-rose-950/40 text-rose-400 border border-rose-500/20 text-xs font-semibold rounded font-mono">
                                                     {{ $kw }}
                                                 </span>
                                             @endforeach
                                         </div>
                                     @else
                                         <span class="text-emerald-400 text-xs font-semibold italic flex items-center gap-1">
                                             <x-heroicon-o-sparkles class="w-3.5 h-3.5" />
                                            <span>{{ __('cv-workspace.modals.analysis_detail.missing_keywords_flawless') }}</span>
                                         </span>
                                     @endif
                                 </div>
                             </div>
                         </div>

                         {{-- AI Action Recommendations --}}
                         @if(!empty($selectedAnalysisDetail['suggestions']) && is_array($selectedAnalysisDetail['suggestions']))
                             <div class="space-y-3">
                                 <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-clipboard-document-check class="w-4.5 h-4.5 text-cyan-400" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.action_checklist') }}</span>
                                 </h4>
                                 <div class="space-y-2.5 max-h-[300px] overflow-y-auto border border-[#2d2d3a] rounded-xl p-4 bg-[#0e0e13]/60">
                                     @foreach($selectedAnalysisDetail['suggestions'] as $idx => $sug)
                                         <div class="p-3 bg-[#181824]/40 border border-[#2d2d3a]/50 rounded-lg flex items-start gap-3 hover:border-cyan-500/30 transition-all duration-200">
                                             <span class="w-5 h-5 rounded-full bg-cyan-950 border border-cyan-500/40 text-[10px] text-cyan-400 font-bold flex items-center justify-center shrink-0 mt-0.5 font-mono">
                                                 {{ $idx + 1 }}
                                             </span>
                                             <p class="text-xs text-slate-300 leading-relaxed font-semibold">{{ $sug }}</p>
                                         </div>
                                     @endforeach
                                 </div>
                             </div>
                         @endif

                         <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                             @if(!empty($selectedAnalysisDetail['strengths']) && is_array($selectedAnalysisDetail['strengths']))
                                 <div class="p-4 bg-emerald-950/10 border border-emerald-900/30 rounded-xl space-y-2">
                                    <h4 class="text-sm font-bold text-emerald-300">{{ __('cv-workspace.modals.analysis_detail.strengths') }}</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['strengths'] as $strength)
                                             <li class="flex gap-2"><span class="text-emerald-400">•</span><span>{{ $strength }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif

                             @if(!empty($selectedAnalysisDetail['critical_gaps']) && is_array($selectedAnalysisDetail['critical_gaps']))
                                 <div class="p-4 bg-rose-950/10 border border-rose-900/30 rounded-xl space-y-2">
                                    <h4 class="text-sm font-bold text-rose-300">{{ __('cv-workspace.modals.analysis_detail.critical_gaps') }}</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['critical_gaps'] as $gap)
                                             <li class="flex gap-2"><span class="text-rose-400">•</span><span>{{ $gap }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif

                             @if(!empty($selectedAnalysisDetail['missing_metrics']) && is_array($selectedAnalysisDetail['missing_metrics']))
                                 <div class="p-4 bg-amber-950/10 border border-amber-900/30 rounded-xl space-y-2">
                                    <h4 class="text-sm font-bold text-amber-300">{{ __('cv-workspace.modals.analysis_detail.missing_metrics') }}</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['missing_metrics'] as $metric)
                                             <li class="flex gap-2"><span class="text-amber-400">•</span><span>{{ $metric }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif

                             @if(!empty($selectedAnalysisDetail['rewrite_priorities']) && is_array($selectedAnalysisDetail['rewrite_priorities']))
                                 <div class="p-4 bg-sky-950/10 border border-sky-900/30 rounded-xl space-y-2">
                                    <h4 class="text-sm font-bold text-sky-300">{{ __('cv-workspace.modals.analysis_detail.rewrite_priorities') }}</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['rewrite_priorities'] as $priority)
                                             <li class="flex gap-2"><span class="text-sky-400">•</span><span>{{ $priority }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif
                         </div>

                         @if(!empty($selectedAnalysisDetail['experience_review']) && is_array($selectedAnalysisDetail['experience_review']))
                             <div class="space-y-3">
                                 <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-document-magnifying-glass class="w-4.5 h-4.5 text-cyan-400" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.experience_review_heading') }}</span>
                                 </h4>
                                 <div class="space-y-2.5">
                                     @php $historyReviewIndex = 0; @endphp
                                     @foreach($selectedAnalysisDetail['experience_review'] as $review)
                                         @if(is_string($review))
                                             <div class="p-3.5 bg-[#181824]/40 border-l-2 border-cyan-500/50 border-y border-r border-[#2d2d3a]/50 rounded-lg">
                                                 <div class="flex items-start gap-2.5">
                                                     <x-heroicon-o-information-circle class="w-3.5 h-3.5 text-cyan-400 mt-0.5 flex-shrink-0" />
                                                     <p class="text-xs text-slate-300 leading-relaxed">{{ $review }}</p>
                                                 </div>
                                             </div>
                                         @elseif(is_array($review))
                                             @php
                                                 $reviewSection = trim((string) ($review['section'] ?? ''));
                                                 $reviewStrength = trim((string) ($review['strength'] ?? ''));
                                                 $reviewGap = trim((string) ($review['gap'] ?? ''));
                                                 $reviewHint = trim((string) ($review['rewrite_hint'] ?? ''));
                                                 $historyReviewIndex++;
                                             @endphp
                                             <div class="bg-[#181824]/40 border border-[#2d2d3a]/50 rounded-xl overflow-hidden">
                                                 <div class="flex items-center gap-2.5 px-4 py-2.5 bg-[#1c1c2a]/60 border-b border-[#2d2d3a]/50">
                                                     <span class="w-6 h-6 rounded-md bg-cyan-950/60 border border-cyan-500/20 text-cyan-400 text-[10px] font-bold font-mono flex items-center justify-center flex-shrink-0">{{ $historyReviewIndex }}</span>
                                                    <h5 class="text-sm font-bold text-white truncate">{{ $reviewSection !== '' ? $reviewSection : __('cv-workspace.modals.analysis_detail.experience_review_default') }}</h5>
                                                 </div>
                                                 <div class="p-4 space-y-3">
                                                     @if($reviewStrength !== '')
                                                         <div class="flex items-start gap-2.5">
                                                             <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-emerald-400 mt-0.5 flex-shrink-0" />
                                                             <div class="min-w-0">
                                                                <span class="block text-[10px] uppercase font-bold tracking-wider text-emerald-400 mb-0.5">{{ __('cv-workspace.modals.analysis_detail.review_strength') }}</span>
                                                                 <p class="text-xs text-slate-300 leading-relaxed">{{ $reviewStrength }}</p>
                                                             </div>
                                                         </div>
                                                     @endif
                                                     @if($reviewGap !== '')
                                                         <div class="flex items-start gap-2.5">
                                                             <x-heroicon-o-x-circle class="w-3.5 h-3.5 text-rose-400 mt-0.5 flex-shrink-0" />
                                                             <div class="min-w-0">
                                                                <span class="block text-[10px] uppercase font-bold tracking-wider text-rose-400 mb-0.5">{{ __('cv-workspace.modals.analysis_detail.review_gap') }}</span>
                                                                 <p class="text-xs text-slate-300 leading-relaxed">{{ $reviewGap }}</p>
                                                             </div>
                                                         </div>
                                                     @endif
                                                     @if($reviewHint !== '')
                                                         <div class="flex items-start gap-2.5 bg-sky-950/20 border border-sky-500/10 rounded-lg p-2.5">
                                                             <x-heroicon-o-pencil-square class="w-3.5 h-3.5 text-sky-400 mt-0.5 flex-shrink-0" />
                                                             <div class="min-w-0">
                                                                <span class="block text-[10px] uppercase font-bold tracking-wider text-sky-400 mb-0.5">{{ __('cv-workspace.modals.analysis_detail.review_hint') }}</span>
                                                                 <p class="text-xs text-slate-300 leading-relaxed">{{ $reviewHint }}</p>
                                                             </div>
                                                         </div>
                                                     @endif
                                                 </div>
                                             </div>
                                         @endif
                                     @endforeach
                                 </div>
                             </div>
                         @endif

                         {{-- Industry/Domain Match --}}
                         @if(!empty($selectedAnalysisDetail['industry_match']) && is_array($selectedAnalysisDetail['industry_match']))
                             <div class="space-y-3">
                                 <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-tag class="w-4.5 h-4.5 text-cyan-400" />
                                    <span>{{ __('cv-workspace.modals.analysis_detail.coverage_match_heading') }}</span>
                                 </h4>
                                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                     @foreach($selectedAnalysisDetail['industry_match'] as $domain => $score)
                                         <div class="p-3.5 bg-[#181824]/40 border border-[#2d2d3a]/60 rounded-xl space-y-2">
                                             <div class="flex justify-between items-center text-xs">
                                                 <span class="font-bold text-slate-300">{{ $domain }}</span>
                                                 <span class="font-mono text-cyan-400 font-bold">{{ $score }}%</span>
                                             </div>
                                             <div class="w-full bg-slate-850 rounded-full h-1.5 overflow-hidden border border-slate-700/30">
                                                 <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $score }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @endif

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-between items-center gap-3">
                    @if($selectedAnalysisDetail)
                        <button type="button" wire:click="loadAnalysisIntoWorkspace({{ $selectedAnalysisDetail['id'] }})" @click="close()" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all flex items-center gap-1.5">
                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                            <span>{{ __('cv-workspace.modals.analysis_detail.load_into_workspace') }}</span>
                        </button>
                    @else
                        <div></div>
                    @endif
                    <button type="button" @click="close()" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        {{ __('cv-workspace.modals.analysis_detail.close_btn') }}
                    </button>
                </div>
            </div>
        </div>
