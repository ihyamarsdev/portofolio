<x-layouts.app title="{{ __('messages.contact.title') }}">
    <div class="section-container py-14 sm:py-20">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-12">
            <!-- Direct contact info -->
            <div class="lg:col-span-5">
                <x-ui.section-header
                    :index="__('messages.contact.eyebrow')"
                    :as="'h1'"
                    :title="__('messages.contact.lead_title')"
                    :description="__('messages.contact.lead_desc')" />

                <dl class="mt-10 divide-y divide-line border-y border-line">
                    @if($profile?->email)
                        <div class="py-4">
                            <dt class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-3">{{ __('messages.contact.info_email') }}</dt>
                            <dd class="mt-1.5"><a href="mailto:{{ $profile->email }}" class="text-sm text-ink transition-colors duration-150 hover:text-accent">{{ $profile->email }}</a></dd>
                        </div>
                    @endif
                    @if($profile?->phone)
                        <div class="py-4">
                            <dt class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-3">{{ __('messages.contact.info_phone') }}</dt>
                            <dd class="mt-1.5 text-sm text-ink">{{ $profile->phone }}</dd>
                        </div>
                    @endif
                    @if($profile?->location)
                        <div class="py-4">
                            <dt class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-3">{{ __('messages.contact.info_location') }}</dt>
                            <dd class="mt-1.5 text-sm text-ink">{{ $profile->location }}</dd>
                        </div>
                    @endif
                    <div class="py-4">
                        <dt class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-3">{{ __('messages.contact.info_response') }}</dt>
                        <dd class="mt-1.5 text-sm text-ink">{{ __('messages.contact.response_time') }}</dd>
                    </div>
                </dl>

                <div class="mt-8 flex flex-wrap gap-x-5 gap-y-2 font-mono text-[11px] uppercase tracking-wider">
                    @if($profile?->github_url)
                        <a href="{{ str_starts_with($profile->github_url, 'http') ? $profile->github_url : 'https://github.com/'.$profile->github_url }}" target="_blank" rel="noopener noreferrer" class="text-ink-2 transition-colors duration-150 hover:text-accent">GitHub ↗</a>
                    @endif
                    @if($profile?->linkedin_url)
                        <a href="{{ str_starts_with($profile->linkedin_url, 'http') ? $profile->linkedin_url : 'https://linkedin.com/in/'.$profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-ink-2 transition-colors duration-150 hover:text-accent">LinkedIn ↗</a>
                    @endif
                    @if($profile?->twitter_url)
                        <a href="{{ str_starts_with($profile->twitter_url, 'http') ? $profile->twitter_url : 'https://x.com/'.$profile->twitter_url }}" target="_blank" rel="noopener noreferrer" class="text-ink-2 transition-colors duration-150 hover:text-accent">X ↗</a>
                    @endif
                </div>
            </div>

            <!-- Form -->
            <div class="lg:col-span-7">
                <div class="border border-line bg-surface p-6 sm:p-8">
                    <h2 class="text-xl font-semibold tracking-[-0.02em] text-ink">{{ __('messages.contact.form_title') }}</h2>

                    @if(session('success'))
                        <div role="status" class="mt-5 border border-accent/40 bg-accent/5 px-4 py-3 text-sm text-accent">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="mt-7 space-y-5">
                        @csrf
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2">{{ __('messages.contact.name') }} *</label>
                                <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="{{ __('messages.contact.placeholder_name') }}" class="mt-2 w-full rounded-md border border-line bg-canvas px-3 py-2.5 text-sm text-ink outline-none transition-colors duration-150 placeholder:text-ink-3 focus:border-accent @error('name') border-error @enderror">
                                @error('name')<p class="mt-1.5 text-xs text-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2">{{ __('messages.contact.email') }} *</label>
                                <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="{{ __('messages.contact.placeholder_email') }}" class="mt-2 w-full rounded-md border border-line bg-canvas px-3 py-2.5 text-sm text-ink outline-none transition-colors duration-150 placeholder:text-ink-3 focus:border-accent @error('email') border-error @enderror">
                                @error('email')<p class="mt-1.5 text-xs text-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2">{{ __('messages.contact.subject') }} *</label>
                            <input id="subject" name="subject" type="text" required value="{{ old('subject') }}" placeholder="{{ __('messages.contact.placeholder_subject') }}" class="mt-2 w-full rounded-md border border-line bg-canvas px-3 py-2.5 text-sm text-ink outline-none transition-colors duration-150 placeholder:text-ink-3 focus:border-accent @error('subject') border-error @enderror">
                            @error('subject')<p class="mt-1.5 text-xs text-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="message" class="font-mono text-[11px] uppercase tracking-[0.12em] text-ink-2">{{ __('messages.contact.message') }} *</label>
                            <textarea id="message" name="message" rows="6" required placeholder="{{ __('messages.contact.placeholder_message') }}" class="mt-2 w-full resize-y rounded-md border border-line bg-canvas px-3 py-2.5 text-sm leading-relaxed text-ink outline-none transition-colors duration-150 placeholder:text-ink-3 focus:border-accent @error('message') border-error @enderror">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1.5 text-xs text-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-md border border-accent bg-accent px-5 py-2.5 text-sm font-medium text-accent-contrast transition-colors duration-150 hover:bg-transparent hover:text-accent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-canvas">
                                {{ __('messages.contact.send') }} <span class="transition-transform duration-150 group-hover:translate-x-1">→</span>
                            </button>
                            <p class="font-mono text-[11px] uppercase tracking-wider text-ink-3">5 / min</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
