<x-layouts.app title="Contact">
    <section class="bg-[#FFFBF0] dark:bg-[#0F172A]">
        <div class="section-container py-12 sm:py-16">
            <div class="grid gap-8 lg:grid-cols-[.9fr_1.1fr] lg:items-start">
                <div>
                    <span class="inline-flex font-mono text-[11px] font-black uppercase tracking-[0.16em] bg-[#FF90E8] text-black px-2.5 py-1 rounded-[6px] border-[2.5px] border-black">{{ __('messages.contact.eyebrow') }}</span>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.06em] leading-[0.9] text-black dark:text-[#FFFBF0] sm:text-6xl">{{ __('messages.contact.lead_title') }}</h1>
                    <p class="mt-4 text-base leading-7 font-medium text-[#2a2a2a] dark:text-[#d6d6d6] border-l-[4px] border-black dark:border-[#CBD5E1] pl-4 sm:text-lg">{{ __('messages.contact.lead_desc') }}</p>
                    <div class="mt-6 grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                        @foreach([
                            [__('messages.contact.info_email'), $profile?->email ?? 'contact@example.com', '📧', '#2563EB'],
                            [__('messages.contact.info_location'), $profile?->location ?? __('Indonesia'), '📍', '#FF90E8'],
                            [__('messages.contact.info_response'), __('messages.contact.response_time'), '⚡', '#B8FF66'],
                        ] as [$label, $value, $icon, $bg])
                            <div class="brutal-card p-4 flex items-center gap-3 !shadow-[3px_3px_0_0_#111] dark:!shadow-[3px_3px_0_0_rgba(203,213,225,0.9)]">
                                <span class="w-10 h-10 inline-flex items-center justify-center border-[2.5px] border-black rounded-[8px] text-lg shrink-0" style="background: {{ $bg }}">{{ $icon }}</span>
                                <div class="min-w-0">
                                    <p class="font-mono text-[10px] font-black uppercase tracking-widest text-[#6b6b6b] dark:text-[#9a9a9a]">{{ $label }}</p>
                                    <p class="break-words text-sm font-black tracking-tight text-black dark:text-white">{{ $value }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="brutal-card p-6 sm:p-7 bg-white dark:bg-[#1E293B] shadow-[6px_6px_0_0_#111] dark:shadow-[5px_5px_0_0_rgba(203,213,225,0.9)]">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="w-2 h-8 bg-[#2563EB] border-2 border-black rounded-full"></span>
                        <h2 class="text-2xl font-black tracking-[-0.04em] text-black dark:text-white">{{ __('messages.contact.form_title') }}</h2>
                    </div>
                    <p class="font-mono text-xs font-bold uppercase tracking-widest text-[#6b6b6b] dark:text-[#9a9a9a] ml-5">We reply fast — brutal honesty</p>
                    @if(session('success'))
                        <div class="mt-5 bg-[#B8FF66] border-[2.5px] border-black rounded-[10px] p-3 text-sm font-black">✓ {{ session('success') }}</div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="font-mono text-xs font-black uppercase tracking-widest">{{ __('messages.contact.name') }} *</label>
                                <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="{{ __('messages.contact.placeholder_name') }}" class="brutal-input mt-1.5">
                                @error('name')<p class="mt-1 text-xs font-black text-red-600 border-2 border-red-600 bg-red-50 rounded px-2 py-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="font-mono text-xs font-black uppercase tracking-widest">{{ __('messages.contact.email') }} *</label>
                                <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="{{ __('messages.contact.placeholder_email') }}" class="brutal-input mt-1.5">
                                @error('email')<p class="mt-1 text-xs font-black text-red-600 border-2 border-red-600 bg-red-50 rounded px-2 py-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="font-mono text-xs font-black uppercase tracking-widest">{{ __('messages.contact.subject') }} *</label>
                            <input id="subject" name="subject" type="text" required value="{{ old('subject') }}" placeholder="{{ __('messages.contact.placeholder_subject') }}" class="brutal-input mt-1.5">
                            @error('subject')<p class="mt-1 text-xs font-black text-red-600 border-2 border-red-600 bg-red-50 rounded px-2 py-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="message" class="font-mono text-xs font-black uppercase tracking-widest">{{ __('messages.contact.message') }} *</label>
                            <textarea id="message" name="message" rows="6" required placeholder="{{ __('messages.contact.placeholder_message') }}" class="brutal-input mt-1.5 resize-none">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-xs font-black text-red-600 border-2 border-red-600 bg-red-50 rounded px-2 py-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="brutal-btn brutal-btn--primary w-full py-3.5 text-sm shadow-[4px_4px_0_0_#111] dark:shadow-[4px_4px_0_0_rgba(203,213,225,0.9)]">{{ __('messages.contact.send') }} →</button>
                        <p class="text-center font-mono text-[11px] font-bold uppercase tracking-widest text-[#6b6b6b]">Throttled: 5 / minute</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>