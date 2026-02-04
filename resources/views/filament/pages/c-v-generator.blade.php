<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Actions & Info -->
        <div class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">Actions</x-slot>
                <div class="flex flex-col gap-4">
                    <x-filament::button tag="a" href="{{ route('cv.download') }}" target="_blank"
                        icon="heroicon-o-arrow-down-tray" size="lg">
                        Download CV (ATS Friendly via PDF)
                    </x-filament::button>

                    <x-filament::button wire:click="analyze" icon="heroicon-o-sparkles" color="warning" size="lg"
                        :disabled="$isAnalyzing">
                        {{ $isAnalyzing ? 'Analyzing with Gemini AI...' : 'Analyze CV with AI' }}
                    </x-filament::button>

                    <div class="text-sm text-gray-400 mt-2">
                        <p class="mb-2">Ensure your data is complete in:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            <li>Profile (Primary)</li>
                            <li>Experience</li>
                            <li>Education</li>
                            <li>Skills</li>
                        </ul>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">About ATS Compatibility</x-slot>
                <div class="prose dark:prose-invert text-sm">
                    <p>
                        This generator creates a clean, text-optimized PDF designed for Applicant Tracking Systems
                        (ATS).
                        It avoids images, columns, and complex formatting that often confuse ATS parsers.
                    </p>
                </div>
            </x-filament::section>
        </div>

        <!-- Analysis Results -->
        @if($analysisResult)
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between">
                        <span>AI Analysis Result</span>
                        @php
                            $score = $analysisResult['ats_score'] ?? 0;
                            $color = $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger');
                        @endphp
                        <span class="text-{{ $color }}-500 font-bold text-xl">{{ $score }} / 100</span>
                    </div>
                </x-slot>

                <div class="space-y-6">
                    <div>
                        <h3 class="font-bold text-gray-200 mb-2">Summary</h3>
                        <p class="text-gray-400 text-sm">{{ $analysisResult['summary'] ?? 'No summary available.' }}</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="bg-gray-900/50 p-4 rounded-lg">
                            <h4 class="font-bold text-success-400 mb-2 flex items-center gap-2">
                                <x-heroicon-o-check-circle class="w-4 h-4" /> Found Keywords
                            </h4>
                            <ul class="list-disc pl-4 text-xs text-gray-300 space-y-1">
                                @forelse($analysisResult['keywords']['found'] ?? [] as $keyword)
                                    <li>{{ $keyword }}</li>
                                @empty
                                    <li class="text-gray-500">No specific keywords found.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="bg-gray-900/50 p-4 rounded-lg">
                            <h4 class="font-bold text-danger-400 mb-2 flex items-center gap-2">
                                <x-heroicon-o-x-circle class="w-4 h-4" /> Missing Keywords
                            </h4>
                            <ul class="list-disc pl-4 text-xs text-gray-300 space-y-1">
                                @forelse($analysisResult['keywords']['missing'] ?? [] as $keyword)
                                    <li>{{ $keyword }}</li>
                                @empty
                                    <li class="text-gray-500">No missing keywords identified.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-200 mb-2">Suggestions for Improvement</h3>
                        <ul class="list-disc pl-4 text-sm text-gray-300 space-y-1">
                            @forelse($analysisResult['suggestions'] ?? [] as $suggestion)
                                <li>{{ $suggestion }}</li>
                            @empty
                                <li>No suggestions available.</li>
                            @endforelse
                        </ul>
                    </div>

                    @if(isset($analysisResult['industry_match']))
                        <div>
                            <h3 class="font-bold text-gray-200 mb-2">Industry Match</h3>
                            <div class="space-y-2">
                                @foreach($analysisResult['industry_match'] as $industry => $match)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="capitalize">{{ str_replace('_', ' ', $industry) }}</span>
                                        <div class="flex items-center gap-2 w-1/2">
                                            <div class="h-2 bg-gray-700 rounded-full flex-1 overflow-hidden">
                                                <div class="h-full bg-primary-500" style="width: {{ $match }}%"></div>
                                            </div>
                                            <span class="w-8 text-right">{{ $match }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        @else
            <x-filament::section>
                <div class="flex flex-col items-center justify-center py-12 text-center text-gray-500">
                    <x-heroicon-o-document-magnifying-glass class="w-16 h-16 mb-4 opacity-50" />
                    <h3 class="text-lg font-medium text-gray-300">Ready to Analyze</h3>
                    <p>Click the "Analyze CV with AI" button to get detailed feedback<br>and an ATS compatibility score from
                        Gemini AI.</p>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>