<x-filament-panels::page>
    @vite('resources/css/app.css')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::button wire:click="analyze" color="primary" :loading="$isAnalyzing" class="w-full">
                <x-heroicon-o-sparkles class="w-5 h-5 mr-2" />
                Analyze CV
            </x-filament::button>

            <x-filament::button wire:click="analyzeJobMatch" color="warning" class="w-full">
                <x-heroicon-o-scale class="w-5 h-5 mr-2" />
                Match Job
            </x-filament::button>

            <x-filament::button wire:click="downloadCv" color="success" class="w-full">
                <x-heroicon-o-document-arrow-down class="w-5 h-5 mr-2" />
                Download PDF
            </x-filament::button>

            <x-filament::button wire:click="downloadExport" color="gray" class="w-full">
                <x-heroicon-o-chevron-down class="w-5 h-5 mr-2" />
                Export Options
            </x-filament::button>
        </div>

        <div class="bg-card bg-gray-200 dark:bg-gray-800 p-6 shadow-sm dark:border-gray-400 rounded-xl">
            <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Choose CV Template</h3>
            <div class="w-full md:w-1/2">
                <label class="block text-sm font-medium text-secondary dark:text-gray-400 mb-2">Select Template</label>
                <select wire:model.live="selectedTemplate"
                    class="w-full rounded-lg text-black shadow-sm py-3 px-4 text-gray-900 dark:text-white sm:text-sm ">
                    @foreach(config('cv-templates') as $key => $template)
                        <option value="{{ $key }}">{{ $template['name'] }}</option>
                    @endforeach
                </select>
                @if(isset(config('cv-templates')[$selectedTemplate]))
                    <p class="mt-3 text-sm text-secondary dark:text-gray-400">
                        {{ config('cv-templates')[$selectedTemplate]['description'] }}</p>
                @endif
            </div>
        </div>

        <div class="bg-card bg-gray-200 dark:bg-gray-800 p-6 shadow-sm dark:border-gray-400 rounded-xl">
            <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Job Description Matching (Optional)</h3>
            <textarea wire:model="jobDescription" rows="6"
                placeholder="Paste job description to see how well your CV matches..."
                class="w-full rounded-lg border-input bg-background text-black focus:ring-primary shadow-sm py-3 px-4 text-gray-900 dark:text-white sm:text-sm"></textarea>
            <x-filament::button wire:click="analyzeJobMatch" color="primary" class="mt-4">
                Analyze Match
            </x-filament::button>
        </div>

        @if($analysisResult)
            <div class="bg-card bg-gray-200 dark:bg-gray-800 p-6 shadow-sm dark:border-gray-400 rounded-xl">
                <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Analysis Results</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div
                        class="bg-gray-300 dark:bg-gray-700 text-center p-4 rounded-lg score-card {{ $analysisResult['ats_score'] >= 80 ? 'score-excellent dark:score-excellent-dark' : ($analysisResult['ats_score'] >= 50 ? 'score-good dark:score-good-dark' : 'score-poor dark:score-poor-dark') }}">
                        <div class="text-4xl font-bold text-black dark:text-white">{{ $analysisResult['ats_score'] }}%
                        </div>
                        <div class="text-black dark:text-gray-400 text-sm mt-1">ATS Score</div>
                        <div class="h-2 bg-background rounded-full overflow-hidden">
                            <div class="h-full score-progress-bar" style="width: {{ $analysisResult['ats_score'] }}%"></div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-300 dark:bg-gray-700 text-center p-4 rounded-lg score-card {{ $analysisResult['format_score'] >= 80 ? 'score-excellent dark:score-excellent-dark' : ($analysisResult['format_score'] >= 50 ? 'score-good dark:score-good-dark' : 'score-poor dark:score-poor-dark') }}">
                        <div class="text-4xl font-bold text-black dark:text-white">{{ $analysisResult['format_score'] }}%
                        </div>
                        <div class="text-black dark:text-gray-400 text-sm mt-1">Format Score</div>
                        <div class="h-2 bg-background rounded-full overflow-hidden">
                            <div class="h-full score-progress-bar" style="width: {{ $analysisResult['format_score'] }}%">
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-300 dark:bg-gray-700 text-center p-4 rounded-lg score-card {{ isset($analysisResult['about_me_score']) ? ($analysisResult['about_me_score'] >= 80 ? 'score-excellent dark:score-excellent-dark' : ($analysisResult['about_me_score'] >= 50 ? 'score-good dark:score-good-dark' : 'score-poor dark:score-poor-dark')) : 'score-neutral-dark' }}">
                        <div class="text-4xl font-bold text-black dark:text-white">
                            {{ $analysisResult['about_me_score'] ?? '-' }}%</div>
                        <div class="text-black dark:text-gray-400 text-sm mt-1">About Me</div>
                        @if(isset($analysisResult['about_me_score']))
                            <div class="h-2 bg-background rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 dark:bg-purple-600" style="width: {{ $analysisResult['about_me_score'] }}%">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div
                        class="bg-gray-300 dark:bg-gray-700 text-center p-4 rounded-lg score-card {{ isset($analysisResult['job_match_score']) ? ($analysisResult['job_match_score'] >= 80 ? 'score-excellent dark:score-excellent-dark' : ($analysisResult['job_match_score'] >= 50 ? 'score-good dark:score-good-dark' : 'score-poor dark:score-poor-dark')) : 'score-neutral-dark' }}">
                        <div class="text-4xl font-bold text-black dark:text-white">
                            {{ $analysisResult['job_match_score'] ?? '-' }}%</div>
                        <div class="text-black dark:text-gray-400 text-sm mt-1">Job Match</div>
                        @if(isset($analysisResult['job_match_score']))
                            <div class="h-2 bg-background rounded-full overflow-hidden">
                                <div class="h-full score-progress-bar" style="width: {{ $analysisResult['job_match_score'] }}%">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(isset($analysisResult['industry_match']))
                    <div class="mb-6">
                        <h4 class="font-semibold text-black dark:text-white mb-3">Industry Compatibility</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($analysisResult['industry_match'] as $industry => $score)
                                <div class="p-3 rounded-lg card-item">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium text-black dark:text-white text-sm">{{ ucfirst($industry) }}</span>
                                        <span class="text-sm font-semibold text-secondary dark:text-gray-400">{{ $score }}%</span>
                                    </div>
                                    <div class="h-2 bg-background rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 dark:bg-blue-600" style="width: {{ $score }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-6">
                    <h4 class="font-semibold text-black dark:text-white mb-3">Keywords Analysis</h4>

                    <div class="mb-4">
                        <span
                            class="inline-block px-3 py-1 bg-success dark:bg-green-900 text-success-foreground rounded-full text-sm font-medium mb-2">Found
                            ({{ count($analysisResult['keywords']['found'] ?? []) }})</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach(($analysisResult['keywords']['found'] ?? []) as $keyword)
                                <span
                                    class="px-2 py-1 bg-green-50 dark:bg-green-200 text-green-600 rounded text-sm">{{ $keyword }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1 bg-danger dark:bg-red-900 text-danger-foreground rounded-full text-sm font-medium mb-2">Missing
                            ({{ count($analysisResult['keywords']['missing'] ?? []) }})</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach(($analysisResult['keywords']['missing'] ?? []) as $keyword)
                                <span
                                    class="px-2 py-1 bg-red-50 dark:bg-red-200 text-red-600 rounded text-sm">{{ $keyword }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold text-black dark:text-white mb-3">Suggestions for Improvement</h4>
                    <ul class="space-y-2">
                        @foreach(($analysisResult['suggestions'] ?? []) as $suggestion)
                            <li class="flex items-start">
                                <x-heroicon-o-light-bulb
                                    class="w-5 h-5 text-yellow-500 dark:text-yellow-400 mr-2 mt-0.5 flex-shrink-0" />
                                <span class="text-secondary dark:text-gray-400">{{ $suggestion }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if(isset($analysisResult['summary']))
                    <div class="p-4 rounded-lg card-info bg-blue-800 dark:bg-blue-900 dark:bg-blue-800 border-blue-200">
                        <h4 class="font-semibold text-blue-200 dark:text-blue-100 mb-2">Summary</h4>
                        <p class="text-blue-300 dark:text-blue-300 text-sm">{{ $analysisResult['summary'] }}</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="bg-card bg-gray-200 dark:bg-gray-800 p-6 shadow-sm dark:border-gray-400 rounded-xl">
            <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Live CV Preview
                ({{ ucfirst($selectedTemplate) }} Template)</h3>
            <div class="border-1 border-gray-200 rounded-lg overflow-hidden dark:border-gray-700">
                <iframe src="{{ route('cv.preview') }}?template={{ $selectedTemplate }}"
                    class="w-full h-[800px]"></iframe>
            </div>
        </div>

        @if($history && count($history) > 0)
            <div class="bg-card bg-grey-200 dark:bg-gray-800 p-6 shadow-sm rounded-xl">
                <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Analysis History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th
                                    class="py-3 px-4 text-xs font-semibold text-secondary dark:text-gray-400 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="py-3 px-4 text-xs font-semibold text-secondary dark:text-gray-400 uppercase tracking-wider">
                                    ATS Score</th>
                                <th
                                    class="py-3 px-4 text-xs font-semibold text-secondary dark:text-gray-400 uppercase tracking-wider">
                                    Format Score</th>
                                <th
                                    class="py-3 px-4 text-xs font-semibold text-secondary dark:text-gray-400 uppercase tracking-wider">
                                    About Me</th>
                                <th
                                    class="py-3 px-4 text-xs font-semibold text-secondary dark:text-gray-400 uppercase tracking-wider">
                                    Job Match</th>
                                <th
                                    class="py-3 px-4 text-xs font-semibold text-secondary dark:text-gray-400 uppercase tracking-wider">
                                    Template</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $item)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $item->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <span
                                            class="px-2 py-1 rounded text-xs font-medium {{ $item->ats_score >= 80 ? 'bg-green-100 dark:bg-green-200 text-green-800' : ($item->ats_score >= 50 ? 'bg-yellow-100 dark:bg-yellow-200 text-yellow-800' : 'bg-red-100 dark:bg-red-200 text-red-800') }}">
                                            {{ $item->ats_score }}%
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->format_score }}%
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                        @if($item->about_me_score)
                                            <span class="px-2 py-1 rounded text-xs font-medium bg-purple-100 dark:bg-purple-200 text-purple-800">
                                                {{ $item->about_me_score }}%
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $item->job_match_score ?? '-' }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700 dark:text-gray-300">
                                        {{ ucfirst($item->profile->cv_template ?? 'ats') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <style>
        .dark {
            --bg-muted: #1f2937a;
            --text-muted: #6b7280;
            --text-primary: #f9fafb;
            --text-secondary: #9ca3af;
            --card-bg: #111827;
            --border-color: #374151;
            --bg-input: #111827;
            --border-color: #374151;
            --bg-background: #1f2937a;

            --card-bg: #1f2937;
            --progress-bg: #111827;
            --text-color: #f9fafb;

            --score-excellent: #15803d;
            --score-excellent-dark: #047857;
            --score-good: #fbbf24;
            --score-good-dark: #374151;
            --score-poor: #fca5a5;
            --score-poor-dark: #7f1d1d;

            --success-foreground: #166534;
            --success-background: #166534;
            --success-text: #166534;

            --danger-foreground: #ef4444;
            --danger-background: #450a0a;
            --danger-text: #450a0a;

            --blue-600: #2563eb;
            --blue-900: #1e3a8a;
            --blue-200: #1e3a8a;

            --score-excellent: #10b981;
            --score-good: #10b981;
            --score-poor: #ef4444;
            --score-poor-dark: #dc2626;
        }
    </style>
</x-filament-panels::page>