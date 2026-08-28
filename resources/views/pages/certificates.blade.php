<x-layouts.app title="Certificates">
    @php($certificates = \App\Models\Certificate::orderBy('issue_date', 'desc')->get())
    <section class="bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container py-12 sm:py-16">
            <div class="mb-8 max-w-3xl">
                <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#00D1FF] text-black px-2.5 py-1 rounded-[6px] border-[2.5px] border-black">Credentials — {{ str_pad($certificates->count(),2,'0',STR_PAD_LEFT) }}</span>
                <h1 class="mt-3 text-4xl font-black tracking-[-0.06em] leading-none text-black dark:text-[#FFFBF0] sm:text-6xl">Certificates &<br><span class="bg-[#2563EB] dark:bg-[#3B82F6] text-white dark:text-white px-2 border-[3px] border-black dark:border-[#CBD5E1] shadow-[5px_5px_0_0_#111] dark:shadow-[4px_4px_0_0_rgba(203,213,225,0.9)] inline-block rotate-[-0.5deg]">proof of practice.</span></h1>
                <p class="mt-4 text-base leading-7 font-medium text-[#2a2a2a] dark:text-[#d6d6d6] border-l-[4px] border-black dark:border-[#CBD5E1] pl-4 sm:text-lg">Validated cloud, security, infrastructure, and engineering credentials.</p>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($certificates as $certificate)
                    <article class="brutal-card p-5 flex flex-col">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <div class="flex h-12 w-12 items-center justify-center bg-[#2563EB] border-[2.5px] border-black rounded-[10px] shadow-[3px_3px_0_0_#111] text-white"><x-heroicon-o-check-badge class="h-7 w-7" /></div>
                            @if($certificate->is_active && !$certificate->isExpired())
                                <span class="brutal-badge bg-[#B8FF66] text-black">● Active</span>
                            @else
                                <span class="brutal-badge brutal-badge--white">Expired</span>
                            @endif
                        </div>
                        <h2 class="text-lg font-black tracking-[-0.02em] leading-tight text-black dark:text-white">{{ $certificate->name }}</h2>
                        <p class="mt-1 text-sm font-bold text-[#2a2a2a] dark:text-[#d6d6d6]">{{ $certificate->issuer }}</p>
                        <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-[#FFFBF0] dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-[8px] p-2"><dt class="font-mono text-[10px] font-black uppercase tracking-widest">Issued</dt><dd class="font-black">{{ $certificate->issue_date->format('M Y') }}</dd></div>
                            <div class="bg-white dark:bg-[#1E293B] border-2 border-black dark:border-[#CBD5E1] rounded-[8px] p-2"><dt class="font-mono text-[10px] font-black uppercase tracking-widest">Expires</dt><dd class="font-black">{{ $certificate->expiry_date?->format('M Y') ?? 'Never' }}</dd></div>
                        </dl>
                        @if($certificate->credential_url)
                            <a href="{{ $certificate->credential_url }}" target="_blank" class="mt-4 inline-flex self-start brutal-btn brutal-btn--white text-xs px-3 py-1.5">Verify →</a>
                        @endif
                    </article>
                @empty
                    <div class="brutal-card p-10 text-center font-black uppercase sm:col-span-2 xl:col-span-3">No certificates yet.</div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>