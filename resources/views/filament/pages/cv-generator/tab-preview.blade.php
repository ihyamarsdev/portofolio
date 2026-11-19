            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="glass-card p-5 space-y-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-cyan-950 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                                <x-heroicon-o-eye class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-md font-bold text-slate-900 dark:text-white">{{ __('cv-workspace.preview.canvas_sync') }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('cv-workspace.preview.template_indicator', ['template' => $selectedTemplate]) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('cv.preview') }}?template={{ $selectedTemplate }}" target="_blank" rel="noopener" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-all">
                                <span>{{ __('cv-workspace.preview.open_full') }}</span>
                                <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        @foreach(config('cv-templates', []) as $key => $template)
                            <button type="button" wire:click="selectTemplate('{{ $key }}')" class="text-left rounded-xl border p-4 transition-all {{ $selectedTemplate === $key ? 'border-cyan-500 bg-cyan-500/10 shadow-lg shadow-cyan-500/10' : 'border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/40 hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-bold {{ $selectedTemplate === $key ? 'text-cyan-600 dark:text-cyan-300' : 'text-slate-900 dark:text-white' }}">{{ __("cv-workspace.templates.{$key}.name") }}</span>
                                    @if($selectedTemplate === $key)
                                        <x-heroicon-o-check-circle class="w-5 h-5 text-cyan-500 dark:text-cyan-400 shrink-0" />
                                    @endif
                                </div>
                                <p class="mt-2 text-xs leading-5 text-slate-600 dark:text-slate-400">{{ __("cv-workspace.templates.{$key}.description") }}</p>
                                <span class="mt-3 inline-flex rounded-md bg-slate-100 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 px-2 py-1 text-[10px] font-mono uppercase text-slate-600 dark:text-slate-400">{{ $key }}</span>
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
                        <span class="text-xs text-slate-400 font-mono">{{ __('cv-workspace.preview.badge_workspace') }}</span>
                        <span class="text-xs text-slate-500 font-mono">{{ __('cv-workspace.preview.badge_size') }}</span>
                    </div>

                    <iframe src="{{ route('cv.preview') }}?template={{ $selectedTemplate }}&v={{ $previewNonce }}" class="w-full min-h-[750px] border-none bg-white" title="{{ __('cv-workspace.preview.iframe_title') }}"></iframe>
                </div>
            </div>
