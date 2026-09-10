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

    <div class="cv-workspace-container space-y-8 font-space-grotesk text-slate-700 dark:text-slate-300">
        
        {{-- Unified Header --}}
        <div class="glass-card premium-workspace-header p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="pulse-dot"></span>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-wide">CV Workspace</h2>
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
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-cyan-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * $analysisResult['ats_score']) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#atsGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ $analysisResult['ats_score'] }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-500 dark:text-slate-400 font-bold uppercase">ATS Score</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-blue-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * $analysisResult['format_score']) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#formatGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ $analysisResult['format_score'] }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-500 dark:text-slate-400 font-bold uppercase">Format Score</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-purple-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($analysisResult['about_me_score'] ?? 0)) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#aboutmeGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ $analysisResult['about_me_score'] ?? 0 }}%</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-500 dark:text-slate-400 font-bold uppercase">About Me Score</span>
                        </div>

                        <div class="glass-card stat-meter-card p-5 text-center flex flex-col items-center">
                            <div class="score-circle-container mb-3">
                                <svg viewBox="0 0 120 120" class="w-24 h-24">
                                    <circle class="stroke-slate-300 dark:stroke-slate-700/40 fill-none" stroke-width="5" cx="60" cy="60" r="50" />
                                    <circle class="stroke-amber-500 fill-none transition-all duration-1000" stroke-linecap="round" stroke-width="5" stroke-dasharray="314.159" stroke-dashoffset="{{ 314.159 - (314.159 * ($analysisResult['job_match_score'] ?? 0)) / 100 }}" cx="60" cy="60" r="50" transform="rotate(-90 60 60)" style="stroke: url(#jobmatchGradient);" />
                                    <text x="60" y="60" text-anchor="middle" dominant-baseline="central" class="fill-slate-900 dark:fill-white font-bold text-xl font-mono">{{ ($analysisResult['job_match_score'] ?? null) === null ? 'N/A' : $analysisResult['job_match_score'].'%' }}</text>
                                </svg>
                            </div>
                            <span class="text-xs tracking-wider text-slate-400 font-bold uppercase">Job Match Score</span>
                        </div>
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-chart-pie class="w-12 h-12 text-slate-500 mx-auto mb-3" />
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white">No CV Analysis Executed Yet</h4>
                        <p class="text-sm text-slate-400 mt-1 max-w-md mx-auto">Fill in your professional profile and run the AI analyzer to generate your resume scoring trends and recommendations.</p>
                        <x-filament::button wire:click="switchTab('profile')" color="primary" class="mt-4">
                            Start Building CV
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
                                            <td class="py-3 px-4 text-slate-900 dark:text-white font-medium">{{ $item->created_at->format('M j, Y H:i') }}</td>
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
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
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
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
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
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
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
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3">Avatar Profile</h3>
                            
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
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3">Design Config</h3>

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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-cyan-400 transition-colors">{{ $exp->position }}</h4>
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-cyan-400 transition-colors">{{ $edu->degree }} in {{ $edu->field_of_study }}</h4>
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                                <h4 class="text-md font-bold text-slate-900 dark:text-white flex items-center gap-2 border-b border-[#2d2d3a]/50 pb-2">
                                    <span class="w-1.5 h-4 bg-cyan-400 rounded-sm"></span>
                                    <span>{{ $categoryName }}</span>
                                </h4>

                                <div class="space-y-3.5">
                                    @foreach($categorySkills as $skill)
                                        @php
                                            $pct = max(5, min(100, (int) round($skill->proficiency * 20)));
                                            $profText = match((int) $skill->proficiency) {
                                                1 => 'Beginner',
                                                2 => 'Developing',
                                                3 => 'Intermediate',
                                                4 => 'Advanced',
                                                5 => 'Expert',
                                                default => (string) $skill->proficiency,
                                            };
                                        @endphp
                                        <div class="group relative pr-16">
                                            <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                                <span class="text-slate-200 group-hover:text-cyan-400 transition-colors">{{ $skill->name }}</span>
                                                <span class="text-slate-400 font-mono">{{ $skill->years_experience }} yrs exp <span class="text-slate-600">|</span> {{ $profText }}</span>
                                            </div>
                                            <div class="w-full bg-[#1e1e2d] h-2 rounded-full overflow-hidden border border-[#2d2d3a]/40">
                                                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full" style="width: {{ $pct }}%"></div>
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                                        <h4 class="text-md font-bold text-slate-900 dark:text-white group-hover:text-cyan-400 transition-colors">{{ $cert->name }}</h4>
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
            <div class="tab-pane-content animate-fade-in am-scope">
                <div class="am-layout">

                    {{-- Kolom input --}}
                    <div class="am-col-input">
                        <div class="am-card">
                            <div class="am-card-head">
                                <span class="am-ico"><x-heroicon-o-briefcase class="w-4.5 h-4.5" /></span>
                                <div>
                                    <h3 class="am-title">Target Pekerjaan</h3>
                                    <p class="am-hint">Pilih role dan tempel deskripsi lowongan untuk analisis kecocokan.</p>
                                </div>
                            </div>

                            <div class="am-field">
                                <label class="am-label" for="am-target-role">Target Role</label>
                                <select id="am-target-role" wire:model.live="targetRole" class="am-select">
                                    <option value="Devops Engineer">DevOps Engineer</option>
                                    <option value="Devsecops Engineer">DevSecOps Engineer</option>
                                    <option value="Cloud Engineer">Cloud Engineer</option>
                                    <option value="Site Reliability Engineer">Site Reliability Engineer</option>
                                </select>
                            </div>

                            <div class="am-field">
                                <label class="am-label" for="am-jobdesc">
                                    Job Description <span class="am-optional">opsional</span>
                                </label>
                                <textarea id="am-jobdesc" wire:model="jobDescription" rows="8" class="am-textarea"
                                          placeholder="Tempel requirement atau deskripsi lowongan di sini..."></textarea>
                                <p class="am-hint">Isi untuk mendapat skor Job Match berbasis bukti. Kosongkan jika hanya ingin menilai ATS, format, dan domain.</p>
                            </div>

                            <x-filament::button wire:click="analyzeJobMatch" color="primary" class="w-full" :loading="$isAnalyzing">
                                <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                                <span>Jalankan Analisis AI</span>
                            </x-filament::button>
                        </div>

                        <x-cv-analysis-progress />

                        @if($history && count(is_countable($history) ? $history : []) > 0)
                            <div class="am-card">
                                <div class="am-card-head">
                                    <span class="am-ico"><x-heroicon-o-clock class="w-4.5 h-4.5" /></span>
                                    <div>
                                        <h3 class="am-title">Analisis Terakhir</h3>
                                        <p class="am-hint">Klik untuk memuat hasil sebelumnya.</p>
                                    </div>
                                </div>
                                <div class="am-history">
                                    @foreach($history as $item)
                                        <button type="button" wire:key="am-hist-{{ $item->id }}"
                                                wire:click="loadAnalysisIntoWorkspace({{ $item->id }})"
                                                class="am-history-item {{ $currentAnalysis?->id === $item->id ? 'is-active' : '' }}">
                                            <span class="am-history-main">
                                                <span class="am-history-role">{{ $item->job_title_targeted ?? 'Analisis CV' }}</span>
                                                <span class="am-history-date">{{ $item->created_at->format('d M Y, H:i') }}</span>
                                            </span>
                                            @if($item->job_match_score !== null)
                                                <span class="am-chip {{ $item->job_match_score >= 70 ? 'am-chip--ok' : ($item->job_match_score >= 50 ? 'am-chip--warn' : 'am-chip--bad') }}">
                                                    {{ $item->job_match_score }}%
                                                </span>
                                            @else
                                                <span class="am-chip am-chip--neutral">ATS {{ $item->ats_score }}%</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom hasil --}}
                    <div class="am-col-result">
                        @if(!$isAnalyzing && $analysisResult)
                            @php
                                // Profil metrik mengikuti role target agar panel tidak menjadi
                                // daftar persentase generik.
                                $roleKey = strtolower(trim($analysisResult['job_title_targeted'] ?? $targetRole ?? ''));
                                $roleKey = str_contains($roleKey, 'devsecops') ? 'devsecops engineer'
                                    : (str_contains($roleKey, 'reliability') ? 'site reliability engineer'
                                    : (str_contains($roleKey, 'cloud') ? 'cloud engineer' : 'devops engineer'));

                                $roleProfiles = [
                                    'devops engineer' => [
                                        'domain' => 'devops',
                                        'tagline' => 'Keseimbangan delivery CI/CD, infrastructure-as-code, dan keandalan platform.',
                                        'core' => ['role_alignment_score', 'reliability_score', 'format_score'],
                                    ],
                                    'devsecops engineer' => [
                                        'domain' => 'devsecops',
                                        'tagline' => 'Keamanan menyatu dalam pipeline: hardening, manajemen secret, dan compliance.',
                                        'core' => ['security_score', 'role_alignment_score'],
                                    ],
                                    'cloud engineer' => [
                                        'domain' => 'cloud_engineer',
                                        'tagline' => 'Kedalaman arsitektur cloud, pemilihan layanan, dan infrastruktur hemat biaya.',
                                        'core' => ['cloud_score', 'impact_score'],
                                    ],
                                    'site reliability engineer' => [
                                        'domain' => 'sre',
                                        'tagline' => 'Rekayasa uptime: SLO, incident response, observability, dan perencanaan kapasitas.',
                                        'core' => ['reliability_score', 'impact_score'],
                                    ],
                                ];
                                $roleProfile = $roleProfiles[$roleKey];

                                $fitmentMetrics = [
                                    'role_alignment_score' => ['label' => 'Kesesuaian Role', 'desc' => 'Cakupan skill & tanggung jawab terhadap role target'],
                                    'security_score' => ['label' => 'Keamanan', 'desc' => 'Hardening, manajemen secret & praktik kerentanan'],
                                    'reliability_score' => ['label' => 'Keandalan', 'desc' => 'Uptime, incident response & praktik SLO'],
                                    'cloud_score' => ['label' => 'Cloud', 'desc' => 'Kedalaman layanan & arsitektur cloud'],
                                    'ats_score' => ['label' => 'Skor ATS', 'desc' => 'Kompatibilitas parsing keyword & section'],
                                    'format_score' => ['label' => 'Format', 'desc' => 'Kualitas struktur, heading & tata letak'],
                                    'about_me_score' => ['label' => 'Ringkasan', 'desc' => 'Kekuatan & positioning summary'],
                                    'impact_score' => ['label' => 'Dampak', 'desc' => 'Outcome terukur & pencapaian'],
                                    'job_match_score' => ['label' => 'Job Match', 'desc' => 'Kecocokan berbasis bukti vs deskripsi lowongan'],
                                ];

                                $orderedMetrics = collect($roleProfile['core'])
                                    ->merge(collect($fitmentMetrics)->keys()->except($roleProfile['core']))
                                    ->values();

                                $heroDomain = $analysisResult['industry_match'][$roleProfile['domain']] ?? null;
                                $jobMatchValue = is_numeric($analysisResult['job_match_score'] ?? null) ? (int) $analysisResult['job_match_score'] : null;
                                $heroPct = $jobMatchValue ?? (is_numeric($heroDomain) ? (int) $heroDomain : ($analysisResult['ats_score'] ?? null));

                                $detail = $analysisResult['job_match_detail'] ?? null;
                                $overall = is_array($detail) && isset($detail['overall']) ? (int) $detail['overall'] : null;

                                $bandLabelMap = [
                                    'Strong Match' => 'Kecocokan Kuat',
                                    'Good Match' => 'Kecocokan Baik',
                                    'Partial Match' => 'Kecocokan Sebagian',
                                    'Weak Match' => 'Kecocokan Lemah',
                                ];
                                $bandLabel = $bandLabelMap[$detail['band']['label'] ?? ''] ?? ($detail['band']['label'] ?? null);
                                $bandTone = $overall === null ? 'neutral' : ($overall >= 70 ? 'ok' : ($overall >= 50 ? 'warn' : 'bad'));

                                $statusMeta = [
                                    'verified' => ['label' => 'Terverifikasi', 'class' => 'am-chip--ok'],
                                    'claimed' => ['label' => 'Terdaku', 'class' => 'am-chip--warn'],
                                    'missing' => ['label' => 'Hilang', 'class' => 'am-chip--bad'],
                                ];

                                $focusAreas = collect($orderedMetrics)
                                    ->reject(fn ($key) => $key === 'job_match_score')
                                    ->map(fn ($key) => ['key' => $key] + $fitmentMetrics[$key] + ['value' => $analysisResult[$key] ?? null])
                                    ->filter(fn ($metric) => is_numeric($metric['value']) && (int) $metric['value'] < 70)
                                    ->sortBy('value')
                                    ->take(3)
                                    ->values();

                                $requirements = is_array($detail['requirements'] ?? null) ? $detail['requirements'] : [];
                                $mustHave = array_values(array_filter($requirements, fn ($r) => ($r['type'] ?? 'must_have') !== 'nice_to_have'));
                                $niceToHave = array_values(array_filter($requirements, fn ($r) => ($r['type'] ?? 'must_have') === 'nice_to_have'));

                                $foundKeywords = is_array($analysisResult['keywords']['found'] ?? null) ? $analysisResult['keywords']['found'] : [];
                                $weakKeywords = is_array($analysisResult['keywords']['weak_evidence'] ?? null) ? $analysisResult['keywords']['weak_evidence'] : [];
                                $missingKeywords = is_array($analysisResult['keywords']['missing'] ?? null) ? $analysisResult['keywords']['missing'] : [];
                                $suggestions = is_array($analysisResult['suggestions'] ?? null) ? $analysisResult['suggestions'] : [];
                                $strengths = is_array($analysisResult['strengths'] ?? null) ? $analysisResult['strengths'] : [];
                                $criticalGaps = is_array($analysisResult['critical_gaps'] ?? null) ? $analysisResult['critical_gaps'] : [];
                                $missingMetrics = is_array($analysisResult['missing_metrics'] ?? null) ? $analysisResult['missing_metrics'] : [];
                                $rewritePriorities = is_array($analysisResult['rewrite_priorities'] ?? null) ? $analysisResult['rewrite_priorities'] : [];
                                $experienceReview = is_array($analysisResult['experience_review'] ?? null) ? array_values(array_filter($analysisResult['experience_review'])) : [];
                                $industryMatch = is_array($analysisResult['industry_match'] ?? null) ? $analysisResult['industry_match'] : [];
                            @endphp

                            <div class="am-result">
                                {{-- Ringkasan utama --}}
                                <div class="am-card am-summary">
                                    <div class="am-summary-top">
                                        <div class="am-hero-ring">
                                            <x-score-ring :value="$heroPct" :size="112" :stroke-width="6" center-class="text-lg" variant="muted" />
                                            <span class="am-ring-caption">{{ $jobMatchValue !== null ? 'Job Match' : 'Role Fitment' }}</span>
                                        </div>
                                        <div class="am-summary-body">
                                            <div class="am-summary-head">
                                                <h3 class="am-title-lg">Kecocokan untuk {{ $analysisResult['job_title_targeted'] ?? $targetRole }}</h3>
                                                @if($bandLabel)
                                                    <span class="am-chip am-chip--{{ $bandTone }}">{{ $bandLabel }}</span>
                                                @endif
                                            </div>
                                            <p class="am-summary-tagline">{{ $roleProfile['tagline'] }}</p>
                                            <div class="am-tags">
                                                @if(!empty($analysisResult['target_seniority']))
                                                    <span class="am-chip am-chip--neutral">Seniority: {{ $analysisResult['target_seniority'] }}</span>
                                                @endif
                                                @if(is_numeric($heroDomain))
                                                    <span class="am-chip am-chip--neutral">Domain {{ strtoupper(str_replace('_', ' ', $roleProfile['domain'])) }}: {{ (int) $heroDomain }}%</span>
                                                @endif
                                                @if($jobMatchValue !== null)
                                                    <span class="am-chip am-chip--{{ $bandTone }}">Job Match: {{ $jobMatchValue }}%</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($analysisResult['summary']))
                                        <p class="am-summary-text">{{ $analysisResult['summary'] }}</p>
                                    @endif

                                    <div class="am-quick-grid">
                                        @foreach($roleProfile['core'] as $coreKey)
                                            @php $coreValue = $analysisResult[$coreKey] ?? null; @endphp
                                            <div class="am-quick">
                                                <span class="am-quick-value am-tone-{{ is_numeric($coreValue) ? ((int) $coreValue >= 80 ? 'ok' : ((int) $coreValue >= 60 ? 'warn' : 'bad')) : 'neutral' }}">
                                                    {{ is_numeric($coreValue) ? (int) $coreValue.'%' : '-' }}
                                                </span>
                                                <span class="am-quick-label">{{ $fitmentMetrics[$coreKey]['label'] }}</span>
                                            </div>
                                        @endforeach
                                        <div class="am-quick">
                                            <span class="am-quick-value am-tone-neutral">{{ $analysisResult['ats_score'] ?? '-' }}%</span>
                                            <span class="am-quick-label">Skor ATS</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Metrik lengkap --}}
                                <div class="am-card">
                                    <div class="am-card-head">
                                        <span class="am-ico"><x-heroicon-o-chart-bar class="w-4.5 h-4.5" /></span>
                                        <div>
                                            <h3 class="am-title">Metrik Penilaian</h3>
                                            <p class="am-hint">Metrik inti untuk role ini ditandai.</p>
                                        </div>
                                    </div>
                                    <div class="am-metrics">
                                        @foreach($orderedMetrics as $metricKey)
                                            @php
                                                $metric = $fitmentMetrics[$metricKey];
                                                $isCore = in_array($metricKey, $roleProfile['core']);
                                                $metricValue = $analysisResult[$metricKey] ?? null;
                                            @endphp
                                            <div class="am-metric {{ $isCore ? 'is-core' : '' }}">
                                                <div class="am-metric-top">
                                                    <span class="am-metric-label">{{ $metric['label'] }}</span>
                                                    @if($isCore)<span class="am-chip am-chip--accent am-chip--xs">inti</span>@endif
                                                </div>
                                                <div class="am-meter">
                                                    <div class="am-meter-fill am-tone-bg-{{ is_numeric($metricValue) ? ((int) $metricValue >= 80 ? 'ok' : ((int) $metricValue >= 60 ? 'warn' : 'bad')) : 'neutral' }}"
                                                         style="width: {{ is_numeric($metricValue) ? (int) max(0, min(100, $metricValue)) : 0 }}%"></div>
                                                </div>
                                                <div class="am-metric-bottom">
                                                    <span class="am-metric-desc">{{ $metric['desc'] }}</span>
                                                    <span class="am-metric-value">{{ is_numeric($metricValue) ? (int) $metricValue.'%' : '-' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- A. Checklist requirement --}}
                                @if(is_array($detail) && $requirements !== [])
                                    <details class="am-section" open>
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-clipboard-document-check class="w-4.5 h-4.5" />
                                                <span>Checklist Requirement</span>
                                            </span>
                                            <span class="am-section-meta">{{ count($requirements) }} syarat</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-coverage-grid">
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value am-tone-{{ $bandTone }}">{{ $detail['overall'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">Skor Keseluruhan</span>
                                                </div>
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value">{{ $detail['must_have_coverage'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">Must-have</span>
                                                </div>
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value">{{ $detail['nice_to_have_coverage'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">Nice-to-have</span>
                                                </div>
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value">{{ $detail['evidence_quality'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">Kualitas Bukti</span>
                                                </div>
                                            </div>

                                            @if(!empty($detail['band']['definition']))
                                                <div class="am-callout am-callout--accent">
                                                    <strong>{{ $bandLabel ?? ($detail['band']['label'] ?? '') }}</strong>
                                                    <span>{{ $detail['band']['definition'] }}</span>
                                                </div>
                                            @endif

                                            @foreach([['title' => 'Must-have', 'items' => $mustHave], ['title' => 'Nice-to-have', 'items' => $niceToHave]] as $group)
                                                @if($group['items'] !== [])
                                                    <div class="am-req-group">
                                                        <h5 class="am-subtitle">{{ $group['title'] }} <span class="am-subtitle-count">{{ count($group['items']) }}</span></h5>
                                                        <div class="am-req-list">
                                                            @foreach($group['items'] as $req)
                                                                @php
                                                                    $status = $req['status'] ?? 'missing';
                                                                    $meta = $statusMeta[$status] ?? $statusMeta['missing'];
                                                                @endphp
                                                                <div class="am-req">
                                                                    <div class="am-req-head">
                                                                        <span class="am-req-name">{{ $req['requirement'] ?? '' }}</span>
                                                                        <span class="am-chip {{ $meta['class'] }} am-chip--xs">{{ $meta['label'] }}</span>
                                                                    </div>
                                                                    @if(!empty($req['evidence_quote']))
                                                                        <blockquote class="am-quote">{{ $req['evidence_quote'] }}</blockquote>
                                                                    @endif
                                                                    @if(!empty($req['reasoning']))
                                                                        <p class="am-req-reason">{{ $req['reasoning'] }}</p>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <details class="am-subsection">
                                                <summary class="am-subsection-head">
                                                    <x-heroicon-o-information-circle class="w-4 h-4" />
                                                    <span>Cara skor dihitung (formula objektif)</span>
                                                    <x-heroicon-o-chevron-down class="am-chevron w-3.5 h-3.5" />
                                                </summary>
                                                <div class="am-subsection-body">
                                                    <p>overall = 0.6 × must_have_coverage + 0.2 × nice_to_have_coverage + 0.2 × evidence_quality</p>
                                                    <p>poin status: verified = 1.0 · claimed = 0.4 · missing = 0.0</p>
                                                    <p>evidence_quality = verified / (verified + claimed)</p>
                                                    <ul class="am-band-list">
                                                        <li><span class="am-tone-ok">85–100 Kuat:</span> mayoritas must-have terverifikasi dengan bukti terukur.</li>
                                                        <li><span class="am-tone-accent">70–84 Baik:</span> must-have tercakup, sebagian masih sebatas klaim.</li>
                                                        <li><span class="am-tone-warn">50–69 Sebagian:</span> ada celah pada must-have atau bukti lemah.</li>
                                                        <li><span class="am-tone-bad">0–49 Lemah:</span> must-have penting belum ada di CV.</li>
                                                    </ul>
                                                    <p class="am-subsection-note">Skor dihitung secara deterministik dari bukti yang diverifikasi terhadap teks CV — AI tidak pernah menetapkan angka.</p>
                                                </div>
                                            </details>
                                        </div>
                                    </details>
                                @endif

                                {{-- B. Audit kata kunci --}}
                                @if($foundKeywords !== [] || $weakKeywords !== [] || $missingKeywords !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-tag class="w-4.5 h-4.5" />
                                                <span>Audit Kata Kunci</span>
                                            </span>
                                            <span class="am-section-meta">{{ count($foundKeywords) }} ditemukan · {{ count($missingKeywords) }} hilang</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-kw-grid">
                                                <div class="am-kw am-kw--ok">
                                                    <h5 class="am-kw-title"><x-heroicon-o-check-circle class="w-4 h-4" /> Ditemukan ({{ count($foundKeywords) }})</h5>
                                                    <div class="am-chip-wrap">
                                                        @forelse($foundKeywords as $kw)
                                                            <span class="am-chip am-chip--ok am-chip--xs">{{ $kw }}</span>
                                                        @empty
                                                            <span class="am-empty-note">Tidak ada</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="am-kw am-kw--warn">
                                                    <h5 class="am-kw-title"><x-heroicon-o-exclamation-triangle class="w-4 h-4" /> Bukti Lemah ({{ count($weakKeywords) }})</h5>
                                                    <div class="am-chip-wrap">
                                                        @forelse($weakKeywords as $kw)
                                                            @php
                                                                $weakLabel = is_array($kw) ? (string) ($kw['keyword'] ?? $kw['reason'] ?? '') : (string) $kw;
                                                                $weakReason = is_array($kw) ? (string) ($kw['reason'] ?? '') : '';
                                                            @endphp
                                                            @if(trim($weakLabel) !== '')
                                                                <span class="am-chip am-chip--warn am-chip--xs" @if(trim($weakReason) !== '') title="{{ $weakReason }}" @endif>{{ $weakLabel }}</span>
                                                            @endif
                                                        @empty
                                                            <span class="am-empty-note">Tidak ada</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="am-kw am-kw--bad">
                                                    <h5 class="am-kw-title"><x-heroicon-o-x-circle class="w-4 h-4" /> Hilang ({{ count($missingKeywords) }})</h5>
                                                    <div class="am-chip-wrap">
                                                        @forelse($missingKeywords as $kw)
                                                            <span class="am-chip am-chip--bad am-chip--xs">{{ $kw }}</span>
                                                        @empty
                                                            <span class="am-empty-note">Cakupan kata kunci lengkap.</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                @endif

                                {{-- C. Fokus perbaikan --}}
                                @if($focusAreas->isNotEmpty() || $suggestions !== [] || $rewritePriorities !== [])
                                    <details class="am-section" open>
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-wrench-screwdriver class="w-4.5 h-4.5" />
                                                <span>Fokus Perbaikan</span>
                                            </span>
                                            <span class="am-section-meta">{{ $focusAreas->count() + count($suggestions) }} item</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            @if($focusAreas->isNotEmpty())
                                                <div class="am-focus">
                                                    <h5 class="am-subtitle">Area skor terendah</h5>
                                                    <div class="am-chip-wrap">
                                                        @foreach($focusAreas as $area)
                                                            <span class="am-chip am-chip--warn">
                                                                <span class="am-chip-strong">{{ (int) $area['value'] }}%</span>
                                                                {{ $area['label'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($suggestions !== [])
                                                <div>
                                                    <h5 class="am-subtitle">Langkah optimasi</h5>
                                                    <ol class="am-numbered">
                                                        @foreach($suggestions as $idx => $suggestion)
                                                            <li>
                                                                <span class="am-numbered-idx">{{ $idx + 1 }}</span>
                                                                <span class="am-numbered-text">{{ $suggestion }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            @endif

                                            @if($rewritePriorities !== [])
                                                <div>
                                                    <h5 class="am-subtitle">Prioritas rewrite</h5>
                                                    <ul class="am-bullets">
                                                        @foreach($rewritePriorities as $priority)
                                                            <li>{{ $priority }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif

                                {{-- D. Kekuatan & celah --}}
                                @if($strengths !== [] || $criticalGaps !== [] || $missingMetrics !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-scale class="w-4.5 h-4.5" />
                                                <span>Kekuatan & Celah</span>
                                            </span>
                                            <span class="am-section-meta">{{ count($strengths) }} kekuatan · {{ count($criticalGaps) }} celah</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-two-col">
                                                @if($strengths !== [])
                                                    <div class="am-callout am-callout--ok">
                                                        <h5 class="am-subtitle am-tone-ok">Kekuatan profesional</h5>
                                                        <ul class="am-bullets">
                                                            @foreach($strengths as $strength)
                                                                <li>{{ $strength }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                @if($criticalGaps !== [])
                                                    <div class="am-callout am-callout--bad">
                                                        <h5 class="am-subtitle am-tone-bad">Celah kritis</h5>
                                                        <ul class="am-bullets">
                                                            @foreach($criticalGaps as $gap)
                                                                <li>{{ $gap }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($missingMetrics !== [])
                                                <div class="am-callout am-callout--warn">
                                                    <h5 class="am-subtitle am-tone-warn">Metrik yang belum ada</h5>
                                                    <ul class="am-bullets">
                                                        @foreach($missingMetrics as $metric)
                                                            <li>{{ $metric }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif

                                {{-- E. Review pengalaman --}}
                                @if($experienceReview !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-clipboard-document-check class="w-4.5 h-4.5" />
                                                <span>Review Pengalaman</span>
                                            </span>
                                            <span class="am-section-meta">{{ count($experienceReview) }} bagian</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-review-list">
                                                @foreach($experienceReview as $review)
                                                    @if(is_string($review))
                                                        <div class="am-review-note">
                                                            <x-heroicon-o-information-circle class="w-4 h-4" />
                                                            <p>{{ $review }}</p>
                                                        </div>
                                                    @elseif(is_array($review))
                                                        @php
                                                            $reviewSection = trim((string) ($review['section'] ?? ''));
                                                            $reviewStrength = trim((string) ($review['strength'] ?? ''));
                                                            $reviewGap = trim((string) ($review['gap'] ?? ''));
                                                            $reviewHint = trim((string) ($review['rewrite_hint'] ?? ''));
                                                        @endphp
                                                        <div class="am-review">
                                                            @if($reviewSection !== '')
                                                                <h5 class="am-review-head">{{ $reviewSection }}</h5>
                                                            @endif
                                                            @if($reviewStrength !== '')
                                                                <div class="am-review-row">
                                                                    <span class="am-review-tag am-tone-ok">Kekuatan</span>
                                                                    <p>{{ $reviewStrength }}</p>
                                                                </div>
                                                            @endif
                                                            @if($reviewGap !== '')
                                                                <div class="am-review-row">
                                                                    <span class="am-review-tag am-tone-bad">Celah</span>
                                                                    <p>{{ $reviewGap }}</p>
                                                                </div>
                                                            @endif
                                                            @if($reviewHint !== '')
                                                                <div class="am-review-row am-review-row--hint">
                                                                    <span class="am-review-tag am-tone-accent">Saran rewrite</span>
                                                                    <p>{{ $reviewHint }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                @endif

                                {{-- F. Detail domain --}}
                                @if($industryMatch !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-globe-alt class="w-4.5 h-4.5" />
                                                <span>Detail Domain</span>
                                            </span>
                                            <span class="am-section-meta">{{ count($industryMatch) }} domain</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-domain-grid">
                                                @foreach($industryMatch as $domainKey => $score)
                                                    @php $isTargetDomain = $domainKey === $roleProfile['domain']; @endphp
                                                    <div class="am-domain {{ $isTargetDomain ? 'is-target' : '' }}">
                                                        <span class="am-domain-name">
                                                            {{ strtoupper(str_replace('_', ' ', $domainKey)) }}
                                                            @if($isTargetDomain)<span class="am-chip am-chip--accent am-chip--xs">target</span>@endif
                                                        </span>
                                                        <span class="am-domain-value">{{ $score }}%</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                @endif
                            </div>
                        @elseif($isAnalyzing)
                            <div class="am-card am-skeleton">
                                <div class="am-skel am-skel--title"></div>
                                <div class="am-skel-grid">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="am-skel am-skel--stat"></div>
                                    @endfor
                                </div>
                                <div class="am-skel am-skel--line"></div>
                                <div class="am-skel am-skel--line am-skel--short"></div>
                            </div>
                        @else
                            <div class="am-card am-empty">
                                <span class="am-empty-ico"><x-heroicon-o-sparkles class="w-6 h-6" /></span>
                                <h4 class="am-title-lg">Belum ada hasil analisis</h4>
                                <p class="am-hint am-empty-hint">Pilih target role di panel kiri, tempel deskripsi lowongan bila ada, lalu jalankan analisis untuk melihat skor, checklist requirement, dan langkah perbaikan.</p>
                                <x-filament::button wire:click="analyzeJobMatch" color="primary">
                                    <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                                    <span>Jalankan Analisis AI</span>
                                </x-filament::button>
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                        @php
                            $profModalText = match((int) $skill_proficiency) {
                                1 => '1 — Beginner',
                                2 => '2 — Developing',
                                3 => '3 — Intermediate',
                                4 => '4 — Advanced',
                                5 => '5 — Expert',
                                default => (string) $skill_proficiency,
                            };
                        @endphp
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5 uppercase">Proficiency Level: <span class="text-cyan-400 font-mono font-bold">{{ $profModalText }}</span></label>
                        <input type="range" wire:model.live="skill_proficiency" min="1" max="5" step="1" class="w-full accent-cyan-500 cursor-pointer h-2 bg-[#1e1e2d] border border-[#2d2d3a] rounded-full" />
                        <div class="flex justify-between text-[10px] text-slate-500 font-mono mt-1 uppercase font-bold">
                            <span>1 (Beginner)</span>
                            <span>3 (Intermediate)</span>
                            <span>5 (Expert)</span>
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
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
                                                     <h5 class="text-sm font-bold text-white truncate">{{ $reviewSection !== '' ? $reviewSection : 'Experience Review' }}</h5>
                                                 </div>
                                                 <div class="p-4 space-y-3">
                                                     @if($reviewStrength !== '')
                                                         <div class="flex items-start gap-2.5">
                                                             <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-emerald-400 mt-0.5 flex-shrink-0" />
                                                             <div class="min-w-0">
                                                                 <span class="block text-[10px] uppercase font-bold tracking-wider text-emerald-400 mb-0.5">Strength</span>
                                                                 <p class="text-xs text-slate-300 leading-relaxed">{{ $reviewStrength }}</p>
                                                             </div>
                                                         </div>
                                                     @endif
                                                     @if($reviewGap !== '')
                                                         <div class="flex items-start gap-2.5">
                                                             <x-heroicon-o-x-circle class="w-3.5 h-3.5 text-rose-400 mt-0.5 flex-shrink-0" />
                                                             <div class="min-w-0">
                                                                 <span class="block text-[10px] uppercase font-bold tracking-wider text-rose-400 mb-0.5">Gap</span>
                                                                 <p class="text-xs text-slate-300 leading-relaxed">{{ $reviewGap }}</p>
                                                             </div>
                                                         </div>
                                                     @endif
                                                     @if($reviewHint !== '')
                                                         <div class="flex items-start gap-2.5 bg-sky-950/20 border border-sky-500/10 rounded-lg p-2.5">
                                                             <x-heroicon-o-pencil-square class="w-3.5 h-3.5 text-sky-400 mt-0.5 flex-shrink-0" />
                                                             <div class="min-w-0">
                                                                 <span class="block text-[10px] uppercase font-bold tracking-wider text-sky-400 mb-0.5">Rewrite Hint</span>
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

        /* ============================================================
           AI JOB MATCH — Muted, scoped design (light + dark)
           Only affects the aimatch tab via .am-scope
           ============================================================ */
        .am-scope {
            --am-bg: #ffffff;
            --am-bg-soft: #f8fafc;
            --am-bg-sunken: #f1f5f9;
            --am-border: #e2e8f0;
            --am-border-strong: #cbd5e1;
            --am-text: #1e293b;
            --am-text-muted: #475569;
            --am-text-faint: #64748b;
            --am-accent: #2563eb;
            --am-accent-soft: #eff6ff;
            --am-accent-border: #bfdbfe;
            --am-ok: #047857;
            --am-ok-soft: #ecfdf5;
            --am-ok-border: #a7f3d0;
            --am-warn: #b45309;
            --am-warn-soft: #fffbeb;
            --am-warn-border: #fde68a;
            --am-bad: #b91c1c;
            --am-bad-soft: #fef2f2;
            --am-bad-border: #fecaca;
            color: var(--am-text);
        }

        .dark .am-scope {
            --am-bg: #1e293b;
            --am-bg-soft: #172033;
            --am-bg-sunken: #0f172a;
            --am-border: #334155;
            --am-border-strong: #475569;
            --am-text: #e2e8f0;
            --am-text-muted: #94a3b8;
            --am-text-faint: #7c8aa0;
            --am-accent: #60a5fa;
            --am-accent-soft: rgba(96, 165, 250, 0.10);
            --am-accent-border: rgba(96, 165, 250, 0.30);
            --am-ok: #34d399;
            --am-ok-soft: rgba(52, 211, 153, 0.10);
            --am-ok-border: rgba(52, 211, 153, 0.28);
            --am-warn: #fbbf24;
            --am-warn-soft: rgba(251, 191, 36, 0.10);
            --am-warn-border: rgba(251, 191, 36, 0.28);
            --am-bad: #fb7185;
            --am-bad-soft: rgba(251, 113, 133, 0.10);
            --am-bad-border: rgba(251, 113, 133, 0.28);
        }

        .am-scope .am-tone-ok { color: var(--am-ok) !important; }
        .am-scope .am-tone-warn { color: var(--am-warn) !important; }
        .am-scope .am-tone-bad { color: var(--am-bad) !important; }
        .am-scope .am-tone-accent { color: var(--am-accent) !important; }
        .am-scope .am-tone-neutral { color: var(--am-text-muted) !important; }

        .am-scope .am-tone-bg-ok { background: var(--am-ok) !important; }
        .am-scope .am-tone-bg-warn { background: var(--am-warn) !important; }
        .am-scope .am-tone-bg-bad { background: var(--am-bad) !important; }
        .am-scope .am-tone-bg-accent { background: var(--am-accent) !important; }
        .am-scope .am-tone-bg-neutral { background: var(--am-border-strong) !important; }

        .am-scope .am-ring-track { color: var(--am-border-strong); opacity: 0.5; }

        /* Layout */
        .am-scope .am-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        @media (min-width: 1024px) {
            .am-scope .am-layout { grid-template-columns: minmax(320px, 360px) 1fr; align-items: start; }
            .am-scope .am-col-input { position: sticky; top: 1rem; }
        }
        .am-scope .am-col-input,
        .am-scope .am-col-result { display: flex; flex-direction: column; gap: 1.25rem; min-width: 0; }

        /* Cards */
        .am-scope .am-card {
            background: var(--am-bg);
            border: 1px solid var(--am-border);
            border-radius: 14px;
            padding: 1.15rem 1.25rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        .dark .am-scope .am-card { box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25); }

        .am-scope .am-card-head { display: flex; align-items: flex-start; gap: 0.65rem; margin-bottom: 0.9rem; }
        .am-scope .am-ico {
            display: inline-flex; align-items: center; justify-content: center;
            width: 2rem; height: 2rem; flex-shrink: 0;
            border-radius: 9px;
            border: 1px solid var(--am-accent-border);
            background: var(--am-accent-soft);
            color: var(--am-accent);
        }
        .am-scope .am-title { font-size: 0.95rem; font-weight: 700; color: var(--am-text); line-height: 1.2; }
        .am-scope .am-title-lg { font-size: 1.05rem; font-weight: 700; color: var(--am-text); line-height: 1.3; }
        .am-scope .am-hint { font-size: 0.78rem; color: var(--am-text-faint); line-height: 1.5; margin-top: 0.15rem; }
        .am-scope .am-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--am-text-muted); margin-bottom: 0.4rem; }
        .am-scope .am-optional { font-weight: 500; color: var(--am-text-faint); font-size: 0.72rem; }
        .am-scope .am-field { margin-bottom: 1rem; }

        .am-scope .am-input,
        .am-scope .am-select,
        .am-scope .am-textarea {
            width: 100%;
            background: var(--am-bg-soft);
            border: 1px solid var(--am-border-strong);
            border-radius: 10px;
            color: var(--am-text);
            padding: 0.6rem 0.8rem;
            font-size: 0.85rem;
            line-height: 1.5;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .am-scope .am-textarea { font-family: var(--font-mono, monospace); font-size: 0.8rem; resize: vertical; }
        .am-scope .am-input:focus,
        .am-scope .am-select:focus,
        .am-scope .am-textarea:focus {
            outline: none;
            border-color: var(--am-accent);
            box-shadow: 0 0 0 3px var(--am-accent-soft);
        }
        .am-scope .am-input::placeholder,
        .am-scope .am-textarea::placeholder { color: var(--am-text-faint); }

        /* Chips */
        .am-scope .am-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.72rem; font-weight: 600; line-height: 1.2;
            border: 1px solid transparent;
        }
        .am-scope .am-chip--xs { padding: 0.15rem 0.5rem; font-size: 0.68rem; }
        .am-scope .am-chip--ok { background: var(--am-ok-soft); color: var(--am-ok); border-color: var(--am-ok-border); }
        .am-scope .am-chip--warn { background: var(--am-warn-soft); color: var(--am-warn); border-color: var(--am-warn-border); }
        .am-scope .am-chip--bad { background: var(--am-bad-soft); color: var(--am-bad); border-color: var(--am-bad-border); }
        .am-scope .am-chip--accent { background: var(--am-accent-soft); color: var(--am-accent); border-color: var(--am-accent-border); }
        .am-scope .am-chip--neutral { background: var(--am-bg-sunken); color: var(--am-text-muted); border-color: var(--am-border); }
        .am-scope .am-chip-strong { font-weight: 700; }

        /* History */
        .am-scope .am-history { display: flex; flex-direction: column; gap: 0.4rem; }
        .am-scope .am-history-item {
            display: flex; align-items: center; justify-content: space-between; gap: 0.6rem;
            width: 100%; text-align: left;
            padding: 0.55rem 0.7rem;
            border-radius: 10px;
            border: 1px solid var(--am-border);
            background: var(--am-bg-soft);
            transition: border-color 0.15s ease, background 0.15s ease;
            cursor: pointer;
        }
        .am-scope .am-history-item:hover { border-color: var(--am-border-strong); }
        .am-scope .am-history-item.is-active { border-color: var(--am-accent-border); background: var(--am-accent-soft); }
        .am-scope .am-history-main { display: flex; flex-direction: column; gap: 0.1rem; min-width: 0; }
        .am-scope .am-history-role { font-size: 0.8rem; font-weight: 600; color: var(--am-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .am-scope .am-history-date { font-size: 0.68rem; color: var(--am-text-faint); font-family: var(--font-mono, monospace); }

        /* Summary */
        .am-scope .am-summary-top { display: flex; align-items: center; gap: 1.15rem; flex-wrap: wrap; }
        .am-scope .am-hero-ring { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; flex-shrink: 0; }
        .am-scope .am-ring-caption { font-size: 0.66rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--am-text-faint); }
        .am-scope .am-summary-body { flex: 1; min-width: 200px; }
        .am-scope .am-summary-head { display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; }
        .am-scope .am-summary-tagline { font-size: 0.8rem; color: var(--am-text-muted); line-height: 1.55; margin-top: 0.35rem; }
        .am-scope .am-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.6rem; }
        .am-scope .am-summary-text {
            margin-top: 0.9rem; padding-top: 0.85rem;
            border-top: 1px solid var(--am-border);
            font-size: 0.8rem; color: var(--am-text-muted); line-height: 1.65;
        }
        .am-scope .am-quick-grid {
            display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;
            margin-top: 0.9rem;
        }
        @media (min-width: 640px) { .am-scope .am-quick-grid { grid-template-columns: repeat(4, 1fr); } }
        .am-scope .am-quick {
            display: flex; flex-direction: column; gap: 0.15rem;
            padding: 0.5rem 0.6rem; border-radius: 10px;
            background: var(--am-bg-soft); border: 1px solid var(--am-border);
        }
        .am-scope .am-quick-value { font-family: var(--font-mono, monospace); font-weight: 700; font-size: 1rem; }
        .am-scope .am-quick-label { font-size: 0.68rem; color: var(--am-text-faint); }

        /* Metrics */
        .am-scope .am-metrics { display: grid; grid-template-columns: 1fr; gap: 0.7rem; }
        @media (min-width: 640px) { .am-scope .am-metrics { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1280px) { .am-scope .am-metrics { grid-template-columns: repeat(3, 1fr); } }
        .am-scope .am-metric { padding: 0.6rem 0.7rem; border-radius: 10px; border: 1px solid var(--am-border); background: var(--am-bg-soft); }
        .am-scope .am-metric.is-core { border-color: var(--am-accent-border); background: var(--am-accent-soft); }
        .am-scope .am-metric-top { display: flex; align-items: center; justify-content: space-between; gap: 0.4rem; }
        .am-scope .am-metric-label { font-size: 0.78rem; font-weight: 600; color: var(--am-text); }
        .am-scope .am-meter { width: 100%; height: 5px; border-radius: 999px; background: var(--am-bg-sunken); overflow: hidden; margin: 0.45rem 0; }
        .dark .am-scope .am-meter { background: rgba(148, 163, 184, 0.18); }
        .am-scope .am-meter-fill { height: 100%; border-radius: 999px; }
        .am-scope .am-metric-bottom { display: flex; align-items: baseline; justify-content: space-between; gap: 0.5rem; }
        .am-scope .am-metric-desc { font-size: 0.68rem; color: var(--am-text-faint); line-height: 1.4; }
        .am-scope .am-metric-value { font-family: var(--font-mono, monospace); font-weight: 700; font-size: 0.85rem; color: var(--am-text); flex-shrink: 0; }

        /* Collapsible sections */
        .am-scope .am-section { background: var(--am-bg); border: 1px solid var(--am-border); border-radius: 14px; overflow: hidden; }
        .am-scope .am-section-head {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.85rem 1.1rem;
            cursor: pointer; user-select: none;
            list-style: none;
        }
        .am-scope .am-section-head::-webkit-details-marker { display: none; }
        .am-scope .am-section-title { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 700; color: var(--am-text); }
        .am-scope .am-section-title svg { color: var(--am-accent); }
        .am-scope .am-section-meta { margin-left: auto; font-size: 0.7rem; color: var(--am-text-faint); }
        .am-scope .am-chevron { color: var(--am-text-faint); transition: transform 0.2s ease; flex-shrink: 0; }
        .am-scope details[open] > .am-section-head .am-chevron { transform: rotate(180deg); }
        .am-scope .am-subsection[open] > .am-subsection-head .am-chevron { transform: rotate(180deg); }
        .am-scope .am-section-head:hover { background: var(--am-bg-soft); }
        .am-scope .am-section-body { padding: 0 1.1rem 1.1rem; display: flex; flex-direction: column; gap: 1rem; }

        .am-scope .am-subtitle { font-size: 0.78rem; font-weight: 700; color: var(--am-text); text-transform: uppercase; letter-spacing: 0.04em; display: flex; align-items: center; gap: 0.4rem; }
        .am-scope .am-subtitle-count { font-size: 0.68rem; font-weight: 600; color: var(--am-text-faint); }

        /* Coverage */
        .am-scope .am-coverage-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
        @media (min-width: 640px) { .am-scope .am-coverage-grid { grid-template-columns: repeat(4, 1fr); } }
        .am-scope .am-coverage { padding: 0.55rem; border-radius: 10px; border: 1px solid var(--am-border); background: var(--am-bg-soft); text-align: center; }
        .am-scope .am-coverage-value { display: block; font-family: var(--font-mono, monospace); font-weight: 700; font-size: 1.05rem; color: var(--am-text); }
        .am-scope .am-coverage-label { display: block; font-size: 0.66rem; color: var(--am-text-faint); margin-top: 0.15rem; }

        /* Callouts */
        .am-scope .am-callout { display: flex; flex-direction: column; gap: 0.35rem; padding: 0.7rem 0.85rem; border-radius: 10px; border: 1px solid var(--am-border); background: var(--am-bg-soft); font-size: 0.78rem; color: var(--am-text-muted); line-height: 1.55; }
        .am-scope .am-callout strong { color: var(--am-text); }
        .am-scope .am-callout--accent { border-color: var(--am-accent-border); background: var(--am-accent-soft); }
        .am-scope .am-callout--ok { border-color: var(--am-ok-border); background: var(--am-ok-soft); }
        .am-scope .am-callout--warn { border-color: var(--am-warn-border); background: var(--am-warn-soft); }
        .am-scope .am-callout--bad { border-color: var(--am-bad-border); background: var(--am-bad-soft); }

        /* Requirements */
        .am-scope .am-req-group { display: flex; flex-direction: column; gap: 0.55rem; }
        .am-scope .am-req-list { display: flex; flex-direction: column; gap: 0.5rem; }
        .am-scope .am-req { padding: 0.65rem 0.75rem; border-radius: 10px; border: 1px solid var(--am-border); background: var(--am-bg-soft); }
        .am-scope .am-req-head { display: flex; align-items: center; justify-content: space-between; gap: 0.6rem; }
        .am-scope .am-req-name { font-size: 0.82rem; font-weight: 600; color: var(--am-text); }
        .am-scope .am-quote {
            margin-top: 0.5rem; padding: 0.4rem 0.6rem;
            border-left: 3px solid var(--am-border-strong);
            background: var(--am-bg-sunken);
            border-radius: 0 8px 8px 0;
            font-family: var(--font-mono, monospace); font-size: 0.72rem; color: var(--am-text-muted); line-height: 1.5;
        }
        .dark .am-scope .am-quote { background: rgba(15, 23, 42, 0.5); }
        .am-scope .am-req-reason { margin-top: 0.4rem; font-size: 0.72rem; color: var(--am-text-faint); line-height: 1.5; }

        /* Subsection (formula) */
        .am-scope .am-subsection { border: 1px solid var(--am-border); border-radius: 10px; background: var(--am-bg-soft); }
        .am-scope .am-subsection-head { display: flex; align-items: center; gap: 0.45rem; padding: 0.6rem 0.8rem; cursor: pointer; list-style: none; font-size: 0.76rem; font-weight: 600; color: var(--am-text-muted); }
        .am-scope .am-subsection-head::-webkit-details-marker { display: none; }
        .am-scope .am-subsection-body { padding: 0 0.8rem 0.8rem; display: flex; flex-direction: column; gap: 0.5rem; font-family: var(--font-mono, monospace); font-size: 0.72rem; color: var(--am-text-muted); line-height: 1.55; }
        .am-scope .am-band-list { list-style: none; display: flex; flex-direction: column; gap: 0.3rem; font-family: var(--font-sans, sans-serif); }
        .am-scope .am-subsection-note { font-family: var(--font-sans, sans-serif); color: var(--am-text-faint); }

        /* Keywords */
        .am-scope .am-kw-grid { display: grid; grid-template-columns: 1fr; gap: 0.6rem; }
        @media (min-width: 768px) { .am-scope .am-kw-grid { grid-template-columns: repeat(3, 1fr); } }
        .am-scope .am-kw { padding: 0.7rem 0.75rem; border-radius: 10px; border: 1px solid var(--am-border); }
        .am-scope .am-kw--ok { background: var(--am-ok-soft); border-color: var(--am-ok-border); }
        .am-scope .am-kw--warn { background: var(--am-warn-soft); border-color: var(--am-warn-border); }
        .am-scope .am-kw--bad { background: var(--am-bad-soft); border-color: var(--am-bad-border); }
        .am-scope .am-kw-title { display: flex; align-items: center; gap: 0.35rem; font-size: 0.74rem; font-weight: 700; margin-bottom: 0.5rem; }
        .am-scope .am-kw--ok .am-kw-title { color: var(--am-ok); }
        .am-scope .am-kw--warn .am-kw-title { color: var(--am-warn); }
        .am-scope .am-kw--bad .am-kw-title { color: var(--am-bad); }
        .am-scope .am-chip-wrap { display: flex; flex-wrap: wrap; gap: 0.35rem; }
        .am-scope .am-empty-note { font-size: 0.72rem; color: var(--am-text-faint); }

        /* Focus / lists */
        .am-scope .am-focus { display: flex; flex-direction: column; gap: 0.5rem; }
        .am-scope .am-numbered { list-style: none; display: flex; flex-direction: column; gap: 0.45rem; }
        .am-scope .am-numbered li { display: flex; align-items: flex-start; gap: 0.55rem; }
        .am-scope .am-numbered-idx {
            display: inline-flex; align-items: center; justify-content: center;
            width: 1.25rem; height: 1.25rem; flex-shrink: 0; margin-top: 0.05rem;
            border-radius: 999px; background: var(--am-accent-soft); border: 1px solid var(--am-accent-border);
            color: var(--am-accent); font-size: 0.68rem; font-weight: 700; font-family: var(--font-mono, monospace);
        }
        .am-scope .am-numbered-text { font-size: 0.78rem; color: var(--am-text-muted); line-height: 1.55; }
        .am-scope .am-bullets { list-style: none; display: flex; flex-direction: column; gap: 0.35rem; }
        .am-scope .am-bullets li { position: relative; padding-left: 0.9rem; font-size: 0.78rem; color: var(--am-text-muted); line-height: 1.55; }
        .am-scope .am-bullets li::before { content: ''; position: absolute; left: 0; top: 0.5em; width: 5px; height: 5px; border-radius: 999px; background: var(--am-border-strong); }

        /* Two-column */
        .am-scope .am-two-col { display: grid; grid-template-columns: 1fr; gap: 0.6rem; }
        @media (min-width: 768px) { .am-scope .am-two-col { grid-template-columns: repeat(2, 1fr); } }
        .am-scope .am-two-col .am-subtitle { margin-bottom: 0.5rem; }

        /* Experience review */
        .am-scope .am-review-list { display: flex; flex-direction: column; gap: 0.6rem; }
        .am-scope .am-review { border: 1px solid var(--am-border); border-radius: 10px; padding: 0.75rem 0.85rem; background: var(--am-bg-soft); display: flex; flex-direction: column; gap: 0.55rem; }
        .am-scope .am-review-head { font-size: 0.82rem; font-weight: 700; color: var(--am-text); padding-bottom: 0.45rem; border-bottom: 1px solid var(--am-border); }
        .am-scope .am-review-row { display: flex; align-items: flex-start; gap: 0.55rem; }
        .am-scope .am-review-row p { font-size: 0.76rem; color: var(--am-text-muted); line-height: 1.55; }
        .am-scope .am-review-row--hint { padding: 0.5rem 0.6rem; border-radius: 8px; border: 1px solid var(--am-accent-border); background: var(--am-accent-soft); }
        .am-scope .am-review-tag { flex-shrink: 0; font-size: 0.64rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding-top: 0.1rem; min-width: 5.5rem; }
        .am-scope .am-review-note { display: flex; align-items: flex-start; gap: 0.5rem; padding: 0.6rem 0.7rem; border-radius: 10px; border: 1px solid var(--am-border); background: var(--am-bg-soft); color: var(--am-text-muted); font-size: 0.76rem; line-height: 1.55; }
        .am-scope .am-review-note svg { color: var(--am-accent); flex-shrink: 0; margin-top: 0.1rem; }

        /* Domain */
        .am-scope .am-domain-grid { display: grid; grid-template-columns: 1fr; gap: 0.5rem; }
        @media (min-width: 768px) { .am-scope .am-domain-grid { grid-template-columns: repeat(3, 1fr); } }
        .am-scope .am-domain { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.55rem 0.7rem; border-radius: 10px; border: 1px solid var(--am-border); background: var(--am-bg-soft); }
        .am-scope .am-domain.is-target { border-color: var(--am-accent-border); background: var(--am-accent-soft); }
        .am-scope .am-domain-name { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 600; color: var(--am-text-muted); }
        .am-scope .am-domain-value { font-family: var(--font-mono, monospace); font-weight: 700; font-size: 0.8rem; color: var(--am-text); }

        /* Skeleton + empty */
        .am-scope .am-skeleton { display: flex; flex-direction: column; gap: 1rem; }
        .am-scope .am-skel { background: var(--am-bg-sunken); border-radius: 8px; height: 0.9rem; animation: am-pulse 1.6s ease-in-out infinite; }
        .dark .am-scope .am-skel { background: rgba(148, 163, 184, 0.14); }
        .am-scope .am-skel--title { width: 45%; height: 1.2rem; }
        .am-scope .am-skel--line { width: 100%; }
        .am-scope .am-skel--short { width: 65%; }
        .am-scope .am-skel-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
        @media (min-width: 640px) { .am-scope .am-skel-grid { grid-template-columns: repeat(4, 1fr); } }
        .am-scope .am-skel--stat { height: 4.5rem; }
        @keyframes am-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.45; } }

        .am-scope .am-empty { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.6rem; padding: 2.5rem 1.5rem; }
        .am-scope .am-empty-ico { display: inline-flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; border-radius: 12px; background: var(--am-bg-sunken); border: 1px solid var(--am-border); color: var(--am-text-faint); }
        .am-scope .am-empty-hint { max-width: 26rem; }

        /* Progress stepper */
        .am-scope .am-progress-bar { height: 5px; border-radius: 999px; background: var(--am-bg-sunken); overflow: hidden; margin-bottom: 0.85rem; }
        .dark .am-scope .am-progress-bar { background: rgba(148, 163, 184, 0.18); }
        .am-scope .am-progress-bar-fill { height: 100%; border-radius: 999px; background: var(--am-accent); transition: width 0.5s ease; }
        .am-scope .am-steps { list-style: none; display: flex; flex-direction: column; gap: 0.7rem; }
        .am-scope .am-step { display: flex; align-items: flex-start; gap: 0.6rem; }
        .am-scope .am-step-mark {
            display: inline-flex; align-items: center; justify-content: center;
            width: 1.5rem; height: 1.5rem; flex-shrink: 0; margin-top: 0.05rem;
            border-radius: 999px; border: 1px solid var(--am-border-strong);
            background: var(--am-bg-soft); color: var(--am-text-faint);
            font-size: 0.7rem; font-weight: 700; font-family: var(--font-mono, monospace);
        }
        .am-scope .am-step.is-active .am-step-mark { border-color: var(--am-accent); color: var(--am-accent); background: var(--am-accent-soft); }
        .am-scope .am-step.is-done .am-step-mark { border-color: var(--am-ok); color: var(--am-ok); background: var(--am-ok-soft); }
        .am-scope .am-step-text { display: flex; flex-direction: column; gap: 0.05rem; }
        .am-scope .am-step-title { font-size: 0.8rem; font-weight: 600; color: var(--am-text-faint); }
        .am-scope .am-step.is-active .am-step-title { color: var(--am-text); }
        .am-scope .am-step.is-done .am-step-title { color: var(--am-text-muted); }
        .am-scope .am-step-desc { font-size: 0.7rem; color: var(--am-text-faint); line-height: 1.45; }

        @media (prefers-reduced-motion: reduce) {
            .am-scope .am-skel { animation: none; }
            .am-scope .am-chevron,
            .am-scope .am-progress-bar-fill { transition: none; }
        }

        .am-scope .am-section-head:focus-visible,
        .am-scope .am-subsection-head:focus-visible,
        .am-scope .am-history-item:focus-visible {
            outline: 2px solid var(--am-accent);
            outline-offset: 2px;
        }
    </style>
</x-filament-panels::page>
