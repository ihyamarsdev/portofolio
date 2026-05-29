<x-filament-panels::page>
    @vite('resources/css/app.css')

    <div class="home-settings-container space-y-8 font-space-grotesk text-slate-300">
        
        {{-- Unified Header --}}
        <div class="glass-card premium-workspace-header p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="pulse-dot-settings"></span>
                    <h2 class="text-2xl font-bold text-white tracking-wide">Homepage Control Center</h2>
                </div>
                <p class="text-sm text-slate-400 mt-1">Configure layout options, live command loops, infrastructure statuses, and branding metadata.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="/" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-all">
                    <span>Open Public Homepage</span>
                    <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" />
                </a>

                <x-filament::button wire:click="saveSettings" color="primary" class="btn-workspace-action glow-cyan-hover">
                    <x-heroicon-o-cloud-arrow-up class="w-4 h-4 mr-1.5" />
                    <span>Synchronize Settings</span>
                </x-filament::button>
            </div>
        </div>

        {{-- Glowing Cyberpunk Tab Navigation --}}
        <div class="workspace-tabs-container">
            <div class="workspace-tabs flex overflow-x-auto gap-2 pb-2 scrollbar-thin">
                <button wire:click="switchTab('branding')" class="tab-btn {{ $activeTab === 'branding' ? 'active' : '' }}">
                    <x-heroicon-o-globe-alt class="w-4.5 h-4.5" />
                    <span>Branding & Identity</span>
                </button>
                <button wire:click="switchTab('hero')" class="tab-btn {{ $activeTab === 'hero' ? 'active' : '' }}">
                    <x-heroicon-o-sparkles class="w-4.5 h-4.5" />
                    <span>Hero & Terminal</span>
                </button>
                <button wire:click="switchTab('section_copy')" class="tab-btn {{ $activeTab === 'section_copy' ? 'active' : '' }}">
                    <x-heroicon-o-document-duplicate class="w-4.5 h-4.5" />
                    <span>Sections & Buttons</span>
                </button>
                <button wire:click="switchTab('infrastructure')" class="tab-btn {{ $activeTab === 'infrastructure' ? 'active' : '' }}">
                    <x-heroicon-o-server class="w-4.5 h-4.5" />
                    <span>Live Infrastructure</span>
                </button>
                <button wire:click="switchTab('preview')" class="tab-btn {{ $activeTab === 'preview' ? 'active' : '' }}">
                    <x-heroicon-o-eye class="w-4.5 h-4.5" />
                    <span>Live Viewport</span>
                </button>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB 1: BRANDING & IDENTITY --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'branding')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-globe-alt class="w-5 h-5 text-cyan-400" />
                                <span>Core Brand Identity</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" wire:key="brand_identity_grid">
                                <div wire:key="brand_logo_wrapper">
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Site Brand (Navbar Logo)</label>
                                    <input type="text" id="site_brand" wire:model.defer="site_brand" class="cyber-input w-full font-mono text-cyan-400" placeholder="e.g., ~/devops" required />
                                    <span class="text-[10px] text-slate-500 mt-1 block">Monospace styled logo text in standard nav bars.</span>
                                </div>
                                <div wire:key="brand_title_wrapper">
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Professional Title</label>
                                    <input type="text" id="site_title" wire:model.defer="site_title" class="cyber-input w-full" placeholder="e.g., Senior Cloud & DevOps Engineer" required />
                                    <span class="text-[10px] text-slate-500 mt-1 block">Appears next to brand name in browser titles.</span>
                                </div>
                            </div>

                            <div wire:key="brand_footer_wrapper">
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Footer Copyright Message</label>
                                <input type="text" id="footer_text" wire:model.defer="footer_text" class="cyber-input w-full" placeholder="e.g., DevOps Portfolio. Built with Laravel." required />
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4" wire:key="seo_section_card">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-magnifying-glass class="w-5 h-5 text-cyan-400" />
                                <span>SEO Search Engine Optimization</span>
                            </h3>

                            <div wire:key="seo_meta_wrapper">
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Meta Description (Recommended length 150-160 chars)</label>
                                <textarea id="meta_description" wire:model.defer="meta_description" rows="4" class="cyber-textarea w-full" placeholder="Write a short summary introducing your DevOps stack and cloud engineering specialties to search crawlers..."></textarea>
                                <div class="flex justify-between items-center text-[10px] text-slate-500 mt-1">
                                    <span>Used globally on all public view pages.</span>
                                    <span>Current: {{ strlen($meta_description ?? '') }} characters</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3">Logo Mockup</h3>
                            <div class="bg-[#0b0b10] border border-[#2d2d3a] p-8 rounded-xl flex items-center justify-center relative overflow-hidden group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-950/20 via-transparent to-transparent opacity-50"></div>
                                <span class="font-mono font-bold text-2xl tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 drop-shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                                    {{ $site_brand ?: '~/brand' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 text-center leading-relaxed">This brand name renders as a glowing vector logo at the header and footer of your website layout.</p>
                        </div>

                        <div class="glass-card p-6 flex flex-col justify-between min-h-[180px]">
                            <div>
                                <h4 class="font-bold text-white text-sm">Save Branding</h4>
                                <p class="text-xs text-slate-400 mt-1">Changes are pushed instantly to all site page layout providers.</p>
                            </div>
                            <x-filament::button type="submit" color="primary" class="w-full mt-4 glow-cyan-hover">
                                Save Branding
                            </x-filament::button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 2: HERO & TERMINAL CONSOLE --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'hero')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-sparkles class="w-5 h-5 text-cyan-400" />
                                <span>Hero Introduction Settings</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Hero Welcome / Greeting</label>
                                    <input type="text" wire:model.defer="hero_greeting" class="cyber-input w-full" placeholder="e.g., Hi, I'm a" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Availability Status Message</label>
                                    <input type="text" wire:model.defer="availability_status" class="cyber-input w-full text-emerald-400" placeholder="e.g., Open for new contracts" required />
                                </div>
                            </div>

                             <div class="flex items-center gap-3 bg-[#111119]/40 p-3.5 rounded-lg">
                                 <input type="checkbox" wire:model.defer="availability_active" id="avail_active" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/20" />
                                 <label for="avail_active" class="text-xs text-slate-300 font-semibold cursor-pointer select-none">
                                     Display Availability Badge Status prominently on the Homepage Hero.
                                 </label>
                             </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Hero Detailed Description</label>
                                <textarea wire:model.defer="hero_description" rows="5" class="cyber-textarea w-full font-sans" placeholder="Write a short summary introducing your specialties, core values, and automation philosophies..."></textarea>
                                <span class="text-[10px] text-slate-500 mt-1 block">Supports HTML styling (bolding, highlighting, spacing) safely.</span>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-command-line class="w-5 h-5 text-cyan-400" />
                                <span>Animated Terminal Command Loops</span>
                            </h3>

                            <div class="space-y-3">
                                <label class="block text-xs font-semibold text-slate-400 uppercase">Interactive Terminal Loop Commands</label>
                                <div class="flex gap-2">
                                    <input type="text" wire:model.defer="new_command" wire:keydown.enter.prevent="addCommand" class="cyber-input flex-1 text-xs font-mono" placeholder="e.g., terraform apply -auto-approve" />
                                    <button type="button" wire:click="addCommand" class="px-4 bg-cyan-950 border border-cyan-500/30 text-cyan-400 rounded-lg text-xs font-bold hover:bg-cyan-900 transition-colors">
                                        Add Command
                                    </button>
                                </div>

                                {{-- Terminal Command Stack Renders --}}
                                <div class="bg-[#08080c] border border-[#1e1e2d] rounded-xl overflow-hidden font-mono shadow-inner shadow-black">
                                    <div class="bg-[#12121a] px-4 py-2 border-b border-[#2d2d3a] flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                        </div>
                                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Active Rotations ({{ count($terminal_commands) }})</span>
                                    </div>
                                    <div class="p-4 space-y-2 min-h-[150px] max-h-[300px] overflow-y-auto">
                                        @if(count($terminal_commands) > 0)
                                            @foreach($terminal_commands as $idx => $cmd)
                                                <div class="flex items-center justify-between bg-[#111119] border border-[#2d2d3a]/40 px-3.5 py-2 rounded-lg hover:border-cyan-500/30 transition-all group">
                                                    <div class="flex items-center gap-2 text-xs">
                                                        <span class="text-cyan-400 font-bold">$</span>
                                                        <span class="text-slate-300">{{ $cmd }}</span>
                                                    </div>
                                                    <button type="button" wire:click="removeCommand({{ $idx }})" class="opacity-0 group-hover:opacity-100 text-slate-500 hover:text-rose-400 transition-all">
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-8 text-slate-600 text-xs italic">No loop animations registered. Console will display placeholder items.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        {{-- Live Console Widget Simulation --}}
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-2">Terminal Shell Mockup</h3>
                            <div class="bg-[#050508] border border-cyan-500/20 p-5 rounded-xl font-mono text-xs text-slate-400 shadow-lg shadow-cyan-900/10 min-h-[160px] relative overflow-hidden flex flex-col justify-between">
                                <div class="absolute top-0 right-0 p-2 text-[8px] uppercase tracking-widest text-cyan-500/30 font-bold">TTY1</div>
                                <div class="space-y-1.5">
                                    <div class="text-slate-500"># Initializing automation pipelines...</div>
                                    <div class="flex items-center gap-1.5 text-cyan-400">
                                        <span class="font-bold">></span>
                                        <span class="text-white font-bold animate-pulse">
                                            @if(count($terminal_commands) > 0)
                                                {{ $terminal_commands[0] }}
                                            @else
                                                echo "No commands"
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-emerald-500/70 font-semibold">[OK] Process spawned successfully.</div>
                                </div>
                                <div class="text-[9px] text-slate-600 mt-4 border-t border-[#2d2d3a] pt-2">The hero landing section cycles through these commands dynamically.</div>
                            </div>
                        </div>

                        <div class="glass-card p-6 flex flex-col justify-between min-h-[180px]">
                            <div>
                                <h4 class="font-bold text-white text-sm">Save Hero details</h4>
                                <p class="text-xs text-slate-400 mt-1">Saves intro text, availability switches, and terminal configurations immediately.</p>
                            </div>
                            <x-filament::button type="submit" color="primary" class="w-full mt-4 glow-cyan-hover">
                                Save Hero & Terminal
                            </x-filament::button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 3: SECTIONS COPY & BUTTONS --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'section_copy')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Tech Stack Section --}}
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-cpu-chip class="w-5 h-5 text-cyan-400" />
                                <span>Tech Stack Header Copy</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Section Title</label>
                                    <input type="text" wire:model.defer="tech_stack_title" class="cyber-input w-full" placeholder="e.g., Tech Stack" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Section Subtitle / Description</label>
                                    <input type="text" wire:model.defer="tech_stack_description" class="cyber-input w-full" placeholder="e.g., Tools I work with daily" required />
                                </div>
                            </div>
                        </div>

                        {{-- Featured Projects Section --}}
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-400" />
                                <span>Featured Projects Header Copy</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Section Title</label>
                                    <input type="text" wire:model.defer="featured_projects_title" class="cyber-input w-full" placeholder="e.g., Featured Projects" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Section Subtitle / Description</label>
                                    <input type="text" wire:model.defer="featured_projects_description" class="cyber-input w-full" placeholder="e.g., Engineering feats I am proud of" required />
                                </div>
                            </div>
                        </div>

                        {{-- Call To Action Section --}}
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-rocket-launch class="w-5 h-5 text-cyan-400" />
                                <span>Call-To-Action (CTA) Bottom Banner Settings</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">CTA Main Title Banner</label>
                                    <input type="text" wire:model.defer="cta_title" class="cyber-input w-full" placeholder="e.g., Let's Build Something Together" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">CTA Primary Button Label</label>
                                    <input type="text" wire:model.defer="cta_primary_button_text" class="cyber-input w-full text-cyan-400 font-semibold" placeholder="e.g., Get In Touch" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">CTA Description Details</label>
                                    <textarea wire:model.defer="cta_description" rows="4" class="cyber-textarea w-full" placeholder="Invite clients to message or collaborate on cloud automations..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">CTA Secondary Button Label</label>
                                    <input type="text" wire:model.defer="cta_secondary_button_text" class="cyber-input w-full text-slate-400" placeholder="e.g., Download CV" required />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        <div class="glass-card p-6 flex flex-col justify-between min-h-[180px]">
                            <div>
                                <h4 class="font-bold text-white text-sm">Save Section Titles</h4>
                                <p class="text-xs text-slate-400 mt-1">Updates homepage headers, project descriptive copies, and landing CTAs immediately.</p>
                            </div>
                            <x-filament::button type="submit" color="primary" class="w-full mt-4 glow-cyan-hover">
                                Save Sections Copy
                            </x-filament::button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 4: LIVE INFRASTRUCTURE --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'infrastructure')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-server class="w-5 h-5 text-cyan-400" />
                                <span>Infrastructure Monitoring Overview</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Monitoring Section Title</label>
                                    <input type="text" wire:model.defer="infrastructure_title" class="cyber-input w-full font-semibold" placeholder="e.g., Live Infrastructure" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Status Message Copy</label>
                                    <input type="text" wire:model.defer="infrastructure_status_text" class="cyber-input w-full text-emerald-400 font-mono text-xs" placeholder="e.g., All cloud services operational" required />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Status Section Description</label>
                                <textarea wire:model.defer="infrastructure_description" rows="3" class="cyber-textarea w-full" placeholder="Write a short summary detailing the deployment architecture, host hypervisors, and cloud security..."></textarea>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-heart class="w-5 h-5 text-cyan-400" />
                                <span>System Health Status</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <label class="flex flex-col items-center p-4 bg-[#111119]/50 border {{ $infrastructure_status === 'operational' ? 'border-emerald-500/40 bg-emerald-950/5' : 'border-slate-800' }} rounded-xl cursor-pointer hover:border-emerald-500/25 transition-all text-center relative overflow-hidden group">
                                    <input type="radio" wire:model="infrastructure_status" value="operational" class="sr-only" />
                                    <span class="w-4 h-4 rounded-full border-2 border-emerald-500/30 flex items-center justify-center shrink-0 mb-3 group-hover:scale-110 transition-transform">
                                        <span class="w-2 h-2 rounded-full {{ $infrastructure_status === 'operational' ? 'bg-emerald-400 animate-pulse' : 'bg-transparent' }}"></span>
                                    </span>
                                    <span class="text-sm font-bold text-emerald-400">Operational</span>
                                    <span class="text-[9px] text-slate-500 mt-1 uppercase">Healthy Stack</span>
                                </label>

                                <label class="flex flex-col items-center p-4 bg-[#111119]/50 border {{ $infrastructure_status === 'degraded' ? 'border-amber-500/40 bg-amber-950/5' : 'border-slate-800' }} rounded-xl cursor-pointer hover:border-amber-500/25 transition-all text-center relative overflow-hidden group">
                                    <input type="radio" wire:model="infrastructure_status" value="degraded" class="sr-only" />
                                    <span class="w-4 h-4 rounded-full border-2 border-amber-500/30 flex items-center justify-center shrink-0 mb-3 group-hover:scale-110 transition-transform">
                                        <span class="w-2 h-2 rounded-full {{ $infrastructure_status === 'degraded' ? 'bg-amber-400 animate-pulse' : 'bg-transparent' }}"></span>
                                    </span>
                                    <span class="text-sm font-bold text-amber-400">Degraded</span>
                                    <span class="text-[9px] text-slate-500 mt-1 uppercase">Minor Anomalies</span>
                                </label>

                                <label class="flex flex-col items-center p-4 bg-[#111119]/50 border {{ $infrastructure_status === 'down' ? 'border-rose-500/40 bg-rose-950/5' : 'border-slate-800' }} rounded-xl cursor-pointer hover:border-rose-500/25 transition-all text-center relative overflow-hidden group">
                                    <input type="radio" wire:model="infrastructure_status" value="down" class="sr-only" />
                                    <span class="w-4 h-4 rounded-full border-2 border-rose-500/30 flex items-center justify-center shrink-0 mb-3 group-hover:scale-110 transition-transform">
                                        <span class="w-2 h-2 rounded-full {{ $infrastructure_status === 'down' ? 'bg-rose-400 animate-pulse' : 'bg-transparent' }}"></span>
                                    </span>
                                    <span class="text-sm font-bold text-rose-400">System Outage</span>
                                    <span class="text-[9px] text-slate-500 mt-1 uppercase">Critical Incident</span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        {{-- Live Status Ring Mockup --}}
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-2">Status Ring</h3>
                            <div class="flex flex-col items-center justify-center py-6">
                                <div class="relative flex items-center justify-center">
                                    {{-- Glowing outer circle --}}
                                    <span class="absolute w-20 h-20 rounded-full border-4 border-dashed {{ $infrastructure_status === 'operational' ? 'border-emerald-500/30 animate-[spin_20s_linear_infinite]' : ($infrastructure_status === 'degraded' ? 'border-amber-500/30 animate-[spin_20s_linear_infinite]' : 'border-rose-500/30 animate-[spin_20s_linear_infinite]') }}"></span>
                                    
                                    {{-- Pulsing ring --}}
                                    <span class="w-16 h-16 rounded-full border-2 {{ $infrastructure_status === 'operational' ? 'border-emerald-500/40 bg-emerald-500/10' : ($infrastructure_status === 'degraded' ? 'border-amber-500/40 bg-amber-500/10' : 'border-rose-500/40 bg-rose-500/10') }} flex items-center justify-center relative shadow-lg">
                                        <span class="absolute inline-flex h-full w-full rounded-full {{ $infrastructure_status === 'operational' ? 'bg-emerald-500/20' : ($infrastructure_status === 'degraded' ? 'bg-amber-500/20' : 'bg-rose-500/20') }} animate-ping opacity-60"></span>
                                        <x-heroicon-o-bolt class="w-8 h-8 {{ $infrastructure_status === 'operational' ? 'text-emerald-400' : ($infrastructure_status === 'degraded' ? 'text-amber-400' : 'text-rose-400') }}" />
                                    </span>
                                </div>
                                <span class="text-xs uppercase font-bold tracking-widest text-slate-400 mt-4">Simulation Status</span>
                                <span class="text-sm font-semibold {{ $infrastructure_status === 'operational' ? 'text-emerald-400' : ($infrastructure_status === 'degraded' ? 'text-amber-400' : 'text-rose-400') }} mt-1 font-mono uppercase">{{ $infrastructure_status }}</span>
                            </div>
                        </div>

                        <div class="glass-card p-6 flex flex-col justify-between min-h-[180px]">
                            <div>
                                <h4 class="font-bold text-white text-sm">Synchronize Health</h4>
                                <p class="text-xs text-slate-400 mt-1">Updates deployment headers, status indicators, and operational metrics immediately.</p>
                            </div>
                            <x-filament::button type="submit" color="primary" class="w-full mt-4 glow-cyan-hover">
                                Synchronize Health status
                            </x-filament::button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 5: LIVE SITE VIEWPORT --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'preview')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="glass-card p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded bg-cyan-950 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                            <x-heroicon-o-eye class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-white">Public Live Homepage Preview</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Interact with the public home page loaded dynamically in a sandbox.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" onclick="document.getElementById('home-iframe').src = document.getElementById('home-iframe').src" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-all">
                            <x-heroicon-o-arrow-path class="w-3.5 h-3.5" />
                            <span>Refresh Preview</span>
                        </button>
                    </div>
                </div>

                <div class="preview-iframe-wrapper bg-[#0e0e13] border border-[#2d2d3a] rounded-xl overflow-hidden shadow-2xl relative">
                    <div class="flex items-center justify-between bg-[#12121a] px-4 py-2.5 border-b border-[#2d2d3a]">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/60"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/60"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/60"></span>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">PORTFOLIO HOMEPAGE VIEWPORT</span>
                        <span class="text-xs text-slate-500 font-mono">LIVE PREVIEW</span>
                    </div>

                    <iframe id="home-iframe" src="/" class="w-full border-none bg-slate-900" style="min-height: 800px; height: 800px;" title="Homepage Live preview"></iframe>
                </div>
            </div>
        @endif

    </div>

    {{-- Neon Cyberpunk Workspace CSS Stylings --}}
    <style>
        /* Pulse dot for workspace title */
        .pulse-dot-settings {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #00d4ff;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.8);
            animation: pulse-glowing-settings 2s infinite;
        }

        @keyframes pulse-glowing-settings {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(0, 212, 255, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 212, 255, 0);
            }
        }

        /* Workspace Tab Button styles */
        .workspace-tabs-container {
            border-bottom: 2px solid rgba(45, 45, 58, 0.4);
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .workspace-tabs {
            background: rgba(13, 13, 20, 0.6);
            padding: 6px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.8);
            display: flex;
            gap: 6px;
            width: max-content;
            max-width: 100%;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            position: relative;
            outline: none;
        }

        .tab-btn:hover {
            color: #e2e8f0;
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.05);
            transform: translateY(-1px);
        }

        .tab-btn:active {
            transform: scale(0.96);
        }

        .tab-btn.active {
            color: #00d4ff;
            background: rgba(6, 182, 212, 0.08);
            border: 1px solid rgba(6, 182, 212, 0.35);
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.15), inset 0 0 8px rgba(6, 182, 212, 0.05);
            text-shadow: 0 0 10px rgba(6, 182, 212, 0.3);
        }

        /* Active tab indicator dot */
        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background-color: #00d4ff;
            box-shadow: 0 0 10px #00d4ff;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(26, 26, 36, 0.7) !important;
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid #2d2d3a !important;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: #3b82f6/40 !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 0 20px rgba(59, 130, 246, 0.05);
        }

        /* Cyberpunk Form Elements */
        .cyber-input {
            background: rgba(18, 18, 26, 0.8) !important;
            border: 1px solid #2d2d3a !important;
            border-radius: 10px;
            color: #f1f5f9 !important;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .cyber-input:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1), 0 0 15px rgba(0, 212, 255, 0.25) !important;
            outline: none;
        }

        .cyber-textarea {
            background: rgba(18, 18, 26, 0.8) !important;
            border: 1px solid #2d2d3a !important;
            border-radius: 10px;
            color: #f1f5f9 !important;
            padding: 12px 14px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            resize: vertical;
        }

        .cyber-textarea:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1), 0 0 15px rgba(0, 212, 255, 0.25) !important;
            outline: none;
        }

        .cyber-select {
            background: rgba(18, 18, 26, 0.8) !important;
            border: 1px solid #2d2d3a !important;
            border-radius: 10px;
            color: #f1f5f9 !important;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .cyber-select:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1), 0 0 15px rgba(0, 212, 255, 0.25) !important;
            outline: none;
        }
    </style>
</x-filament-panels::page>
