<x-filament-panels::page>
    <div class="home-settings-container space-y-6 text-zinc-700 dark:text-zinc-300">
        {{-- Header — simplified --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 tracking-tight">Homepage Settings</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Branding, hero, sections, and system status in one place.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="/" target="_blank" class="px-3.5 py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" /> Preview
                </a>
                <x-filament::button wire:click="saveSettings" color="primary">
                    <x-heroicon-o-cloud-arrow-up class="w-4 h-4 mr-1.5" /> Save
                </x-filament::button>
            </div>
        </div>

        {{-- Tabs — 3 only --}}
        <div class="border-b border-zinc-200 dark:border-zinc-700 pb-2 mb-4">
            <div class="flex gap-1 w-max p-1 rounded-xl border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900">
                <button wire:click="switchTab('content')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border cursor-pointer {{ $activeTab === 'content' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border-zinc-200 dark:border-zinc-700' : 'bg-transparent border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
                    <x-heroicon-o-document-duplicate class="w-4 h-4" /> Content
                </button>
                <button wire:click="switchTab('system')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border cursor-pointer {{ $activeTab === 'system' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border-zinc-200 dark:border-zinc-700' : 'bg-transparent border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
                    <x-heroicon-o-server class="w-4 h-4" /> System
                </button>
                <button wire:click="switchTab('preview')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border cursor-pointer {{ $activeTab === 'preview' ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border-zinc-200 dark:border-zinc-700' : 'bg-transparent border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
                    <x-heroicon-o-eye class="w-4 h-4" /> Preview
                </button>
            </div>
        </div>

        @if($activeTab === 'content')
            <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Hero — urutan sama dengan home: badge → H1 (2 baris) → description → CTA → brand/terminal --}}
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 space-y-4">
                        <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-3 flex items-center gap-2">
                            <x-heroicon-o-sparkles class="w-5 h-5 text-zinc-500 dark:text-zinc-400" /> Hero
                        </h3>
                        {{-- 1. H1 dua baris --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Hero Greeting — H1 Baris 1</label>
                                <input type="text" wire:model.defer="hero_greeting" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="Hi, I'm a" required />
                                <span class="text-[10px] text-zinc-500">Baris 1 di hero, di atas title biru.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Site Title — H1 Baris 2 (Biru)</label>
                                <input type="text" wire:model.defer="site_title" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 font-semibold" placeholder="DevOps Engineer" required />
                                <span class="text-[10px] text-zinc-500">Highlight biru di hero + title tab.</span>
                            </div>
                        </div>
                        {{-- 2. Badge --}}
                        <div class="rounded-lg bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 p-3">
                            <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Availability Badge — di atas H1</label>
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-3 items-end">
                                <div>
                                    <input type="text" wire:model.defer="availability_status" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="Available for work" required />
                                </div>
                                <label class="flex items-center gap-2 cursor-pointer whitespace-nowrap pb-2">
                                    <input type="checkbox" wire:model.defer="availability_active" class="rounded border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400" />
                                    <span class="text-xs font-medium text-zinc-600 dark:text-zinc-300">Show badge</span>
                                </label>
                            </div>
                        </div>
                        {{-- 3. Description --}}
                        <div>
                            <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Hero Description — border kiri di home</label>
                            <textarea wire:model.defer="hero_description" rows="4" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="Short intro..."></textarea>
                        </div>
                        {{-- 4. CTA Hero --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">CTA Primary — tombol hitam di hero</label>
                                <input type="text" wire:model.defer="cta_primary_button_text" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="Get In Touch" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">CTA Secondary — tombol putih di hero</label>
                                <input type="text" wire:model.defer="cta_secondary_button_text" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="Download CV" required />
                            </div>
                        </div>
                        {{-- 5. Terminal / Brand --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Site Brand — $ whoami + logo</label>
                                <input type="text" wire:model.defer="site_brand" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 font-mono" placeholder="~/devops" required />
                                <span class="text-[10px] text-zinc-500">Muncul di nav + terminal.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Meta Description — focus di terminal</label>
                                <textarea wire:model.defer="meta_description" rows="2" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="SEO + focus: ..."></textarea>
                            </div>
                        </div>
                        {{-- 6. Footer --}}
                        <div class="pt-3 border-t border-zinc-200 dark:border-zinc-700">
                            <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Footer Text — paling bawah</label>
                            <input type="text" wire:model.defer="footer_text" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" placeholder="Built with Laravel" required />
                        </div>
                    </div>

                    {{-- Sections --}}
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 space-y-4">
                        <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-3 flex items-center gap-2">
                            <x-heroicon-o-briefcase class="w-5 h-5 text-zinc-500 dark:text-zinc-400" /> Sections
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Tech Stack Title</label>
                                <input type="text" wire:model.defer="tech_stack_title" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Tech Stack Desc</label>
                                <input type="text" wire:model.defer="tech_stack_description" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Projects Title</label>
                                <input type="text" wire:model.defer="featured_projects_title" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Projects Desc</label>
                                <input type="text" wire:model.defer="featured_projects_description" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">CTA Title — bottom black block</label>
                                <input type="text" wire:model.defer="cta_title" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" required />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">CTA Description — bottom block</label>
                                <textarea wire:model.defer="cta_description" rows="3" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 space-y-4">
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-3">Logo Mockup</h3>
                        <div class="bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 p-6 rounded-xl flex items-center justify-center">
                            <span class="font-mono font-bold text-xl text-zinc-900 dark:text-zinc-100">{{ $site_brand ?: '~/brand' }}</span>
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 text-center">Renders in nav & footer.</p>
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
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 space-y-4">
                        <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-3 flex items-center gap-2">
                            <x-heroicon-o-command-line class="w-5 h-5 text-zinc-500 dark:text-zinc-400" /> Terminal Commands
                        </h3>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1.5 uppercase">Commands (press Enter to add)</label>
                            <input type="text" placeholder="terraform apply -auto-approve" class="w-full px-3.5 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" 
                                x-data="{val: ''}" 
                                x-on:keydown.enter.prevent="$wire.set('terminal_commands', [...$wire.get('terminal_commands'), $el.value]); $el.value=''"
                                />
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($terminal_commands as $i => $cmd)
                                    <span class="inline-flex items-center gap-1.5 bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 px-2.5 py-1 rounded-full text-xs font-mono">
                                        <span class="text-zinc-500 dark:text-zinc-400">$</span> {{ $cmd }}
                                        <button type="button" wire:click="$set('terminal_commands', {{ json_encode(array_values(array_filter($terminal_commands, fn($k)=>$k!==$i, ARRAY_FILTER_USE_KEY))) }})" class="text-zinc-400 hover:text-rose-500">×</button>
                                    </span>
                                @endforeach
                                @if(empty($terminal_commands))
                                    <span class="text-xs text-zinc-500 dark:text-zinc-500 italic">No commands — add above.</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-zinc-500 mt-2">Stored as array `terminal_commands`, rotates in hero.</p>
                        </div>
                    </div>


                </div>
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6 space-y-3">
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-2">Preview</h3>
                        <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 p-4 rounded-xl font-mono text-xs">
                            <div class="text-zinc-500"># preview</div>
                            <div class="text-zinc-500 dark:text-zinc-400 font-bold">> {{ $terminal_commands[0] ?? 'echo ready' }}</div>
                        </div>
                    </div>
                    <x-filament::button type="submit" color="primary" class="w-full">Save System</x-filament::button>
                </div>
            </form>
        @endif

        @if($activeTab === 'preview')
            <div class="space-y-4">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-4 flex flex-col sm:flex-row justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 flex items-center justify-center">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">Live Preview</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Homepage sandbox.</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('home-iframe').src='/'" class="px-3 py-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs font-semibold">Refresh</button>
                </div>
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                    <div class="flex items-center gap-1.5 px-4 py-2 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span class="ml-3 text-xs font-mono text-zinc-500">/</span>
                    </div>
                    <iframe id="home-iframe" src="/" sandbox="allow-same-origin allow-scripts" loading="lazy" class="w-full border-none bg-white dark:bg-zinc-900" style="height: 700px;"></iframe>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
