<x-layouts.app title="{{ __('messages.certificates.title') }}">
    @php($certificates = \App\Models\Certificate::orderBy('issue_date', 'desc')->get())

    <div class="section-container py-14 sm:py-20">
        <x-ui.section-header
            :index="'Credentials — '.str_pad($certificates->count(), 2, '0', STR_PAD_LEFT)"
            :as="'h1'"
            :title="__('messages.certificates.title')"
            :description="__('messages.certificates.subtitle')" />

        @if($certificates->isNotEmpty())
            <div class="mt-10 border-t border-line">
                <div class="hidden grid-cols-12 gap-4 border-b border-line py-3 font-mono text-[11px] uppercase tracking-[0.14em] text-ink-3 md:grid">
                    <div class="col-span-2">Year</div>
                    <div class="col-span-5">{{ __('messages.certificates.title') }}</div>
                    <div class="col-span-3">{{ __('messages.certificates.issued_by') }}</div>
                    <div class="col-span-2 text-right">Status</div>
                </div>

                @foreach($certificates as $certificate)
                    @php($active = $certificate->is_active && ! $certificate->isExpired())
                    <div class="grid gap-2 border-b border-line py-5 transition-colors duration-150 hover:bg-subtle md:grid-cols-12 md:gap-4">
                        <div class="font-mono text-xs text-ink-3 md:col-span-2">{{ $certificate->issue_date?->format('Y') ?? '—' }}</div>
                        <div class="md:col-span-5">
                            <h2 class="font-medium leading-snug text-ink">{{ $certificate->name }}</h2>
                            @if($certificate->credential_id)
                                <p class="mt-1 font-mono text-[11px] text-ink-3">{{ $certificate->credential_id }}</p>
                            @endif
                            @if($certificate->credential_url)
                                <a href="{{ $certificate->credential_url }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center gap-1 font-mono text-[11px] uppercase tracking-wider text-accent transition-colors duration-150 hover:text-ink">
                                    {{ __('messages.certificates.view_certificate') }} ↗
                                </a>
                            @endif
                        </div>
                        <div class="text-sm text-ink-2 md:col-span-3">{{ $certificate->issuer }}</div>
                        <div class="font-mono text-[11px] uppercase tracking-wider md:col-span-2 md:text-right {{ $active ? 'text-accent' : 'text-ink-3' }}">
                            {{ $active ? 'Active' : 'Expired' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-10 border border-line bg-surface p-10 text-center">
                <h2 class="text-xl font-semibold tracking-[-0.02em] text-ink">{{ __('messages.ux_pages.no_certificates_title') }}</h2>
                <p class="measure mx-auto mt-3 text-sm leading-relaxed text-ink-2">{{ __('messages.ux_pages.no_certificates_desc') }}</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <x-ui.button :href="route('about')" variant="secondary" size="sm">{{ __('messages.ux_pages.view_about') }}</x-ui.button>
                    <x-ui.button :href="route('contact')" size="sm">{{ __('messages.ux_pages.contact_me') }}</x-ui.button>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
