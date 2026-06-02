<x-layouts.app title="Certificates">
    @php($certificates = \App\Models\Certificate::orderBy('issue_date', 'desc')->get())
    <section class="bg-bg-primary">
        <div class="section-container py-16 sm:py-24">
            <div class="mb-10 max-w-3xl">
                <p class="font-mono text-xs uppercase tracking-widest text-accent-sky">Credentials</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-[-.05em] text-text-primary sm:text-6xl">Certificates & proof of practice.</h1>
                <p class="mt-5 text-base leading-8 text-text-secondary sm:text-lg">Validated cloud, security, infrastructure, and engineering credentials.</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($certificates as $certificate)
                    <article class="rounded-2xl bg-bg-card p-5 shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition hover:-translate-y-1 dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-emerald-500 text-white"><x-heroicon-o-check-badge class="h-8 w-8" /></div>
                            @if($certificate->is_active && !$certificate->isExpired())
                                <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-500">Active</span>
                            @else
                                <span class="rounded-full bg-slate-500/10 px-3 py-1 text-xs font-medium text-text-muted">Expired</span>
                            @endif
                        </div>
                        <h2 class="text-lg font-semibold tracking-[-.02em] text-text-primary">{{ $certificate->name }}</h2>
                        <p class="mt-1 text-sm text-text-secondary">{{ $certificate->issuer }}</p>
                        <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                            <div><dt class="text-text-muted">Issued</dt><dd class="font-medium text-text-primary">{{ $certificate->issue_date->format('M Y') }}</dd></div>
                            <div><dt class="text-text-muted">Expires</dt><dd class="font-medium text-text-primary">{{ $certificate->expiry_date?->format('M Y') ?? 'Never' }}</dd></div>
                        </dl>
                        @if($certificate->credential_url)
                            <a href="{{ $certificate->credential_url }}" target="_blank" class="mt-5 inline-flex text-sm font-semibold text-accent-sky">Verify credential →</a>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl bg-bg-card p-10 text-center text-text-secondary shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)] sm:col-span-2 xl:col-span-3">No certificates yet.</div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
