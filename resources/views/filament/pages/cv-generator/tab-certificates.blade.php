            <div class="tab-pane-content space-y-6 animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-trophy class="w-5 h-5 text-cyan-400" />
                        <span>{{ __('cv-workspace.certificates.title') }}</span>
                    </h3>
                    <x-filament::button wire:click="openCertificateModal()" color="primary" size="sm">
                        <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                        {{ __('cv-workspace.certificates.add') }}
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
                                            <p class="text-[10px] text-slate-500 mt-1 font-mono">{{ __('cv-workspace.certificates.credential_id', ['id' => $cert->credential_id]) }}</p>
                                        @endif

                                        <div class="flex gap-3 text-[10px] text-slate-400 mt-2 font-mono">
                                            <span>{{ __('cv-workspace.certificates.issued', ['date' => $cert->issue_date->translatedFormat('M Y')]) }}</span>
                                            @if($cert->expiry_date)
                                                <span>{{ __('cv-workspace.certificates.expires', ['date' => $cert->expiry_date->translatedFormat('M Y')]) }}</span>
                                            @else
                                                <span>{{ __('cv-workspace.certificates.no_expiry') }}</span>
                                            @endif
                                        </div>

                                        @if($cert->credential_url)
                                            <a href="{{ $cert->credential_url }}" target="_blank" rel="noopener" class="text-[10px] text-cyan-400 hover:underline inline-flex items-center gap-0.5 mt-2 font-semibold">
                                                <span>{{ __('cv-workspace.certificates.verify') }}</span>
                                                <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                    <button wire:click="openCertificateModal({{ $cert->id }})" class="p-1 bg-slate-800 text-slate-400 hover:text-white rounded" title="{{ __('cv-workspace.certificates.edit') }}" aria-label="{{ __('cv-workspace.certificates.edit') }}">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </button>
                                    <button wire:click="deleteCertificate({{ $cert->id }})" wire:confirm="{{ __('cv-workspace.certificates.confirm_delete') }}" class="p-1 bg-slate-800 text-slate-400 hover:text-rose-400 rounded" title="{{ __('cv-workspace.certificates.delete') }}" aria-label="{{ __('cv-workspace.certificates.delete') }}">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass-card p-8 text-center">
                        <x-heroicon-o-trophy class="w-12 h-12 text-slate-600 mx-auto mb-3" />
                        <p class="text-sm text-slate-400">{{ __('cv-workspace.certificates.empty.description') }}</p>
                        <x-filament::button wire:click="openCertificateModal()" color="primary" class="mt-4" size="sm">
                            {{ __('cv-workspace.certificates.empty.action') }}
                        </x-filament::button>
                    </div>
                @endif
            </div>
