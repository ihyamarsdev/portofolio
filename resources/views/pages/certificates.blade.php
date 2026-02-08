<x-layouts.app title="Certificates">
    <section class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="max-w-3xl mb-12">
                <h1 class="text-4xl font-bold text-[--color-text-primary] mb-4">Certifications</h1>
                <p class="text-lg text-[--color-text-secondary]">
                    Professional certifications and credentials in cloud platforms and DevOps practices.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $certificates = \App\Models\Certificate::orderBy('issue_date', 'desc')->get();
                @endphp

                @forelse($certificates as $certificate)
                    <div class="glass-card p-6 group">
                    
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-[--color-accent-emerald] to-[--color-accent-cyan] rounded-xl flex items-center justify-center">
                            <x-heroicon-o-check-badge class="w-20 h-20 text-text-primary dark:text-white" />
                        </div>


                        <h2
                            class="text-lg font-semibold text-[--color-text-primary] text-center mb-1 group-hover:text-[--color-accent-emerald] transition-colors">
                            {{ $certificate->name }}
                        </h2>
                        <p class="text-[--color-text-secondary] text-sm text-center mb-4">{{ $certificate->issuer }}</p>

                        <div class="flex items-center justify-center gap-2 mb-4">
                            @if($certificate->is_active && !$certificate->isExpired())
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-green-500/10 text-green-400 text-xs rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                                    Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-gray-500/10 text-gray-400 text-xs rounded-full">
                                    Expired
                                </span>
                            @endif
                        </div>

                        <div class="text-center text-sm text-[--color-text-muted] space-y-1">
                            <p>Issued: {{ $certificate->issue_date->format('M Y') }}</p>
                            @if($certificate->expiry_date)
                                <p>Expires: {{ $certificate->expiry_date->format('M Y') }}</p>
                            @endif
                        </div>

                        @if($certificate->credential_url)
                            <div class="mt-4 pt-4 border-t border-[--color-border]">
                                <a href="{{ $certificate->credential_url }}" target="_blank"
                                    class="flex items-center justify-center gap-2 text-sm text-[--color-accent-cyan] hover:underline">
                                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                                    Verify Credential
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-[--color-bg-secondary] rounded-full flex items-center justify-center">
                            <x-heroicon-o-check-badge class="w-8 h-8 text-[--color-text-muted]" />
                        </div>
                        <h3 class="text-lg font-medium text-[--color-text-primary] mb-2">No certificates yet</h3>
                        <p class="text-[--color-text-secondary]">Certificates will appear here once added through the admin
                            panel.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>