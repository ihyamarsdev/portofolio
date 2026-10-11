            <div class="tab-pane-content animate-fade-in">
                <form wire:submit.prevent="saveProfile" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-user class="w-5 h-5 text-cyan-400" />
                                <span>{{ __('cv-workspace.profile.basic.heading') }}</span>
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.basic.full_name') }}</label>
                                <input type="text" wire:model.defer="profile_name" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.basic.full_name_placeholder') }}" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.basic.title') }}</label>
                                <input type="text" wire:model.defer="profile_title" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.basic.title_placeholder') }}" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.basic.biography') }}</label>
                                <textarea wire:model.defer="profile_biography" rows="6" class="cyber-textarea w-full" placeholder="{{ __('cv-workspace.profile.basic.biography_placeholder') }}"></textarea>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-envelope class="w-5 h-5 text-cyan-400" />
                                <span>{{ __('cv-workspace.profile.contact.heading') }}</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.contact.email') }}</label>
                                    <input type="email" wire:model.defer="profile_email" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.contact.email_placeholder') }}" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.contact.phone') }}</label>
                                    <input type="text" wire:model.defer="profile_phone" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.contact.phone_placeholder') }}" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.contact.location') }}</label>
                                    <input type="text" wire:model.defer="profile_location" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.contact.location_placeholder') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3 flex items-center gap-2">
                                <x-heroicon-o-link class="w-5 h-5 text-cyan-400" />
                                <span>{{ __('cv-workspace.profile.social.heading') }}</span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.social.github') }}</label>
                                    <input type="text" wire:model.defer="profile_github_url" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.social.github_placeholder') }}" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.social.linkedin') }}</label>
                                    <input type="text" wire:model.defer="profile_linkedin_url" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.social.linkedin_placeholder') }}" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.social.twitter') }}</label>
                                    <input type="text" wire:model.defer="profile_twitter_url" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.social.twitter_placeholder') }}" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.social.website') }}</label>
                                    <input type="text" wire:model.defer="profile_website_url" class="cyber-input w-full" placeholder="{{ __('cv-workspace.profile.social.website_placeholder') }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="glass-card p-6 space-y-5">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3">{{ __('cv-workspace.profile.avatar.heading') }}</h3>
                            
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
                                        {{ __('cv-workspace.profile.avatar.upload') }}
                                    </label>
                                    <div wire:loading wire:target="profile_avatar" class="text-xs text-cyan-400 mt-2">{{ __('cv-workspace.profile.avatar.uploading') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="glass-card p-6 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-[#2d2d3a] pb-3">{{ __('cv-workspace.profile.design.heading') }}</h3>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ __('cv-workspace.profile.design.template_label') }}</label>
                                <select wire:model="selectedTemplate" class="cyber-select w-full">
                                    @foreach(config('cv-templates', []) as $key => $template)
                                        <option value="{{ $key }}">{{ __("cv-workspace.templates.{$key}.name") }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <x-filament::button type="submit" size="lg" color="primary" class="w-full shadow-lg glow-cyan shadow-cyan-500/20">
                                <x-heroicon-o-check class="w-5 h-5 mr-1.5" />
                                {{ __('cv-workspace.profile.save_button') }}
                            </x-filament::button>
                        </div>
                    </div>

                </form>
            </div>
