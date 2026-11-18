        {{-- Workspace tab navigation --}}
        <div x-data="{
                activeTab: @entangle('activeTab').live,
                optimisticTab: '{{ $activeTab }}',
                tabs: ['overview', 'profile', 'experience', 'education', 'skills', 'certificates', 'aimatch', 'preview'],
                canScrollLeft: false,
                canScrollRight: false,
                savedScrollY: null,
                
                checkScroll() {
                    const el = this.$refs.tabList;
                    if (!el) return;
                    this.canScrollLeft = el.scrollLeft > 4;
                    this.canScrollRight = el.scrollLeft + el.clientWidth < el.scrollWidth - 4;
                },
                
                scrollToTab(tab) {
                    Alpine.nextTick(() => {
                        const btn = this.$refs['tab_' + tab];
                        if (btn) {
                            btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                        }
                        this.checkScroll();
                    });
                },
                
                selectTab(tab) {
                    if (this.optimisticTab === tab) return;
                    this.savedScrollY = window.scrollY;
                    this.optimisticTab = tab;
                    this.scrollToTab(tab);
                    $wire.switchTab(tab);
                },
                
                focusTab(index) {
                    if (index < 0) index = this.tabs.length - 1;
                    if (index >= this.tabs.length) index = 0;
                    const targetTab = this.tabs[index];
                    const targetBtn = this.$refs['tab_' + targetTab];
                    if (targetBtn) {
                        targetBtn.focus();
                        this.selectTab(targetTab);
                    }
                }
            }"
            x-init="
                optimisticTab = activeTab;
                $nextTick(() => {
                    checkScroll();
                    scrollToTab(activeTab);
                });
                $watch('activeTab', (val) => {
                    optimisticTab = val;
                    scrollToTab(val);
                    if (savedScrollY !== null) {
                        const y = savedScrollY;
                        $nextTick(() => {
                            window.scrollTo({ top: y, behavior: 'instant' });
                        });
                    }
                });
                Livewire.hook('morph.updated', () => {
                    if (savedScrollY !== null) {
                        const y = savedScrollY;
                        $nextTick(() => {
                            window.scrollTo({ top: y, behavior: 'instant' });
                        });
                    }
                });
            "
            wire:ignore
            class="workspace-tabs-container relative">

            {{-- Left scroll hint --}}
            <div x-cloak
                 x-show="canScrollLeft"
                 class="pointer-events-none absolute left-0 top-0 bottom-2 w-8 sm:w-12 bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700 z-10 flex items-center pl-1 text-zinc-500">
                <x-heroicon-m-chevron-left class="w-4 h-4" />
            </div>

            <div x-ref="tabList"
                 role="tablist"
                 aria-label="{{ __('cv-workspace.tabs.aria_label') }}"
                 @scroll.passive="checkScroll()"
                 @resize.window.passive="checkScroll()"
                 @keydown.right.prevent="focusTab((tabs.indexOf(optimisticTab) + 1) % tabs.length)"
                 @keydown.left.prevent="focusTab((tabs.indexOf(optimisticTab) - 1 + tabs.length) % tabs.length)"
                 @keydown.home.prevent="focusTab(0)"
                 @keydown.end.prevent="focusTab(tabs.length - 1)"
                 class="workspace-tabs flex overflow-x-auto gap-1.5 pb-2 scrollbar-none lg:w-full lg:justify-between lg:overflow-x-visible">
                
                <button type="button"
                        role="tab"
                        id="tab-overview"
                        aria-controls="tabpanel-overview"
                        :aria-selected="optimisticTab === 'overview' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'overview' ? '0' : '-1'"
                        x-ref="tab_overview"
                        @click="selectTab('overview')"
                        :class="optimisticTab === 'overview' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-chart-bar class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.dashboard') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-profile"
                        aria-controls="tabpanel-profile"
                        :aria-selected="optimisticTab === 'profile' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'profile' ? '0' : '-1'"
                        x-ref="tab_profile"
                        @click="selectTab('profile')"
                        :class="optimisticTab === 'profile' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-user-circle class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.personal_info') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-experience"
                        aria-controls="tabpanel-experience"
                        :aria-selected="optimisticTab === 'experience' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'experience' ? '0' : '-1'"
                        x-ref="tab_experience"
                        @click="selectTab('experience')"
                        :class="optimisticTab === 'experience' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-briefcase class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.experience') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-education"
                        aria-controls="tabpanel-education"
                        :aria-selected="optimisticTab === 'education' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'education' ? '0' : '-1'"
                        x-ref="tab_education"
                        @click="selectTab('education')"
                        :class="optimisticTab === 'education' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-academic-cap class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.education') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-skills"
                        aria-controls="tabpanel-skills"
                        :aria-selected="optimisticTab === 'skills' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'skills' ? '0' : '-1'"
                        x-ref="tab_skills"
                        @click="selectTab('skills')"
                        :class="optimisticTab === 'skills' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-bolt class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.skills') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-certificates"
                        aria-controls="tabpanel-certificates"
                        :aria-selected="optimisticTab === 'certificates' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'certificates' ? '0' : '-1'"
                        x-ref="tab_certificates"
                        @click="selectTab('certificates')"
                        :class="optimisticTab === 'certificates' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-trophy class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.certificates') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-aimatch"
                        aria-controls="tabpanel-aimatch"
                        :aria-selected="optimisticTab === 'aimatch' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'aimatch' ? '0' : '-1'"
                        x-ref="tab_aimatch"
                        @click="selectTab('aimatch')"
                        :class="optimisticTab === 'aimatch' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-sparkles class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.ai_job_match') }}</span>
                </button>

                <button type="button"
                        role="tab"
                        id="tab-preview"
                        aria-controls="tabpanel-preview"
                        :aria-selected="optimisticTab === 'preview' ? 'true' : 'false'"
                        :tabindex="optimisticTab === 'preview' ? '0' : '-1'"
                        x-ref="tab_preview"
                        @click="selectTab('preview')"
                        :class="optimisticTab === 'preview' ? 'active' : ''"
                        class="tab-btn !px-2.5 !py-2 lg:!px-1.5 lg:!py-2 2xl:!px-3.5 2xl:!py-2.5 !gap-1.5 2xl:!gap-2 text-xs 2xl:text-sm shrink-0 lg:shrink lg:flex-1 justify-center">
                    <x-heroicon-o-eye class="w-4 h-4 2xl:w-4.5 2xl:h-4.5 shrink-0" />
                    <span class="truncate">{{ __('cv-workspace.tabs.live_preview') }}</span>
                </button>
            </div>

            {{-- Right scroll hint --}}
            <div x-cloak
                 x-show="canScrollRight"
                 class="pointer-events-none absolute right-0 top-0 bottom-2 w-8 sm:w-12 bg-zinc-50 dark:bg-zinc-900 border-l border-zinc-200 dark:border-zinc-700 z-10 flex items-center justify-end pr-1 text-zinc-500">
                <x-heroicon-m-chevron-right class="w-4 h-4" />
            </div>
        </div>
