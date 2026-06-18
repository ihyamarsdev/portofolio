<x-filament-panels::page>
    @vite('resources/css/app.css')

    {{-- SVG Gradient Definitions for Circular Progress & Neon Borders --}}
    <svg style="position: absolute; width: 0; height: 0">
        <defs>
            <linearGradient id="atsGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#0891b2;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="formatGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="aboutmeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#8b5cf6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#6d28d9;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="jobmatchGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#f59e0b;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#b45309;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>

    <div class="cv-workspace-container space-y-8 font-space-grotesk">
        
        {{-- Unified Header --}}
        <div class="glass-card premium-workspace-header p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="pulse-dot"></span>
                    <h2 class="text-2xl font-bold text-white tracking-wide">CV Workspace</h2>
                </div>
                <p class="text-sm text-slate-400 mt-1">Manage, design, preview, and optimize your professional CV with AI</p>
            </div>
            
            <div class="flex items-center gap-3">
                <x-filament::button wire:click="analyze" color="primary" :loading="$isAnalyzing" class="btn-workspace-action glow-cyan-hover">
                    <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                    <span>Run AI Analysis</span>
                </x-filament::button>

                <x-filament::button wire:click="downloadCv" color="success" class="btn-workspace-action glow-green-hover">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-1.5" />
                    <span>Download PDF</span>
                </x-filament::button>
            </div>
        </div>

        {{-- Glowing Cyberpunk Tab Navigation --}}
        <div class="workspace-tabs-container">
            <div class="workspace-tabs flex overflow-x-auto gap-2 pb-2 scrollbar-thin">
                <button wire:click="switchTab('overview')" class="tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}">
                    <x-heroicon-o-chart-bar class="w-4.5 h-4.5" />
                    <span>Dashboard</span>
                </button>
                <button wire:click="switchTab('profile')" class="tab-btn {{ $activeTab === 'profile' ? 'active' : '' }}">
                    <x-heroicon-o-user-circle class="w-4.5 h-4.5" />
                    <span>Personal Info</span>
                </button>
                <button wire:click="switchTab('experience')" class="tab-btn {{ $activeTab === 'experience' ? 'active' : '' }}">
                    <x-heroicon-o-briefcase class="w-4.5 h-4.5" />
                    <span>Experience</span>
                </button>
                <button wire:click="switchTab('education')" class="tab-btn {{ $activeTab === 'education' ? 'active' : '' }}">
                    <x-heroicon-o-academic-cap class="w-4.5 h-4.5" />
                    <span>Education</span>
                </button>
                <button wire:click="switchTab('skills')" class="tab-btn {{ $activeTab === 'skills' ? 'active' : '' }}">
                    <x-heroicon-o-bolt class="w-4.5 h-4.5" />
                    <span>Skills</span>
                </button>
                <button wire:click="switchTab('certificates')" class="tab-btn {{ $activeTab === 'certificates' ? 'active' : '' }}">
                    <x-heroicon-o-trophy class="w-4.5 h-4.5" />
                    <span>Certificates</span>
                </button>
                <button wire:click="switchTab('aimatch')" class="tab-btn {{ $activeTab === 'aimatch' ? 'active' : '' }}">
                    <x-heroicon-o-sparkles class="w-4.5 h-4.5" />
                    <span>AI Job Match</span>
                </button>
                <button wire:click="switchTab('preview')" class="tab-btn {{ $activeTab === 'preview' ? 'active' : '' }}">
                    <x-heroicon-o-eye class="w-4.5 h-4.5" />
                    <span>Live Preview</span>
                </button>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB 1: DASHBOARD OVERVIEW --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'overview')
            <div class="tab-pane-content space-y-8 animate-fade-in">
                
                {{-- Score Meters --}}
                @if($analysisResult)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-cyan-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * $analysisResult['ats_score']) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#atsGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-white font-bold text-xl font-mono">{{ $analysisResult['ats_score'] }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-400 font-bold uppercase">ATS Score</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-blue-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * $analysisResult['format_score']) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#formatGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-white font-bold text-xl font-mono">{{ $analysisResult['format_score'] }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-400 font-bold uppercase">Format Score</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-purple-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($analysisResult['about_me_score'] ?? 0)) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#aboutmeGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-white font-bold text-xl font-mono">{{ $analysisResult['about_me_score'] ?? 0 }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-400 font-bold uppercase">About Me Score</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-amber-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($analysisResult['job_match_score'] ?? 0)) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#jobmatchGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-white font-bold text-xl font-mono">{{ $analysisResult['job_match_score'] ?? 0 }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-400 font-bold uppercase">Job Match Score</span>
                        </div>
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-chart-pie class="w-12 h-12 text-slate-500 mx-auto mb-3" />
                        <h4 class="text-lg font-bold text-white">No CV Analysis Executed Yet</h4>
                        <p class="text-sm text-slate-400 mt-1 max-w-md mx-auto">Fill in your professional profile and run the AI analyzer to generate your resume scoring trends and recommendations.</p>
                        <x-filament::button wire:click="switchTab('profile')" color="primary" class="mt-4">
                            Start Building CV
                        </x-filament::button>
                    </div>
                @endif

                {{-- Visual Trend Charts --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass-card p-5">
                        <livewire:is :key="'ats-score-chart'" :component="'App\\Filament\\Widgets\\ATSScoreChartWidget'" />
                    </div>
                    <div class="glass-card p-5">
                        <livewire:is :key="'job-match-chart'" :component="'App\\Filament\\Widgets\\JobMatchChartWidget'" />
                    </div>
                </div>

                {{-- History Table --}}
                @if($history && count(is_countable($history) ? $history : []) > 0)
                    <div class="glass-card p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <x-heroicon-o-clock class="w-5 h-5 text-cyan-400" />
                                <span>Historical Runs</span>
                            </h3>
                            <button wire:click="switchTab('aimatch')" type="button" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-slate-600 rounded-lg text-xs font-semibold text-slate-200 transition-colors inline-flex items-center gap-1">
                                <x-heroicon-o-sparkles class="w-3.5 h-3.5" />
                                <span>Open AI Job Match</span>
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-300">
                                <thead class="text-xs uppercase text-slate-500 border-b border-[#2d2d3a]">
                                    <tr>
                                        <th class="py-3 px-4">Date</th>
                                        <th class="py-3 px-4">ATS</th>
                                        <th class="py-3 px-4">Format</th>
                                        <th class="py-3 px-4">About Me</th>
                                        <th class="py-3 px-4">Job Match</th>
                                        <th class="py-3 px-4">Target Role</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#1e1e2d]">
                                    @foreach($history as $item)
                                        <tr class="hover:bg-slate-800/20 group">
                                            <td class="py-3 px-4 text-white font-medium">{{ $item->created_at->format('M j, Y H:i') }}</td>
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
                                                    <button type="button" wire:click="loadAnalysisIntoWorkspace({{ $item->id }})" class="p-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded hover:text-white inline-flex" title="Load Insights Into Workspace">
                                                        <x-heroicon-o-eye class="w-4 h-4" />
                                                    </button>
                                                    <button wire:click="deleteAnalysis({{ $item->id }})" wire:confirm="Are you sure you want to delete this analysis run?" class="p-1 bg-slate-800 hover:bg-rose-950 text-slate-400 rounded hover:text-rose-400 inline-flex" title="Delete Run">
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
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 2: PERSONAL PROFILE --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'profile')
            <div class="tab-pane-content animate-fade-in">
                <form wire:submit.prevent="saveProfile" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-user class="w-5 h-5 text-cyan-400" />
                                <span>Basic Biography</span>
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                                <input type="text" wire:model.defer="profile_name" class="cyber-input w-full" placeholder="John Doe" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Professional Title</label>
                                <input type="text" wire:model.defer="profile_title" class="cyber-input w-full" placeholder="Senior Cloud & DevOps Architect" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Biography / Professional Summary</label>
                                <textarea wire:model.defer="profile_biography" rows="6" class="cyber-textarea w-full" placeholder="Write a professional and concise summary introducing your core capabilities..."></textarea>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-envelope class="w-5 h-5 text-cyan-400" />
                                <span>Contact Details</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                                    <input type="email" wire:model.defer="profile_email" class="cyber-input w-full" placeholder="john@example.com" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Phone Number</label>
                                    <input type="text" wire:model.defer="profile_phone" class="cyber-input w-full" placeholder="+62 812 3456 789" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Location / City</label>
                                    <input type="text" wire:model.defer="profile_location" class="cyber-input w-full" placeholder="Jakarta, Indonesia" />
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-link class="w-5 h-5 text-cyan-400" />
                                <span>Social & Web Links</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">GitHub URL</label>
                                    <input type="text" wire:model.defer="profile_github_url" class="cyber-input w-full" placeholder="https://github.com/username" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">LinkedIn Profile URL</label>
                                    <input type="text" wire:model.defer="profile_linkedin_url" class="cyber-input w-full" placeholder="https://linkedin.com/in/username" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Twitter / X URL</label>
                                    <input type="text" wire:model.defer="profile_twitter_url" class="cyber-input w-full" placeholder="https://x.com/username" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Personal Website URL</label>
                                    <input type="text" wire:model.defer="profile_website_url" class="cyber-input w-full" placeholder="https://yourwebsite.com" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="glass-card p-6 space-y-5">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3">Avatar Profile</h3>
                            
                            <div class="flex flex-col items-center text-center">
                                @if($profile_avatar)
                                    <img src="{{ $profile_avatar->temporaryUrl() }}" class="w-32 h-32 rounded-full object-cover border-2 border-cyan-500/50 shadow-lg glow-cyan shadow-cyan-500/20" />
                                @elseif($profile_avatar_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($profile_avatar_path) }}" class="w-32 h-32 rounded-full object-cover border-2 border-cyan-500/30" />

                                @else
                                    <div class="w-32 h-32 rounded-full bg-slate-800 border-2 border-slate-700 flex items-center justify-center text-slate-500">
                                        <x-heroicon-o-user class="w-16 h-16" />
                                    </div>
                                @endif

                                <div class="mt-4 w-full">
                                    <input type="file" wire:model="profile_avatar" id="avatar" class="hidden" accept="image/*" />
                                    <label for="avatar" class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 rounded-lg text-sm w-full transition-all">
                                        <x-heroicon-o-arrow-up-tray class="w-4 h-4 mr-1.5" />
                                        Upload Avatar
                                    </label>
                                    <div wire:loading wire:target="profile_avatar" class="text-xs text-cyan-400 mt-2">Uploading file...</div>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-white border-b border-[#2d2d3a] pb-3">Design Config</h3>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">CV Output Template</label>
                                <select wire:model="selectedTemplate" class="cyber-select w-full">
                                    @foreach(config('cv-templates', []) as $key => $template)
                                        <option value="{{ $key }}">{{ $template['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <x-filament::button type="submit" size="lg" color="primary" class="w-full shadow-lg glow-cyan shadow-cyan-500/20">
                                <x-heroicon-o-check class="w-5 h-5 mr-1.5" />
                                Save Profile Data
                            </x-filament::button>
                        </div>
                    </div>

                </form>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 3: WORK EXPERIENCE --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'experience')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-400" />
                        <span>Work History Timeline</span>
                    </h3>
                    <x-filament::button wire:click="openExperienceModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        Add Experience
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
                                        <h4 class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $exp->position }}</h4>
                                        <p class="text-sm font-semibold text-slate-300 mt-0.5">{{ $exp->company }} <span class="text-slate-500">•</span> <span class="text-slate-400 text-xs font-normal font-mono">{{ $exp->employment_type }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-400 font-mono bg-slate-800/50 border border-slate-700 px-2 py-0.5 rounded">
                                            {{ $exp->start_date->format('M Y') }} - {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                                        </span>
                                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="p-1.5 text-slate-500 cursor-grab active:cursor-grabbing hover:text-cyan-400" title="Drag to reorder">
                                                <x-heroicon-o-bars-3 class="w-4 h-4" />
                                            </div>
                                            <button wire:click="openExperienceModal({{ $exp->id }})" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded hover:text-white" title="Edit">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </button>
                                            <button wire:click="deleteExperience({{ $exp->id }})" wire:confirm="Are you sure you want to delete this experience?" class="p-1.5 bg-slate-800 hover:bg-rose-950 text-slate-400 rounded hover:text-rose-400" title="Delete">
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
                        <p class="text-sm text-slate-400">No work experiences listed yet. Add your employment history to populate your resume.</p>
                        <x-filament::button wire:click="openExperienceModal()" color="primary" class="mt-4" size="sm">
                            Add First Experience
                        </x-filament::button>
                    </div>
                @endif
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 4: EDUCATION --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'education')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-academic-cap class="w-5 h-5 text-cyan-400" />
                        <span>Academic Timeline</span>
                    </h3>
                    <x-filament::button wire:click="openEducationModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        Add Education
                    </x-filament::button>
                </div>

                @if($educations && count(is_countable($educations) ? $educations : []) > 0)
                    <div class="space-y-4 relative before:absolute before:left-6 before:top-4 before:bottom-4 before:w-[2px] before:bg-[#2d2d3a]">
                        @foreach($educations as $edu)
                            <div class="glass-card p-5 relative pl-14 hover:border-cyan-500/30 transition-all group">
                                <div class="absolute left-[18px] top-6 w-3 h-3 rounded-full bg-[#2d2d3a] border border-[#12121a] group-hover:bg-cyan-400 group-hover:shadow-[0_0_8px_#06b6d4] transition-all"></div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                    <div>
                                        <h4 class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $edu->degree }} in {{ $edu->field_of_study }}</h4>
                                        <p class="text-sm font-semibold text-slate-300 mt-0.5">{{ $edu->institution }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-400 font-mono bg-slate-800/50 border border-slate-700 px-2 py-0.5 rounded">
                                            {{ $edu->start_date->format('Y') }} - {{ $edu->end_date ? $edu->end_date->format('Y') : 'Present' }}
                                        </span>
                                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="openEducationModal({{ $edu->id }})" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded hover:text-white" title="Edit">
                                                <x-heroicon-o-pencil-square class="w-4 h-4" />
                                            </button>
                                            <button wire:click="deleteEducation({{ $edu->id }})" wire:confirm="Are you sure you want to delete this education?" class="p-1.5 bg-slate-800 hover:bg-rose-950 text-slate-400 rounded hover:text-rose-400" title="Delete">
                                                <x-heroicon-o-trash class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 text-sm text-slate-400 flex flex-col md:flex-row gap-4">
                                    @if($edu->grade)
                                        <div>
                                            <span class="font-bold text-slate-300">Grade / GPA:</span>
                                            <span class="text-cyan-400 font-mono">{{ $edu->grade }}</span>
                                        </div>
                                    @endif
                                    @if($edu->activities)
                                        <div class="flex-1">
                                            <span class="font-bold text-slate-300 block mb-0.5">Activities:</span>
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
                        <p class="text-sm text-slate-400">No education entries listed yet. Add your academic qualifications.</p>
                        <x-filament::button wire:click="openEducationModal()" color="primary" class="mt-4" size="sm">
                            Add First Qualification
                        </x-filament::button>
                    </div>
                @endif
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 5: PROFESSIONAL SKILLS --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'skills')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5 text-cyan-400" />
                        <span>Core Capabilities & Tooling</span>
                    </h3>
                    <x-filament::button wire:click="openSkillModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        Add Skill
                    </x-filament::button>
                </div>

                @if($skills && count(is_countable($skills) ? $skills : []) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($skills->groupBy('category_id') as $catId => $categorySkills)
                            @php
                                $categoryName = $categorySkills->first()?->category?->name ?? 'Uncategorized';
                            @endphp
                            <div class="glass-card p-5 space-y-4">
                                <h4 class="text-md font-bold text-white flex items-center gap-2 border-b border-[#2d2d3a]/50 pb-2">
                                    <span class="w-1.5 h-4 bg-cyan-400 rounded-sm"></span>
                                    <span>{{ $categoryName }}</span>
                                </h4>

                                <div class="space-y-3.5">
                                    @foreach($categorySkills as $skill)
                                        <div class="group relative pr-16">
                                            <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                                <span class="text-slate-200 group-hover:text-cyan-400 transition-colors">{{ $skill->name }}</span>
                                                <span class="text-slate-400 font-mono">{{ $skill->years_experience }} yrs exp <span class="text-slate-600">|</span> {{ $skill->proficiency }}%</span>
                                            </div>
                                            <div class="w-full bg-[#1e1e2d] h-2 rounded-full overflow-hidden border border-[#2d2d3a]/40">
                                                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full" style="width: {{ $skill->proficiency }}%"></div>
                                            </div>
                                            
                                            <div class="absolute right-0 top-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button wire:click="openSkillModal({{ $skill->id }})" class="p-1 bg-slate-800 text-slate-400 hover:text-white rounded" title="Edit">
                                                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                                </button>
                                                <button wire:click="deleteSkill({{ $skill->id }})" wire:confirm="Are you sure?" class="p-1 bg-slate-800 text-slate-400 hover:text-rose-400 rounded" title="Delete">
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
                        <p class="text-sm text-slate-400">No skills registered yet. Start adding skills like Linux, AWS, Docker, Kubernetes.</p>
                        <x-filament::button wire:click="openSkillModal()" color="primary" class="mt-4" size="sm">
                            Add First Skill
                        </x-filament::button>
                    </div>
                @endif
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 6: CERTIFICATES --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'certificates')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-trophy class="w-5 h-5 text-cyan-400" />
                        <span>Professional Certifications</span>
                    </h3>
                    <x-filament::button wire:click="openCertificateModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        Add Certificate
                    </x-filament::button>
                </div>

                @if($certificates && count(is_countable($certificates) ? $certificates : []) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($certificates as $cert)
                            <div class="glass-card p-5 hover:border-cyan-500/30 transition-all group flex items-start justify-between gap-3">
                                <div class="flex gap-4">
                                    <div class="w-12 h-12 rounded bg-slate-800 border border-slate-700 flex items-center justify-center text-amber-400 flex-shrink-0">
                                        <x-heroicon-o-academic-cap class="w-7 h-7" />
                                    </div>
                                    <div>
                                        <h4 class="text-md font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $cert->name }}</h4>
                                        <p class="text-xs font-semibold text-slate-300 mt-0.5">{{ $cert->issuer }}</p>
                                        
                                        @if($cert->credential_id)
                                            <p class="text-[10px] text-slate-500 mt-1 font-mono">ID: {{ $cert->credential_id }}</p>
                                        @endif

                                        <div class="flex gap-3 text-[10px] text-slate-400 mt-2 font-mono">
                                            <span>Issued: {{ $cert->issue_date->format('M Y') }}</span>
                                            @if($cert->expiry_date)
                                                <span>Expires: {{ $cert->expiry_date->format('M Y') }}</span>
                                            @else
                                                <span>No Expiry</span>
                                            @endif
                                        </div>

                                        @if($cert->credential_url)
                                            <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener" class="text-[10px] text-cyan-400 hover:underline inline-flex items-center gap-0.5 mt-2 font-semibold">
                                                <span>Verify Credential</span>
                                                <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                    <button wire:click="openCertificateModal({{ $cert->id }})" class="p-1 bg-slate-800 text-slate-400 hover:text-white rounded" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </button>
                                    <button wire:click="deleteCertificate({{ $cert->id }})" wire:confirm="Are you sure?" class="p-1 bg-slate-800 text-slate-400 hover:text-rose-400 rounded" title="Delete">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-trophy class="w-12 h-12 text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-400">No professional certificates listed yet. Add AWS, CKA, or Cisco credentials here.</p>
                        <x-filament::button wire:click="openCertificateModal()" color="primary" class="mt-4" size="sm">
                            Add First Certificate
                        </x-filament::button>
                    </div>
                @endif
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 7: AI JOB MATCH & ANALYSIS --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'aimatch')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Input panel --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="glass-card p-5 space-y-4">
                            <h3 class="text-md font-bold text-white border-b border-[#2d2d3a] pb-2 flex items-center gap-2">
                                <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-400" />
                                <span>Paste Target Role Info</span>
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Target Role</label>
                                <select wire:model.live="targetRole" class="cyber-input w-full text-xs font-semibold bg-[#12121a] text-slate-200 border border-[#2d2d3a] rounded-lg p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500/20">
                                    <option value="Devops Engineer">DevOps Engineer</option>
                                    <option value="Cloud Engineer">Cloud Engineer</option>
                                    <option value="Site Reliability Engineer">Site Reliability Engineer</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Job Description (Optional)</label>
                                <textarea wire:model="jobDescription" rows="8" class="cyber-textarea w-full text-xs font-mono" placeholder="Paste the target job requirements or description details here to perform deep-match analysis..."></textarea>
                            </div>

                            <x-filament::button wire:click="analyzeJobMatch" color="primary" class="w-full shadow-lg glow-cyan shadow-cyan-500/20" :loading="$isAnalyzing">
                                <x-heroicon-o-bolt class="w-4 h-4 mr-1.5" />
                                Run AI Analysis Match
                            </x-filament::button>
                        </div>

                        <x-cv-analysis-console :targetRole="$targetRole" :jobDescription="$jobDescription" />
                    </div>

                    {{-- Output panel --}}
                    <div class="lg:col-span-2">
                        @if(!$isAnalyzing && $analysisResult)
                            <div class="glass-card p-6 space-y-6">
                                <div class="flex items-center justify-between border-b border-[#2d2d3a] pb-4">
                                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                        <x-heroicon-o-document-text class="w-5 h-5 text-cyan-400" />
                                        <span>AI Fitment Insights</span>
                                    </h3>
                                    @if(!empty($analysisResult['job_title_targeted']))
                                        <span class="text-xs bg-cyan-950/40 text-cyan-400 border border-cyan-500/20 px-3 py-1 rounded-full font-bold">
                                            Role: {{ $analysisResult['job_title_targeted'] }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Scores --}}
                                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                                    @php
                                        $scoreCards = [
                                            ['label' => 'ATS Score', 'value' => $analysisResult['ats_score'] ?? '-', 'color' => 'text-cyan-400'],
                                            ['label' => 'Format', 'value' => $analysisResult['format_score'] ?? '-', 'color' => 'text-blue-400'],
                                            ['label' => 'About Me', 'value' => $analysisResult['about_me_score'] ?? '-', 'color' => 'text-purple-400'],
                                            ['label' => 'Impact', 'value' => $analysisResult['impact_score'] ?? '-', 'color' => 'text-emerald-400'],
                                            ['label' => 'Role Align', 'value' => $analysisResult['role_alignment_score'] ?? '-', 'color' => 'text-fuchsia-400'],
                                            ['label' => 'Security', 'value' => $analysisResult['security_score'] ?? '-', 'color' => 'text-rose-400'],
                                            ['label' => 'Reliability', 'value' => $analysisResult['reliability_score'] ?? '-', 'color' => 'text-orange-400'],
                                            ['label' => 'Cloud', 'value' => $analysisResult['cloud_score'] ?? '-', 'color' => 'text-sky-400'],
                                            ['label' => 'Job Match', 'value' => $analysisResult['job_match_score'] ?? '-', 'color' => 'text-amber-400'],
                                        ];
                                    @endphp

                                    @foreach($scoreCards as $card)
                                        <div class="bg-[#1e1e2d]/60 border border-[#2d2d3a]/50 p-4 rounded-xl text-center">
                                            <p class="text-2xl font-bold font-mono {{ $card['color'] }}">{{ $card['value'] }}%</p>
                                            <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">{{ $card['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if(!empty($analysisResult['target_seniority']))
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span class="bg-fuchsia-950/30 text-fuchsia-300 border border-fuchsia-500/20 px-3 py-1 rounded-full font-semibold">
                                            Seniority: {{ $analysisResult['target_seniority'] }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Industry match --}}
                                @if(isset($analysisResult['industry_match']))
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-bold text-slate-200">Domain Match Details</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            @foreach((is_array($analysisResult['industry_match'] ?? null) ? $analysisResult['industry_match'] : []) as $domain => $score)
                                                <div class="bg-[#12121a]/60 border border-[#2d2d3a]/30 p-3 rounded-lg flex items-center justify-between">
                                                    <span class="text-xs text-slate-400 font-semibold">{{ strtoupper($domain) }}</span>
                                                    <span class="text-xs font-mono font-bold text-cyan-400">{{ $score }}%</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Keywords --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-emerald-950/10 border border-emerald-500/20 p-4 rounded-xl">
                                        <h5 class="text-xs font-bold text-emerald-400 uppercase tracking-wide flex items-center gap-1.5 mb-2.5">
                                            <x-heroicon-o-check-circle class="w-4 h-4" />
                                            <span>Keywords Found ({{ count(is_countable($analysisResult['keywords']['found'] ?? null) ? $analysisResult['keywords']['found'] : []) }})</span>
                                        </h5>
                                        <div class="flex flex-wrap gap-1">
                                            @forelse((is_array($analysisResult['keywords']['found'] ?? null) ? $analysisResult['keywords']['found'] : []) as $kw)
                                                <span class="text-[10px] bg-emerald-950/30 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded">{{ $kw }}</span>
                                            @empty
                                                <span class="text-[10px] text-slate-500">None detected</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="bg-amber-950/10 border border-amber-500/20 p-4 rounded-xl">
                                        <h5 class="text-xs font-bold text-amber-400 uppercase tracking-wide flex items-center gap-1.5 mb-2.5">
                                            <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                                            <span>Weak Evidence ({{ count(is_countable($analysisResult['keywords']['weak_evidence'] ?? null) ? $analysisResult['keywords']['weak_evidence'] : []) }})</span>
                                        </h5>
                                        <div class="flex flex-wrap gap-1">
                                            @forelse((is_array($analysisResult['keywords']['weak_evidence'] ?? null) ? $analysisResult['keywords']['weak_evidence'] : []) as $kw)
                                                <span class="text-[10px] bg-amber-950/30 text-amber-300 border border-amber-500/20 px-2 py-0.5 rounded">{{ $kw }}</span>
                                            @empty
                                                <span class="text-[10px] text-slate-500">No weak-evidence flags.</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="bg-rose-950/10 border border-rose-500/20 p-4 rounded-xl">
                                        <h5 class="text-xs font-bold text-rose-400 uppercase tracking-wide flex items-center gap-1.5 mb-2.5">
                                            <x-heroicon-o-x-circle class="w-4 h-4" />
                                            <span>Missing Keywords ({{ count(is_countable($analysisResult['keywords']['missing'] ?? null) ? $analysisResult['keywords']['missing'] : []) }})</span>
                                        </h5>
                                        <div class="flex flex-wrap gap-1">
                                            @forelse((is_array($analysisResult['keywords']['missing'] ?? null) ? $analysisResult['keywords']['missing'] : []) as $kw)
                                                <span class="text-[10px] bg-rose-950/30 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded">{{ $kw }}</span>
                                            @empty
                                                <span class="text-[10px] text-slate-500">Perfect keyword coverage!</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                {{-- Recommendations --}}
                                @if(isset($analysisResult['suggestions']) && count(is_countable($analysisResult['suggestions']) ? $analysisResult['suggestions'] : []) > 0)
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-bold text-slate-200">Recommended Optimization Steps</h4>
                                        <div class="space-y-2">
                                            @foreach((is_array($analysisResult['suggestions'] ?? null) ? $analysisResult['suggestions'] : []) as $idx => $suggestion)
                                                <div class="bg-[#12121a]/80 border border-[#2d2d3a]/50 p-3 rounded-lg flex items-start gap-3">
                                                    <span class="w-5 h-5 rounded-full bg-cyan-950 text-cyan-400 border border-cyan-500/30 text-xs flex items-center justify-center flex-shrink-0 font-bold font-mono">{{ $idx + 1 }}</span>
                                                    <p class="text-xs text-slate-300 leading-relaxed">{{ $suggestion }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                                    @if(!empty($analysisResult['strengths']) && is_array($analysisResult['strengths']))
                                        <div class="bg-emerald-950/10 border border-emerald-500/20 p-4 rounded-xl space-y-2">
                                            <h4 class="text-sm font-bold text-emerald-300">Professional Strengths</h4>
                                            <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                                @foreach((is_array($analysisResult['strengths'] ?? null) ? $analysisResult['strengths'] : []) as $strength)
                                                    <li class="flex gap-2"><span class="text-emerald-400">•</span><span>{{ $strength }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($analysisResult['critical_gaps']) && is_array($analysisResult['critical_gaps']))
                                        <div class="bg-rose-950/10 border border-rose-500/20 p-4 rounded-xl space-y-2">
                                            <h4 class="text-sm font-bold text-rose-300">Critical Gaps</h4>
                                            <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                                @foreach((is_array($analysisResult['critical_gaps'] ?? null) ? $analysisResult['critical_gaps'] : []) as $gap)
                                                    <li class="flex gap-2"><span class="text-rose-400">•</span><span>{{ $gap }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($analysisResult['missing_metrics']) && is_array($analysisResult['missing_metrics']))
                                        <div class="bg-amber-950/10 border border-amber-500/20 p-4 rounded-xl space-y-2">
                                            <h4 class="text-sm font-bold text-amber-300">Missing Metrics</h4>
                                            <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                                @foreach((is_array($analysisResult['missing_metrics'] ?? null) ? $analysisResult['missing_metrics'] : []) as $metric)
                                                    <li class="flex gap-2"><span class="text-amber-400">•</span><span>{{ $metric }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($analysisResult['rewrite_priorities']) && is_array($analysisResult['rewrite_priorities']))
                                        <div class="bg-sky-950/10 border border-sky-500/20 p-4 rounded-xl space-y-2">
                                            <h4 class="text-sm font-bold text-sky-300">Rewrite Priorities</h4>
                                            <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                                @foreach((is_array($analysisResult['rewrite_priorities'] ?? null) ? $analysisResult['rewrite_priorities'] : []) as $priority)
                                                    <li class="flex gap-2"><span class="text-sky-400">•</span><span>{{ $priority }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                @if(!empty($analysisResult['experience_review']) && is_array($analysisResult['experience_review']))
                                    <div class="space-y-3">
                                        <h4 class="text-sm font-bold text-slate-200">Experience Evidence Review</h4>
                                        <div class="space-y-3">
                                            @foreach((is_array($analysisResult['experience_review'] ?? null) ? $analysisResult['experience_review'] : []) as $review)
                                                <div class="bg-[#12121a]/80 border border-[#2d2d3a]/50 p-4 rounded-xl space-y-2">
                                                    <div class="text-sm font-semibold text-white">{{ $review['section'] ?? 'Experience' }}</div>
                                                    <div class="text-xs text-emerald-300">Strength: {{ $review['strength'] ?? '-' }}</div>
                                                    <div class="text-xs text-rose-300">Gap: {{ $review['gap'] ?? '-' }}</div>
                                                    <div class="text-xs text-sky-300">Rewrite Hint: {{ $review['rewrite_hint'] ?? '-' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Summary --}}
                                @if(!empty($analysisResult['summary']))
                                    <div class="bg-[#12121a]/40 border border-[#2d2d3a]/30 p-4 rounded-xl">
                                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Executive Fitment Summary</h4>
                                        <p class="text-xs text-slate-300 leading-relaxed font-sans">{{ $analysisResult['summary'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @elseif(!$isAnalyzing && !$analysisResult)
                            <div class="glass-card p-12 text-center">
                                <x-heroicon-o-sparkles class="w-12 h-12 text-slate-600 mx-auto mb-3" />
                                <h4 class="text-md font-bold text-white">Interactive AI Insights Ready</h4>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">Paste a job description on the left panel, and click "Run AI Analysis" to review direct scoring results, keywords audit, and action items inline.</p>
                            </div>
                        @else
                            {{-- Skeleton Loader during analysis --}}
                            <div class="glass-card p-6 space-y-6 animate-pulse">
                                <div class="flex items-center justify-between border-b border-[#2d2d3a] pb-4">
                                    <div class="h-6 w-48 bg-slate-800 rounded"></div>
                                    <div class="h-6 w-24 bg-slate-800 rounded-full"></div>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                                    @for($i=0; $i<5; $i++)
                                        <div class="bg-[#1e1e2d]/40 border border-[#2d2d3a]/30 p-4 rounded-xl text-center space-y-2">
                                            <div class="h-8 w-12 bg-slate-800 rounded mx-auto"></div>
                                            <div class="h-3 w-16 bg-slate-800 rounded mx-auto"></div>
                                        </div>
                                    @endfor
                                </div>
                                <div class="space-y-3 pt-4 border-t border-[#2d2d3a]/50">
                                    <div class="h-4 w-1/4 bg-slate-800 rounded"></div>
                                    <div class="h-3 w-full bg-slate-800/60 rounded"></div>
                                    <div class="h-3 w-5/6 bg-slate-800/60 rounded"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- TAB 8: LIVE PREVIEW & EXPORT --}}
        {{-- ========================================================================= --}}
        @if($activeTab === 'preview')
            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="glass-card p-5 space-y-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-cyan-950 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                                <x-heroicon-o-eye class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-md font-bold text-white">Real-time Canvas Sync</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Template: <span class="text-cyan-400 font-bold uppercase">{{ $selectedTemplate }}</span></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('cv.preview') }}?template={{ $selectedTemplate }}" target="_blank" rel="noopener" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-all">
                                <span>Open Full Canvas</span>
                                <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" />
                            </a>
                            <x-filament::button wire:click="downloadCv" color="success" size="sm" class="glow-green-hover">
                                <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-1.5" />
                                <span>Download PDF</span>
                            </x-filament::button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        @foreach(config('cv-templates', []) as $key => $template)
                            <button type="button" wire:click="selectTemplate('{{ $key }}')" class="text-left rounded-xl border p-4 transition-all {{ $selectedTemplate === $key ? 'border-cyan-400 bg-cyan-950/30 shadow-lg shadow-cyan-500/10' : 'border-slate-700 bg-slate-900/40 hover:border-slate-500 hover:bg-slate-800/60' }}">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-bold {{ $selectedTemplate === $key ? 'text-cyan-300' : 'text-white' }}">{{ $template['name'] }}</span>
                                    @if($selectedTemplate === $key)
                                        <x-heroicon-o-check-circle class="w-5 h-5 text-cyan-400 shrink-0" />
                                    @endif
                                </div>
                                <p class="mt-2 text-xs leading-5 text-slate-400">{{ $template['description'] ?? 'CV template' }}</p>
                                <span class="mt-3 inline-flex rounded-md bg-slate-950/60 px-2 py-1 text-[10px] font-mono uppercase text-slate-500">{{ $key }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="preview-iframe-wrapper bg-[#0e0e13] border border-[#2d2d3a] rounded-xl overflow-hidden shadow-2xl relative">
                    <div class="flex items-center justify-between bg-[#12121a] px-4 py-2.5 border-b border-[#2d2d3a]">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/60"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/60"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/60"></span>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">CV PREVIEW WORKSPACE</span>
                        <span class="text-xs text-slate-500 font-mono">A4 STANDARD SIZE</span>
                    </div>

                    <iframe src="{{ route('cv.preview') }}?template={{ $selectedTemplate }}" class="w-full min-h-[750px] border-none bg-white" title="CV Live preview"></iframe>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- MODALS IMPLEMENTATION --}}
        {{-- ========================================================================= --}}
        
        {{-- Experience Modal --}}
        <div x-data="{ open: @entangle('showExperienceModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="open = false"></div>
            
            <div class="glass-card relative w-full max-w-2xl bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-briefcase class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingExperienceId ? 'Edit Work Experience' : 'Add Work Experience' }}</span>
                    </h3>
                    <button @click="open = false" class="text-slate-400 hover:text-white">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Company Name *</label>
                            <input type="text" wire:model.defer="exp_company" class="cyber-input w-full" placeholder="e.g., Google Indonesia" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Job Title / Position *</label>
                            <input type="text" wire:model.defer="exp_position" class="cyber-input w-full" placeholder="e.g., Senior DevOps Architect" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Employment Type</label>
                            <select wire:model.defer="exp_employment_type" class="cyber-select w-full">
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Location</label>
                            <input type="text" wire:model.defer="exp_location" class="cyber-input w-full" placeholder="e.g., Jakarta, Indonesia (Remote)" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Start Date *</label>
                            <input type="date" wire:model.defer="exp_start_date" class="cyber-input w-full font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">End Date (Leave blank if current)</label>
                            <input type="date" wire:model.defer="exp_end_date" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Description</label>
                        <textarea wire:model.defer="exp_description" rows="4" class="cyber-textarea w-full" placeholder="Introduce your scope of work or general duties in Markdown format..."></textarea>
                    </div>

                    {{-- Achievements Repeater --}}
                    <div class="space-y-2 border-t border-[#2d2d3a]/50 pt-4">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Key Achievements & Bullet Points</label>
                        
                        <div class="flex gap-2">
                            <input type="text" wire:model.defer="new_achievement" wire:keydown.enter.prevent="addAchievement" class="cyber-input flex-1 text-xs" placeholder="e.g., Automated deployment pipelines reducing release times by 40%..." />
                            <button type="button" wire:click="addAchievement" class="px-3 bg-cyan-950 border border-cyan-500/30 text-cyan-400 rounded-lg text-xs font-bold hover:bg-cyan-900 transition-colors">
                                Add
                            </button>
                        </div>

                        @if($exp_achievements && count(is_countable($exp_achievements) ? $exp_achievements : []) > 0)
                            <ul class="space-y-1.5 pt-2">
                                @foreach((is_array($exp_achievements) ? $exp_achievements : []) as $idx => $ach)
                                    <li class="bg-[#1a1a24] border border-[#2d2d3a] px-3 py-1.5 rounded-lg flex items-center justify-between text-xs text-slate-300">
                                        <span class="flex-1 leading-relaxed">{{ $ach }}</span>
                                        <button type="button" wire:click="removeAchievement({{ $idx }})" class="text-slate-500 hover:text-rose-400 ml-2">
                                            <x-heroicon-o-x-mark class="w-4.5 h-4.5" />
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Associated Skills --}}
                    <div class="space-y-2 border-t border-[#2d2d3a]/50 pt-4">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Associated Skills Used</label>
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
                    <button @click="open = false" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button wire:click="saveExperience" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        Save Experience
                    </button>
                </div>
            </div>
        </div>

        {{-- Education Modal --}}
        <div x-data="{ open: @entangle('showEducationModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="open = false"></div>
            
            <div class="glass-card relative w-full max-w-xl bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-academic-cap class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingEducationId ? 'Edit Academic Qualification' : 'Add Academic Qualification' }}</span>
                    </h3>
                    <button @click="open = false" class="text-slate-400 hover:text-white">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Institution / School Name *</label>
                        <input type="text" wire:model.defer="edu_institution" class="cyber-input w-full" placeholder="e.g., Institut Teknologi Bandung" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Degree / Qualification *</label>
                            <input type="text" wire:model.defer="edu_degree" class="cyber-input w-full" placeholder="e.g., Bachelor of Engineering (B.Eng.)" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Major / Field of Study *</label>
                            <input type="text" wire:model.defer="edu_field_of_study" class="cyber-input w-full" placeholder="e.g., Computer Science" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Start Date *</label>
                            <input type="date" wire:model.defer="edu_start_date" class="cyber-input w-full font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">End Date (Leave blank if current)</label>
                            <input type="date" wire:model.defer="edu_end_date" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Grade / GPA</label>
                            <input type="text" wire:model.defer="edu_grade" class="cyber-input w-full font-mono" placeholder="e.g., 3.84 / 4.00" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Display Order</label>
                            <input type="number" wire:model.defer="edu_order" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Activities and Societies</label>
                        <textarea wire:model.defer="edu_activities" rows="3" class="cyber-textarea w-full text-xs" placeholder="e.g., Head of Computer Engineering Union, Core member of robotics team..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button @click="open = false" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button wire:click="saveEducation" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        Save Education
                    </button>
                </div>
            </div>
        </div>

        {{-- Skill Modal --}}
        <div x-data="{ open: @entangle('showSkillModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="open = false"></div>
            
            <div class="glass-card relative w-full max-w-md bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingSkillId ? 'Edit Skill Profile' : 'Add Skill Profile' }}</span>
                    </h3>
                    <button @click="open = false" class="text-slate-400 hover:text-white">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Skill Name *</label>
                        <input type="text" wire:model.defer="skill_name" class="cyber-input w-full" placeholder="e.g., Kubernetes, Golang, CI/CD" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Skill Category *</label>
                        <select wire:model.defer="skill_category_id" class="cyber-select w-full">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Proficiency Level ({{ $skill_proficiency }}%)</label>
                        <input type="range" wire:model="skill_proficiency" min="0" max="100" class="w-full accent-cyan-500 cursor-pointer h-2 bg-[#1e1e2d] border border-[#2d2d3a] rounded-full" />
                        <div class="flex justify-between text-[10px] text-slate-500 font-mono mt-1 uppercase font-bold">
                            <span>Beginner</span>
                            <span>Intermediate</span>
                            <span>Expert</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Years of Experience</label>
                        <input type="number" step="0.5" wire:model.defer="skill_years_experience" class="cyber-input w-full font-mono" placeholder="e.g., 3.5" />
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button @click="open = false" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button wire:click="saveSkill" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        Save Skill
                    </button>
                </div>
            </div>
        </div>

        {{-- Certificate Modal --}}
        <div x-data="{ open: @entangle('showCertificateModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="open = false"></div>
            
            <div class="glass-card relative w-full max-w-lg bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-trophy class="w-5 h-5 text-cyan-400" />
                        <span>{{ $editingCertificateId ? 'Edit Certificate Record' : 'Add Certificate Record' }}</span>
                    </h3>
                    <button @click="open = false" class="text-slate-400 hover:text-white">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Certificate Title *</label>
                        <input type="text" wire:model.defer="cert_name" class="cyber-input w-full" placeholder="e.g., Certified Kubernetes Administrator (CKA)" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Issuer / Authority *</label>
                        <input type="text" wire:model.defer="cert_issuer" class="cyber-input w-full" placeholder="e.g., CNCF / The Linux Foundation" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Credential ID</label>
                            <input type="text" wire:model.defer="cert_credential_id" class="cyber-input w-full font-mono text-xs" placeholder="e.g., CKA-8263158" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Credential URL</label>
                            <input type="text" wire:model.defer="cert_credential_url" class="cyber-input w-full text-xs" placeholder="e.g., https://credly.com/..." />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Issue Date *</label>
                            <input type="date" wire:model.defer="cert_issue_date" class="cyber-input w-full font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Expiry Date</label>
                            <input type="date" wire:model.defer="cert_expiry_date" class="cyber-input w-full font-mono" />
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-300">
                            <input type="checkbox" wire:model.defer="cert_is_active" class="rounded bg-slate-800 border-slate-700 text-cyan-500 focus:ring-cyan-500/20" />
                            <span>This certificate is active and not archived</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#2d2d3a] bg-[#181824]/50 flex justify-end gap-3">
                    <button @click="open = false" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        Cancel
                    </button>
                    <button wire:click="saveCertificate" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all">
                        Save Certificate
                    </button>
                </div>
            </div>
        </div>

        {{-- Detailed Analysis Modal --}}
        <div x-data="{ open: @entangle('showAnalysisDetailModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
            <div class="fixed inset-0 bg-black/75 backdrop-blur-sm" @click="open = false"></div>
            
            <div class="glass-card relative w-full max-w-4xl bg-[#12121a] border border-[#2d2d3a] rounded-xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-[#2d2d3a] flex items-center justify-between bg-[#181824]/50">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <x-heroicon-o-document-text class="w-5 h-5 text-cyan-400" />
                        <span>AI Analysis Audit Report</span>
                    </h3>
                    <button @click="open = false" class="text-slate-400 hover:text-white transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                @if($selectedAnalysisDetail)
                    <div class="p-6 overflow-y-auto space-y-6 flex-1 font-space-grotesk text-slate-300">
                        {{-- Meta Details --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#1a1a24]/40 border border-[#2d2d3a]/60 p-4 rounded-xl">
                            <div>
                                <span class="text-xs uppercase text-slate-500 font-semibold">Targeted Role</span>
                                <h4 class="text-lg font-bold text-white mt-0.5">{{ $selectedAnalysisDetail['job_title_targeted'] }}</h4>
                            </div>
                            <div>
                                <span class="text-xs uppercase text-slate-500 font-semibold">Run Date</span>
                                <p class="text-sm font-mono font-medium text-slate-300 mt-0.5">{{ $selectedAnalysisDetail['date'] }}</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase text-slate-500 font-semibold">Profile Checked</span>
                                <p class="text-sm font-semibold text-cyan-400 mt-0.5">{{ $selectedAnalysisDetail['profile_name'] }}</p>
                            </div>
                        </div>

                        {{-- Scoring Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
                            @php
                                $detailScoreCards = [
                                    ['label' => 'ATS Score', 'value' => $selectedAnalysisDetail['ats_score'] ?? '-', 'color' => 'text-cyan-400'],
                                    ['label' => 'Format Score', 'value' => $selectedAnalysisDetail['format_score'] ?? '-', 'color' => 'text-blue-400'],
                                    ['label' => 'About Me Score', 'value' => $selectedAnalysisDetail['about_me_score'] ?? '-', 'color' => 'text-purple-400'],
                                    ['label' => 'Impact Score', 'value' => $selectedAnalysisDetail['impact_score'] ?? '-', 'color' => 'text-emerald-400'],
                                    ['label' => 'Role Alignment', 'value' => $selectedAnalysisDetail['role_alignment_score'] ?? '-', 'color' => 'text-fuchsia-400'],
                                    ['label' => 'Security Score', 'value' => $selectedAnalysisDetail['security_score'] ?? '-', 'color' => 'text-rose-400'],
                                    ['label' => 'Reliability Score', 'value' => $selectedAnalysisDetail['reliability_score'] ?? '-', 'color' => 'text-orange-400'],
                                    ['label' => 'Cloud Score', 'value' => $selectedAnalysisDetail['cloud_score'] ?? '-', 'color' => 'text-sky-400'],
                                    ['label' => 'Job Match Score', 'value' => $selectedAnalysisDetail['job_match_score'] ?? '-', 'color' => 'text-amber-400'],
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
                                Seniority: {{ $selectedAnalysisDetail['target_seniority'] }}
                            </div>
                        @endif

                        {{-- Executive Summary --}}
                        @if($selectedAnalysisDetail['summary'])
                            <div class="space-y-2">
                                <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                    <x-heroicon-o-information-circle class="w-4.5 h-4.5 text-cyan-400" />
                                    <span>Executive Summary</span>
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
                                    <span>Keywords Found ({{ count(is_countable($selectedAnalysisDetail['keywords']['found'] ?? null) ? $selectedAnalysisDetail['keywords']['found'] : []) }})</span>
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
                                        <span class="text-slate-500 text-xs italic">No matching keywords parsed yet.</span>
                                    @endif
                                </div>
                             </div>

                             {{-- Weak Evidence --}}
                             <div class="space-y-2">
                                 <h4 class="text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-exclamation-triangle class="w-4.5 h-4.5" />
                                     <span>Weak Evidence ({{ count(is_countable($selectedAnalysisDetail['keywords']['weak_evidence'] ?? null) ? $selectedAnalysisDetail['keywords']['weak_evidence'] : []) }})</span>
                                 </h4>
                                 <div class="p-4 bg-amber-950/10 border border-amber-900/30 rounded-xl min-h-[120px] max-h-[220px] overflow-y-auto">
                                     @if(!empty($selectedAnalysisDetail['keywords']['weak_evidence']) && is_array($selectedAnalysisDetail['keywords']['weak_evidence']))
                                         <div class="flex flex-wrap gap-1.5">
                                             @foreach($selectedAnalysisDetail['keywords']['weak_evidence'] as $kw)
                                                 <span class="px-2 py-1 bg-amber-950/40 text-amber-300 border border-amber-500/20 text-xs font-semibold rounded font-mono">
                                                     {{ $kw }}
                                                 </span>
                                             @endforeach
                                         </div>
                                     @else
                                         <span class="text-slate-500 text-xs italic">No weak-evidence claims flagged.</span>
                                     @endif
                                 </div>
                             </div>

                             {{-- Missing Keywords --}}
                             <div class="space-y-2">
                                 <h4 class="text-sm font-bold text-rose-400 uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-x-circle class="w-4.5 h-4.5" />
                                     <span>Missing Keywords ({{ count(is_countable($selectedAnalysisDetail['keywords']['missing'] ?? null) ? $selectedAnalysisDetail['keywords']['missing'] : []) }})</span>
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
                                             <span>Flawless! All key terms are covered.</span>
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
                                     <span>Step-by-Step AI Improvement Action Checklist</span>
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
                                     <h4 class="text-sm font-bold text-emerald-300">Professional Strengths</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['strengths'] as $strength)
                                             <li class="flex gap-2"><span class="text-emerald-400">•</span><span>{{ $strength }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif

                             @if(!empty($selectedAnalysisDetail['critical_gaps']) && is_array($selectedAnalysisDetail['critical_gaps']))
                                 <div class="p-4 bg-rose-950/10 border border-rose-900/30 rounded-xl space-y-2">
                                     <h4 class="text-sm font-bold text-rose-300">Critical Gaps</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['critical_gaps'] as $gap)
                                             <li class="flex gap-2"><span class="text-rose-400">•</span><span>{{ $gap }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif

                             @if(!empty($selectedAnalysisDetail['missing_metrics']) && is_array($selectedAnalysisDetail['missing_metrics']))
                                 <div class="p-4 bg-amber-950/10 border border-amber-900/30 rounded-xl space-y-2">
                                     <h4 class="text-sm font-bold text-amber-300">Missing Metrics</h4>
                                     <ul class="space-y-2 text-xs text-slate-300 leading-relaxed">
                                         @foreach($selectedAnalysisDetail['missing_metrics'] as $metric)
                                             <li class="flex gap-2"><span class="text-amber-400">•</span><span>{{ $metric }}</span></li>
                                         @endforeach
                                     </ul>
                                 </div>
                             @endif

                             @if(!empty($selectedAnalysisDetail['rewrite_priorities']) && is_array($selectedAnalysisDetail['rewrite_priorities']))
                                 <div class="p-4 bg-sky-950/10 border border-sky-900/30 rounded-xl space-y-2">
                                     <h4 class="text-sm font-bold text-sky-300">Rewrite Priorities</h4>
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
                                     <span>Experience Evidence Review</span>
                                 </h4>
                                 <div class="space-y-2.5">
                                     @foreach($selectedAnalysisDetail['experience_review'] as $review)
                                         <div class="p-4 bg-[#181824]/40 border border-[#2d2d3a]/50 rounded-lg space-y-2">
                                             <div class="text-sm font-semibold text-white">{{ $review['section'] ?? 'Experience' }}</div>
                                             <div class="text-xs text-emerald-300">Strength: {{ $review['strength'] ?? '-' }}</div>
                                             <div class="text-xs text-rose-300">Gap: {{ $review['gap'] ?? '-' }}</div>
                                             <div class="text-xs text-sky-300">Rewrite Hint: {{ $review['rewrite_hint'] ?? '-' }}</div>
                                         </div>
                                     @endforeach
                                 </div>
                             </div>
                         @endif

                         {{-- Industry/Domain Match --}}
                         @if(!empty($selectedAnalysisDetail['industry_match']) && is_array($selectedAnalysisDetail['industry_match']))
                             <div class="space-y-3">
                                 <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
                                     <x-heroicon-o-tag class="w-4.5 h-4.5 text-cyan-400" />
                                     <span>Industry & Skill Keyword Coverage Match</span>
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
                        <button type="button" wire:click="loadAnalysisIntoWorkspace({{ $selectedAnalysisDetail['id'] }})" @click="open = false" class="px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:shadow-lg hover:shadow-cyan-500/20 text-white rounded-lg text-sm font-bold transition-all flex items-center gap-1.5">
                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                            <span>Load Into Workspace</span>
                        </button>
                    @else
                        <div></div>
                    @endif
                    <button @click="open = false" class="px-4 py-2 border border-slate-700 hover:bg-slate-800 text-slate-300 rounded-lg text-sm transition-colors">
                        Close Report
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- Neon Cyberpunk Workspace CSS Stylings --}}
    <style>
        /* Typography import check */
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

        .font-space-grotesk {
            font-family: 'Space Grotesk', sans-serif !important;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace !important;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Pulse dot for workspace title */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #00d4ff;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.8);
            animation: pulse-glowing 2s infinite;
        }

        @keyframes pulse-glowing {
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

        /* Fade-in Animation for Tab panes */
        .animate-fade-in {
            animation: slide-up-fade 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes slide-up-fade {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
            border-color: rgba(0, 212, 255, 0.25) !important;
            box-shadow: 0 12px 40px rgba(0, 212, 255, 0.12);
        }

        /* Action Buttons Hover Pendaran */
        .glow-cyan-hover:hover {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.6) !important;
        }

        .glow-green-hover:hover {
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.6) !important;
        }

        /* Cyberpunk Input & Textarea Elements */
        .cyber-input {
            background: rgba(18, 18, 26, 0.8) !important;
            border: 1px solid #2d2d3a !important;
            border-radius: 10px;
            color: #f1f5f9 !important;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .cyber-input:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.15), 0 0 10px rgba(0, 212, 255, 0.2) !important;
            outline: none;
        }

        .cyber-textarea {
            background: rgba(18, 18, 26, 0.8) !important;
            border: 1px solid #2d2d3a !important;
            border-radius: 10px;
            color: #f1f5f9 !important;
            padding: 12px 14px;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            resize: vertical;
        }

        .cyber-textarea:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.15), 0 0 10px rgba(0, 212, 255, 0.2) !important;
            outline: none;
        }

        .cyber-select {
            background: rgba(18, 18, 26, 0.8) !important;
            border: 1px solid #2d2d3a !important;
            border-radius: 10px;
            color: #f1f5f9 !important;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            cursor: pointer;
        }

        .cyber-select:focus {
            border-color: #00d4ff !important;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.15), 0 0 10px rgba(0, 212, 255, 0.2) !important;
            outline: none;
        }
    </style>
</x-filament-panels::page>
