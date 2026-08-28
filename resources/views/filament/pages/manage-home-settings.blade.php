<x-filament-panels::page>
    <div class="home-settings-container space-y-6 font-space-grotesk text-slate-700 dark:text-slate-300">
        {{-- Header — simplified --}}
        <div class="glass-card p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Homepage Settings</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Branding, hero, sections, and system status in one place.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="/" target="_blank" class="px-3.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" /> Preview
                </a>
                <x-filament::button wire:click="saveSettings" color="primary">
                    <x-heroicon-o-cloud-arrow-up class="w-4 h-4 mr-1.5" /> Save
                </x-filament::button>
            </div>
        </div>

        {{-- Tabs — 3 only --}}
        <div class="workspace-tabs-container">
            <div class="workspace-tabs flex gap-2">
                <button wire:click="switchTab('content')" class="tab-btn {{ $activeTab === 'content' ? 'active' : '' }}">
                    <x-heroicon-o-document-duplicate class="w-4 h-4" /> Content
                </button>
                <button wire:click="switchTab('system')" class="tab-btn {{ $activeTab === 'system' ? 'active' : '' }}">
                    <x-heroicon-o-server class="w-4 h-4" /> System
                </button>
                <button wire:click="switchTab('preview')" class="tab-btn {{ $activeTab === 'preview' ? 'active' : '' }}">
                    <x-heroicon-o-eye class="w-4 h-4" /> Preview
                </button>
            </div>
        </div>

        @if($activeTab === 'content')
            <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Hero — urutan sama dengan home: badge → H1 (2 baris) → description → CTA → brand/terminal --}}
                    <div class="glass-card p-6 space-y-4">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-[#2d2d3a] pb-3 flex items-center gap-2">
                            <x-heroicon-o-sparkles class="w-5 h-5 text-cyan-500" /> Hero
                        </h3>
                        {{-- 1. H1 dua baris --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Hero Greeting — H1 Baris 1</label>
                                <input type="text" wire:model.defer="hero_greeting" class="cyber-input w-full" placeholder="Hi, I'm a" required />
                                <span class="text-[10px] text-slate-500">Baris 1 di hero, di atas title biru.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Site Title — H1 Baris 2 (Biru)</label>
                                <input type="text" wire:model.defer="site_title" class="cyber-input w-full font-semibold" placeholder="DevOps Engineer" required />
                                <span class="text-[10px] text-slate-500">Highlight biru di hero + title tab.</span>
                            </div>
                        </div>
                        {{-- 2. Badge --}}
                        <div class="rounded-lg bg-slate-50 dark:bg-[#111119]/40 border border-slate-200 dark:border-[#2d2d3a] p-3">
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Availability Badge — di atas H1</label>
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-3 items-end">
                                <div>
                                    <input type="text" wire:model.defer="availability_status" class="cyber-input w-full" placeholder="Available for work" required />
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer whitespace-nowrap pb-2">
                                    <input type="checkbox" wire:model.defer="availability_active" class="rounded border-slate-300 dark:border-slate-700 text-cyan-600" />
                                    <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Show badge</span>
                                </label>
                            </div>
                        </div>
                        {{-- 3. Description --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Hero Description — border kiri di home</label>
                            <textarea wire:model.defer="hero_description" rows="4" class="cyber-textarea w-full" placeholder="Short intro..."></textarea>
                        </div>
                        {{-- 4. CTA Hero --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-slate-200 dark:border-[#2d2d3a]">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">CTA Primary — tombol hitam di hero</label>
                                <input type="text" wire:model.defer="cta_primary_button_text" class="cyber-input w-full" placeholder="Get In Touch" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">CTA Secondary — tombol putih di hero</label>
                                <input type="text" wire:model.defer="cta_secondary_button_text" class="cyber-input w-full" placeholder="Download CV" required />
                            </div>
                        </div>
                        {{-- 5. Terminal / Brand --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-slate-200 dark:border-[#2d2d3a]">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Site Brand — $ whoami + logo</label>
                                <input type="text" wire:model.defer="site_brand" class="cyber-input w-full font-mono" placeholder="~/devops" required />
                                <span class="text-[10px] text-slate-500">Muncul di nav + terminal.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Meta Description — focus di terminal</label>
                                <textarea wire:model.defer="meta_description" rows="2" class="cyber-textarea w-full" placeholder="SEO + focus: ..."></textarea>
                            </div>
                        </div>
                        {{-- 6. Footer --}}
                        <div class="pt-3 border-t border-slate-200 dark:border-[#2d2d3a]">
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Footer Text — paling bawah</label>
                            <input type="text" wire:model.defer="footer_text" class="cyber-input w-full" placeholder="Built with Laravel" required />
                        </div>
                    </div>

                    {{-- Sections --}}
                    <div class="glass-card p-6 space-y-4">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-[#2d2d3a] pb-3 flex items-center gap-2">
                            <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-500" /> Sections
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Tech Stack Title</label>
                                <input type="text" wire:model.defer="tech_stack_title" class="cyber-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Tech Stack Desc</label>
                                <input type="text" wire:model.defer="tech_stack_description" class="cyber-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Projects Title</label>
                                <input type="text" wire:model.defer="featured_projects_title" class="cyber-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Projects Desc</label>
                                <input type="text" wire:model.defer="featured_projects_description" class="cyber-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">CTA Title — bottom black block</label>
                                <input type="text" wire:model.defer="cta_title" class="cyber-input w-full" required />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">CTA Description — bottom block</label>
                                <textarea wire:model.defer="cta_description" rows="3" class="cyber-textarea w-full"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="glass-card p-6 space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-[#2d2d3a] pb-3">Logo Mockup</h3>
                        <div class="bg-slate-100 dark:bg-[#0b0b10] border border-slate-200 dark:border-[#2d2d3a] p-6 rounded-xl flex items-center justify-center">
                            <span class="font-mono font-bold text-xl bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">{{ $site_brand ?: '~/brand' }}</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 text-center">Renders in nav & footer.</p>
                    </div>
                    <x-filament::button type="submit" color="primary" class="w-full">
                        Save Content
                    </x-filament::button>
                </div>
            </form>
        @endif

        @if($activeTab === 'system')
            <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="glass-card p-6 space-y-4">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-[#2d2d3a] pb-3 flex items-center gap-2">
                            <x-heroicon-o-command-line class="w-5 h-5 text-cyan-500" /> Terminal Commands
                        </h3>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase">Commands (press Enter to add)</label>
                            <input type="text" placeholder="terraform apply -auto-approve" class="cyber-input w-full" 
                                x-data="{val: ''}" 
                                x-on:keydown.enter.prevent="$wire.set('terminal_commands', [...$wire.get('terminal_commands'), $el.value]); $el.value=''"
                                />
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($terminal_commands as $i => $cmd)
                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 dark:bg-[#1a1a24] border border-slate-200 dark:border-[#2d2d3a] px-2.5 py-1 rounded-full text-xs font-mono">
                                        <span class="text-cyan-600 dark:text-cyan-400">$</span> {{ $cmd }}
                                        <button type="button" wire:click="$set('terminal_commands', {{ json_encode(array_values(array_filter($terminal_commands, fn($k)=>$k!==$i, ARRAY_FILTER_USE_KEY))) }})" class="text-slate-400 hover:text-rose-500">×</button>
                                    </span>
                                @endforeach
                                @if(empty($terminal_commands))
                                    <span class="text-xs text-slate-500 dark:text-slate-500 italic">No commands — add above.</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-500 mt-2">Stored as array `terminal_commands`, rotates in hero.</p>
                        </div>
                    </div>


                </div>
                <div class="lg:col-span-1 space-y-6">
                    <div class="glass-card p-6 space-y-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-[#2d2d3a] pb-2">Preview</h3>
                        <div class="bg-slate-50 dark:bg-[#050508] border border-slate-200 dark:border-cyan-500/20 p-4 rounded-xl font-mono text-xs">
                            <div class="text-slate-500"># preview</div>
                            <div class="text-cyan-600 dark:text-cyan-400 font-bold">> {{ $terminal_commands[0] ?? 'echo ready' }}</div>
                        </div>
                    </div>
                    <x-filament::button type="submit" color="primary" class="w-full">Save System</x-filament::button>
                </div>
            </form>
        @endif

        @if($activeTab === 'preview')
            <div class="space-y-4">
                <div class="glass-card p-4 flex flex-col sm:flex-row justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded bg-cyan-100 dark:bg-cyan-950 border border-cyan-200 dark:border-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Live Preview</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Homepage sandbox.</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('home-iframe').src='/'" class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold">Refresh</button>
                </div>
                <div class="bg-white dark:bg-[#0e0e13] border border-slate-200 dark:border-[#2d2d3a] rounded-xl overflow-hidden">
                    <div class="flex items-center gap-1.5 px-4 py-2 border-b border-slate-200 dark:border-[#2d2d3a] bg-slate-50 dark:bg-[#12121a]">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span class="ml-3 text-xs font-mono text-slate-500">/</span>
                    </div>
                    <iframe id="home-iframe" src="/" sandbox="allow-same-origin allow-scripts" loading="lazy" class="w-full border-none bg-white dark:bg-slate-900" style="height: 700px;"></iframe>
                </div>
            </div>
        @endif
    </div>

    <style>
        .workspace-tabs-container { border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 16px; }
        .dark .workspace-tabs-container { border-color: rgba(45,45,58,0.4); }
        .workspace-tabs { background: #f1f5f9; padding: 4px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; gap: 4px; width: max-content; }
        .dark .workspace-tabs { background: rgba(13,13,20,0.6); border-color: rgba(255,255,255,0.03); box-shadow: inset 0 2px 10px rgba(0,0,0,0.8); }
        .tab-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:10px; font-size:0.85rem; font-weight:600; color:#64748b; background:transparent; border:1px solid transparent; cursor:pointer; }
        .tab-btn.active { color:#0ea5e9; background:#e0f2fe; border-color:#7dd3fc; }
        .dark .tab-btn.active { color:#00d4ff; background: rgba(6,182,212,0.08); border-color: rgba(6,182,212,0.35); box-shadow: 0 0 15px rgba(6,182,212,0.15); }
        .glass-card { background: #ffffff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 4px 20px rgba(15,23,42,0.06); }
        .dark .glass-card { background: rgba(26,26,36,0.7) !important; border-color:#2d2d3a !important; backdrop-filter: blur(20px) saturate(180%); box-shadow: 0 10px 30px rgba(0,0,0,0.4) !important; }
        .cyber-input, .cyber-textarea { background:#ffffff; border:1px solid #cbd5e1; border-radius:10px; color:#0f172a; padding:10px 14px; font-size:0.875rem; width:100%; }
        .dark .cyber-input, .dark .cyber-textarea { background: rgba(18,18,26,0.8) !important; border-color:#2d2d3a !important; color:#f1f5f9 !important; }
    </style>
</x-filament-panels::page>
