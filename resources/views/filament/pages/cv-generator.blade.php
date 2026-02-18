<x-filament-panels::page>
    @vite('resources/css/app.css')

    {{-- SVG Gradient Definitions for Circular Progress --}}
    <svg style="position: absolute; width: 0; height: 0">
        <defs>
            <linearGradient id="atsGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="formatGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="aboutmeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#8b5cf6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="jobmatchGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#f59e0b;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#d97706;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>

    <div class="cv-generator-wrapper space-y-10">
        {{-- Action Buttons with Premium Design --}}
        <div class="glass-card premium-actions p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-filament::button wire:click="analyze" color="primary" :loading="$isAnalyzing"
                    class="action-btn btn-analyze">
                    <x-heroicon-o-sparkles class="w-5 h-5 mr-2" />
                    <span>Analyze CV</span>
                </x-filament::button>

                <x-filament::button wire:click="analyzeJobMatch" color="warning" class="action-btn btn-match">
                    <x-heroicon-o-scale class="w-5 h-5 mr-2" />
                    <span>Match Job</span>
                </x-filament::button>

                <x-filament::button wire:click="downloadCv" color="success" class="action-btn btn-download">
                    <x-heroicon-o-document-arrow-down class="w-5 h-5 mr-2" />
                    <span>Download PDF</span>
                </x-filament::button>

                <x-filament::button wire:click="downloadExport" color="gray" class="action-btn btn-export">
                    <x-heroicon-o-chevron-down class="w-5 h-5 mr-2" />
                    <span>Export Options</span>
                </x-filament::button>
            </div>
        </div>

        {{-- Template Selector with Premium Design --}}
        <div class="glass-card template-selector-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="icon-wrapper">
                        <x-heroicon-o-document-text class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold gradient-text-custom">Choose CV Template</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="template-selector-wrapper">
                    <label class="input-label">Select Template</label>
                    <div class="custom-select-wrapper">
                        <select wire:model.live="selectedTemplate" class="custom-select">
                            @foreach(config('cv-templates') as $key => $template)
                                <option value="{{ $key }}">{{ $template['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(isset(config('cv-templates')[$selectedTemplate]))
                        <p class="template-description">
                            <x-heroicon-o-information-circle class="w-4 h-4 inline mr-1" />
                            {{ config('cv-templates')[$selectedTemplate]['description'] }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Job Description Matching with Premium Design --}}
        <div class="glass-card job-matcher-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="icon-wrapper">
                        <x-heroicon-o-briefcase class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold gradient-text-custom">Job Description Matching</h3>
                </div>
                <span class="optional-badge">Optional</span>
            </div>
            <div class="card-body">
                <textarea wire:model="jobDescription" rows="6"
                    placeholder="Paste the job description here to analyze how well your CV matches..."
                    class="custom-textarea"></textarea>
                <x-filament::button wire:click="analyzeJobMatch" color="primary" class="mt-4 analyze-match-btn">
                    <x-heroicon-o-bolt class="w-5 h-5 mr-2" />
                    Analyze Match
                </x-filament::button>
            </div>
        </div>

        @if($analysisResult)
            <div class="glass-card analysis-results-card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="icon-wrapper">
                            <x-heroicon-o-chart-bar class="w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold gradient-text-custom">Analysis Results</h3>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Circular Score Indicators --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                        {{-- ATS Score --}}
                        <div class="score-circle-wrapper">
                            <div class="score-circle" data-score="{{ $analysisResult['ats_score'] }}">
                                <svg viewBox="0 0 120 120" class="circular-chart">
                                    <circle class="circle-bg" cx="60" cy="60" r="54" />
                                    <circle class="circle-progress ats-progress" cx="60" cy="60" r="54"
                                        style="--score: {{ $analysisResult['ats_score'] }}" />
                                    <text x="60" y="60" class="score-text">
                                        {{ $analysisResult['ats_score'] }}
                                        <tspan class="percent-sign">%</tspan>
                                    </text>
                                </svg>
                            </div>
                            <p class="score-label">ATS Score</p>
                        </div>

                        {{-- Format Score --}}
                        <div class="score-circle-wrapper">
                            <div class="score-circle" data-score="{{ $analysisResult['format_score'] }}">
                                <svg viewBox="0 0 120 120" class="circular-chart">
                                    <circle class="circle-bg" cx="60" cy="60" r="54" />
                                    <circle class="circle-progress format-progress" cx="60" cy="60" r="54"
                                        style="--score: {{ $analysisResult['format_score'] }}" />
                                    <text x="60" y="60" class="score-text">
                                        {{ $analysisResult['format_score'] }}
                                        <tspan class="percent-sign">%</tspan>
                                    </text>
                                </svg>
                            </div>
                            <p class="score-label">Format Score</p>
                        </div>

                        {{-- About Me Score --}}
                        <div class="score-circle-wrapper">
                            <div class="score-circle" data-score="{{ $analysisResult['about_me_score'] ?? 0 }}">
                                <svg viewBox="0 0 120 120" class="circular-chart">
                                    <circle class="circle-bg" cx="60" cy="60" r="54" />
                                    <circle class="circle-progress aboutme-progress" cx="60" cy="60" r="54"
                                        style="--score: {{ $analysisResult['about_me_score'] ?? 0 }}" />
                                    <text x="60" y="60" class="score-text">
                                        {{ $analysisResult['about_me_score'] ?? '-' }}
                                        <tspan class="percent-sign">
                                            {{ isset($analysisResult['about_me_score']) ? '%' : '' }}
                                        </tspan>
                                    </text>
                                </svg>
                            </div>
                            <p class="score-label">About Me</p>
                        </div>

                        {{-- Job Match Score --}}
                        <div class="score-circle-wrapper">
                            <div class="score-circle" data-score="{{ $analysisResult['job_match_score'] ?? 0 }}">
                                <svg viewBox="0 0 120 120" class="circular-chart">
                                    <circle class="circle-bg" cx="60" cy="60" r="54" />
                                    <circle class="circle-progress jobmatch-progress" cx="60" cy="60" r="54"
                                        style="--score: {{ $analysisResult['job_match_score'] ?? 0 }}" />
                                    <text x="60" y="60" class="score-text">
                                        {{ $analysisResult['job_match_score'] ?? '-' }}
                                        <tspan class="percent-sign">
                                            {{ isset($analysisResult['job_match_score']) ? '%' : '' }}
                                        </tspan>
                                    </text>
                                </svg>
                            </div>
                            <p class="score-label">Job Match</p>
                        </div>
                    </div>

                    {{-- Industry Compatibility --}}
                    @if(isset($analysisResult['industry_match']))
                        <div class="mb-8">
                            <h4 class="section-subtitle">Industry Compatibility</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($analysisResult['industry_match'] as $industry => $score)
                                    <div class="industry-match-card">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="industry-name">{{ ucfirst($industry) }}</span>
                                            <span class="industry-score">{{ $score }}%</span>
                                        </div>
                                        <div class="progress-bar-wrapper">
                                            <div class="progress-bar-fill" style="width: {{ $score }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Keywords Analysis --}}
                    <div class="mb-8">
                        <h4 class="section-subtitle">Keywords Analysis</h4>

                        <div class="keywords-section mb-6">
                            <div class="keywords-header">
                                <x-heroicon-o-check-circle class="w-5 h-5" />
                                <span class="keywords-title">Found Keywords</span>
                                <span class="keywords-count">{{ count($analysisResult['keywords']['found'] ?? []) }}</span>
                            </div>
                            <div class="keywords-list">
                                @foreach(($analysisResult['keywords']['found'] ?? []) as $keyword)
                                    <span class="keyword-badge keyword-found">{{ $keyword }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="keywords-section">
                            <div class="keywords-header missing">
                                <x-heroicon-o-x-circle class="w-5 h-5" />
                                <span class="keywords-title">Missing Keywords</span>
                                <span
                                    class="keywords-count">{{ count($analysisResult['keywords']['missing'] ?? []) }}</span>
                            </div>
                            <div class="keywords-list">
                                @foreach(($analysisResult['keywords']['missing'] ?? []) as $keyword)
                                    <span class="keyword-badge keyword-missing">{{ $keyword }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Suggestions --}}
                    <div class="mb-8">
                        <h4 class="section-subtitle">Suggestions for Improvement</h4>
                        <div class="suggestions-list">
                            @foreach(($analysisResult['suggestions'] ?? []) as $index => $suggestion)
                                <div class="suggestion-item" style="animation-delay: {{ $index * 0.1 }}s">
                                    <div class="suggestion-icon">
                                        <x-heroicon-o-light-bulb class="w-5 h-5" />
                                    </div>
                                    <p class="suggestion-text">{{ $suggestion }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Summary --}}
                    @if(isset($analysisResult['summary']))
                        <div class="summary-card">
                            <div class="summary-icon">
                                <x-heroicon-o-document-text class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="summary-title">Executive Summary</h4>
                                <p class="summary-text">{{ $analysisResult['summary'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Live CV Preview --}}
        <div class="glass-card preview-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="icon-wrapper">
                        <x-heroicon-o-eye class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold gradient-text-custom">Live CV Preview</h3>
                </div>
                <span class="template-badge">{{ ucfirst($selectedTemplate) }} Template</span>
            </div>
            <div class="card-body p-0">
                <div class="preview-iframe-wrapper">
                    <iframe src="{{ route('cv.preview') }}?template={{ $selectedTemplate }}"
                        class="preview-iframe"></iframe>
                </div>
            </div>
        </div>

        {{-- Analysis History --}}
        @if($history && count($history) > 0)
            <div class="glass-card history-card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="icon-wrapper">
                            <x-heroicon-o-clock class="w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold gradient-text-custom">Analysis History</h3>
                    </div>
                    <span class="history-count">{{ count($history) }} Records</span>
                </div>
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>ATS Score</th>
                                    <th>Format</th>
                                    <th>About Me</th>
                                    <th>Job Match</th>
                                    <th>Template</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <span
                                                class="score-badge {{ $item->ats_score >= 80 ? 'excellent' : ($item->ats_score >= 50 ? 'good' : 'poor') }}">
                                                {{ $item->ats_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="score-badge {{ $item->format_score >= 80 ? 'excellent' : ($item->format_score >= 50 ? 'good' : 'poor') }}">
                                                {{ $item->format_score }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->about_me_score)
                                                <span
                                                    class="score-badge {{ $item->about_me_score >= 80 ? 'excellent' : ($item->about_me_score >= 50 ? 'good' : 'poor') }}">{{ $item->about_me_score }}%</span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->job_match_score)
                                                <span
                                                    class="score-badge {{ $item->job_match_score >= 80 ? 'excellent' : ($item->job_match_score >= 50 ? 'good' : 'poor') }}">{{ $item->job_match_score }}%</span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="template-tag">{{ ucfirst($item->profile->cv_template ?? 'ats') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* ========================================
           CV Generator Premium Styles
           ======================================== */

        /* CSS Variables */
        :root {
            --cv-gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --cv-gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --cv-gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --cv-gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc3626 100%);
            --cv-gradient-info: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);

            --cv-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
            --cv-shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --cv-shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.15);
            --cv-shadow-glow: 0 0 20px rgba(102, 126, 234, 0.3);

            --cv-border-radius: 16px;
            --cv-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark {
            --cv-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
            --cv-shadow-md: 0 4px 16px rgba(0, 0, 0, 0.4);
            --cv-shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.5);
            --cv-shadow-glow: 0 0 30px rgba(102, 126, 234, 0.2);
        }

        /* Wrapper */
        .cv-generator-wrapper {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-radius: var(--cv-border-radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--cv-shadow-md);
            transition: var(--cv-transition);
            overflow: hidden;
        }

        .dark .glass-card {
            background: rgba(28, 33, 40, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card:hover {
            box-shadow: var(--cv-shadow-lg);
            transform: translateY(-2px);
        }

        /* Card Header */
        .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(229, 231, 235, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Icon Wrapper */
        .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--cv-gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Gradient Text */
        .gradient-text-custom {
            background: var(--cv-gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Card Body */
        .card-body {
            padding: 2rem;
        }

        /* Badges */
        .optional-badge,
        .template-badge,
        .history-count {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, #ddd6fe 0%, #c7d2fe 100%);
            color: #5b21b6;
        }

        .dark .optional-badge,
        .dark .template-badge,
        .dark .history-count {
            background: linear-gradient(135deg, #4c1d95 0%, #3730a3 100%);
            color: #ddd6fe;
        }

        /* Input Elements */
        .custom-select-wrapper {
            position: relative;
        }

        .custom-select {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            background: white;
            font-size: 0.9375rem;
            transition: var(--cv-transition);
            cursor: pointer;
        }

        .dark .custom-select {
            background: rgba(17, 24, 39, 0.8);
            border-color: rgba(75, 85, 99, 0.6);
            color: #e5e7eb;
        }

        .custom-select:hover {
            border-color: #667eea;
        }

        .custom-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .custom-textarea {
            width: 100%;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            background: white;
            font-size: 0.9375rem;
            transition: var(--cv-transition);
            resize: vertical;
            min-height: 150px;
        }

        .dark .custom-textarea {
            background: rgba(17, 24, 39, 0.8);
            border-color: rgba(75, 85, 99, 0.6);
            color: #e5e7eb;
        }

        .custom-textarea:hover {
            border-color: #667eea;
        }

        .custom-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.625rem;
        }

        .dark .input-label {
            color: #d1d5db;
        }

        .template-description {
            margin-top: 0.875rem;
            font-size: 0.875rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .dark .template-description {
            color: #9ca3af;
        }

        /* Circular Score Indicators */
        .score-circle-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .score-circle {
            width: 120px;
            height: 120px;
        }

        .circular-chart {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .circle-bg {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 6;
        }

        .dark .circle-bg {
            stroke: rgba(75, 85, 99, 0.3);
        }

        .circle-progress {
            fill: none;
            stroke-width: 6;
            stroke-linecap: round;
            stroke-dasharray: 339.292;
            stroke-dashoffset: calc(339.292 - (339.292 * var(--score)) / 100);
            transition: stroke-dashoffset 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .ats-progress {
            stroke: url(#atsGradient);
        }

        .format-progress {
            stroke: url(#formatGradient);
        }

        .aboutme-progress {
            stroke: url(#aboutmeGradient);
        }

        .jobmatch-progress {
            stroke: url(#jobmatchGradient);
        }

        .score-text {
            fill: #1f2937;
            font-size: 24px;
            font-weight: 700;
            text-anchor: middle;
            dominant-baseline: middle;
            transform: rotate(90deg);
            transform-origin: center;
        }

        .dark .score-text {
            fill: #f9fafb;
        }

        .percent-sign {
            font-size: 14px;
            font-weight: 600;
        }

        .score-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-align: center;
        }

        .dark .score-label {
            color: #9ca3af;
        }

        /* Section Subtitle */
        .section-subtitle {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dark .section-subtitle {
            color: #f9fafb;
        }

        .section-subtitle::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--cv-gradient-primary);
            border-radius: 2px;
        }

        /* Industry Match Cards */
        .industry-match-card {
            padding: 1.25rem;
            border-radius: 12px;
            background: rgba(243, 244, 246, 0.5);
            border: 1px solid #e5e7eb;
            transition: var(--cv-transition);
        }

        .dark .industry-match-card {
            background: rgba(31, 41, 55, 0.5);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .industry-match-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--cv-shadow-sm);
        }

        .industry-name {
            font-weight: 600;
            color: #374151;
            font-size: 0.9375rem;
        }

        .dark .industry-name {
            color: #e5e7eb;
        }

        .industry-score {
            font-weight: 700;
            background: var(--cv-gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .progress-bar-wrapper {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .dark .progress-bar-wrapper {
            background: rgba(75, 85, 99, 0.3);
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--cv-gradient-primary);
            border-radius: 4px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Keywords Section */
        .keywords-section {
            padding: 1.5rem;
            border-radius: 12px;
            background: rgba(249, 250, 251, 0.5);
            border: 1px solid #e5e7eb;
        }

        .dark .keywords-section {
            background: rgba(31, 41, 55, 0.3);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .keywords-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: #10b981;
        }

        .keywords-header.missing {
            color: #ef4444;
        }

        .keywords-title {
            font-weight: 600;
            font-size: 1rem;
            flex: 1;
        }

        .keywords-count {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .keywords-header.missing .keywords-count {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .keywords-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.625rem;
        }

        .keyword-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--cv-transition);
        }

        .keyword-found {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .dark .keyword-found {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            color: #a7f3d0;
        }

        .keyword-missing {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .dark .keyword-missing {
            background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
            color: #fecaca;
        }

        .keyword-badge:hover {
            transform: translateY(-2px);
            box-shadow: var(--cv-shadow-sm);
        }

        /* Suggestions */
        .suggestions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .suggestion-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.3) 0%, rgba(253, 230, 138, 0.3) 100%);
            border: 1px solid rgba(251, 191, 36, 0.2);
            transition: var(--cv-transition);
            animation: slideIn 0.5s ease-out backwards;
        }

        .dark .suggestion-item {
            background: linear-gradient(135deg, rgba(120, 53, 15, 0.2) 0%, rgba(146, 64, 14, 0.2) 100%);
            border-color: rgba(251, 191, 36, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .suggestion-item:hover {
            transform: translateX(8px);
            box-shadow: var(--cv-shadow-sm);
        }

        .suggestion-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .suggestion-text {
            flex: 1;
            color: #374151;
            line-height: 1.6;
        }

        .dark .suggestion-text {
            color: #d1d5db;
        }

        /* Summary Card */
        .summary-card {
            padding: 1.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #93c5fd;
            display: flex;
            gap: 1rem;
        }

        .dark .summary-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-color: #3b82f6;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .summary-title {
            font-weight: 700;
            font-size: 1rem;
            color: #1e40af;
            margin-bottom: 0.5rem;
        }

        .dark .summary-title {
            color: #dbeafe;
        }

        .summary-text {
            color: #1e3a8a;
            line-height: 1.6;
        }

        .dark .summary-text {
            color: #bfdbfe;
        }

        /* Preview Section - A4 Size Centered */
        .preview-iframe-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            min-height: 900px;
            overflow: auto;
        }

        .dark .preview-iframe-wrapper {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        .preview-iframe {
            width: 210mm;
            height: 297mm;
            max-width: 210mm;
            min-height: 297mm;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            transform-origin: top center;
        }

        .dark .preview-iframe {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(6, 182, 212, 0.2);
        }

        /* History Table */
        .history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .history-table thead tr {
            background: rgba(243, 244, 246, 0.5);
        }

        .dark .history-table thead tr {
            background: rgba(31, 41, 55, 0.3);
        }

        .history-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        .dark .history-table th {
            color: #9ca3af;
        }

        .history-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: var(--cv-transition);
        }

        .dark .history-table tbody tr {
            border-bottom: 1px solid rgba(75, 85, 99, 0.3);
        }

        .history-table tbody tr:hover {
            background: rgba(249, 250, 251, 0.5);
        }

        .dark .history-table tbody tr:hover {
            background: rgba(31, 41, 55, 0.3);
        }

        .history-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: #374151;
        }

        .dark .history-table td {
            color: #d1d5db;
        }

        .score-badge {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .score-badge.excellent {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .dark .score-badge.excellent {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            color: #a7f3d0;
        }

        .score-badge.good {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .dark .score-badge.good {
            background: linear-gradient(135deg, #78350f 0%, #92400e 100%);
            color: #fde68a;
        }

        .score-badge.poor {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .dark .score-badge.poor {
            background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
            color: #fecaca;
        }

        .template-tag {
            display: inline-block;
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #3730a3;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .dark .template-tag {
            background: linear-gradient(135deg, #3730a3 0%, #4338ca 100%);
            color: #e0e7ff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .score-circle {
                width: 100px;
                height: 100px;
            }

            .score-text {
                font-size: 20px;
            }

            .preview-iframe-wrapper {
                padding: 1rem;
            }

            .preview-iframe {
                width: 100%;
                height: 141.42vw;
                max-width: none;
                min-height: auto;
            }
        }

        /* Scale A4 preview on screens smaller than 900px */
        @media (max-width: 900px) {
            .preview-iframe-wrapper {
                overflow-x: auto;
            }

            .preview-iframe {
                transform: scale(0.6);
                transform-origin: top center;
            }
        }

        @media (min-width: 901px) and (max-width: 1100px) {
            .preview-iframe {
                transform: scale(0.75);
                transform-origin: top center;
            }
        }

        @media (min-width: 1101px) and (max-width: 1300px) {
            .preview-iframe {
                transform: scale(0.85);
                transform-origin: top center;
            }
        }
    </style>
</x-filament-panels::page>