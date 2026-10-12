<x-layouts.app :title="$project->title">
    <section class="py-10 sm:py-14 bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container">
            <a href="{{ route('projects') }}" class="inline-flex items-center gap-2 font-black uppercase text-xs tracking-widest bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-full px-4 py-2.5 min-h-[44px] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none transition-all mb-8">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg> Back to Projects
            </a>

            <div class="max-w-4xl mx-auto">
                <div class="mb-6">
                    @php($imageSrc = $project->imageSrc())
                    @if($imageSrc)
                        <div class="aspect-video overflow-hidden bg-white dark:bg-[#1E293B] border-[3px] border-black dark:border-[#CBD5E1] rounded-[16px] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)] mb-6">
                            <img src="{{ $imageSrc }}" alt="{{ $project->title }}" fetchpriority="high" decoding="async" class="h-full w-full object-cover">
                        </div>
                    @endif

                    <span class="inline-flex font-mono text-xs font-black uppercase tracking-[0.12em] bg-[#2563EB] text-white px-2.5 py-1 rounded-full border-2 border-black">Project Detail</span>
                    <h1 class="mt-3 text-3xl sm:text-5xl font-black tracking-[-0.06em] leading-none text-black dark:text-[#FFFBF0]">{{ $project->title }}</h1>

                    <div class="flex flex-wrap gap-3 mt-5">
                        @if($project->repository_url)
                            <a href="{{ $project->repository_url }}" target="_blank" class="brutal-btn brutal-btn--white text-xs px-4 py-2.5 min-h-[44px] inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg> Repository
                            </a>
                        @endif
                        @if($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" class="brutal-btn bg-[#00D1FF] text-black border-black text-xs px-4 py-2.5 min-h-[44px] inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg> Live Demo
                            </a>
                        @endif
                    </div>
                </div>

                @if($project->description)
                    <div class="brutal-card p-6 sm:p-7 mb-6">
                        <h2 class="text-lg font-black uppercase tracking-tight flex items-center gap-2 mb-4"><span class="w-1.5 h-6 bg-[#2563EB] border-2 border-black rounded-full"></span> Description</h2>
                        <div class="prose max-w-none">
                            {!! Str::markdown($project->description) !!}
                        </div>
                    </div>
                @endif

                @if($project->skills && $project->skills->count() > 0)
                    <div class="brutal-card !bg-[#2563EB] dark:!bg-[#1E293B] p-6 sm:p-7 mb-6">
                        <h2 class="text-lg font-black uppercase tracking-tight flex items-center gap-2 mb-4 text-white"><span class="w-1.5 h-6 bg-[#FFD23F] dark:bg-[#3B82F6] border-2 border-black rounded-full"></span> Tech Stack</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->skills as $skill)
                                <span class="inline-flex items-center gap-1.5 bg-white dark:bg-[#1E293B] border-[2px] border-black dark:border-[#CBD5E1] rounded-full px-3 py-1.5 text-xs font-black uppercase tracking-tight text-[#0B1B3F] dark:text-white">
                                    <span class="w-2 h-2 rounded-full bg-[#2563EB] dark:bg-[#3B82F6]"></span> {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($project->architecture_diagram)
                    <div class="brutal-card p-6 sm:p-7">
                        <h2 class="text-lg font-black uppercase tracking-tight flex items-center gap-2 mb-4"><span class="w-1.5 h-6 bg-[#00D1FF] border-2 border-black rounded-full"></span> Architecture Diagram</h2>
                        <div class="bg-white border-[2.5px] border-black rounded-[12px] p-4 overflow-x-auto">
                            <div class="mermaid">{{ $project->architecture_diagram }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if($project->architecture_diagram)
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    mermaid.initialize({ startOnLoad: true, theme: 'default', securityLevel: 'loose', flowchart: { useMaxWidth: true, htmlLabels: true, curve: 'basis' } });
                });
            </script>
        @endpush
    @endif
</x-layouts.app>