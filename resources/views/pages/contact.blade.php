<x-layouts.app title="Contact">
    <section class="bg-bg-primary">
        <div class="section-container py-16 sm:py-24">
            <div class="grid gap-8 lg:grid-cols-[.9fr_1.1fr] lg:items-start">
                <div>
                    <p class="font-mono text-xs uppercase tracking-widest text-accent-sky">Contact</p>
                    <h1 class="mt-2 text-4xl font-semibold tracking-[-.05em] text-text-primary sm:text-6xl">Let’s build something reliable.</h1>
                    <p class="mt-5 text-base leading-8 text-text-secondary sm:text-lg">Have a project, infrastructure challenge, or role to discuss? Send a message.</p>
                    <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                        @foreach([
                            ['Email', $profile?->email ?? 'contact@example.com'],
                            ['Phone', $profile?->phone ?? '+62 123 4567 890'],
                            ['Location', $profile?->location ?? 'Indonesia'],
                            ['Response', 'Usually within 24 hours'],
                        ] as [$label, $value])
                            <div class="rounded-2xl bg-bg-card p-4 shadow-[0_0_0_1px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                                <p class="text-xs font-medium uppercase tracking-widest text-text-muted">{{ $label }}</p>
                                <p class="mt-2 break-words text-sm font-semibold text-text-primary">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl bg-bg-card p-5 shadow-[0_0_0_1px_rgba(0,0,0,.08),0_16px_50px_rgba(0,0,0,.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08),0_16px_50px_rgba(0,0,0,.35)] sm:p-8">
                    <h2 class="text-2xl font-semibold tracking-[-.04em] text-text-primary">Send a message</h2>
                    @if(session('success'))
                        <div class="mt-5 rounded-xl bg-emerald-500/10 p-4 text-sm text-emerald-500">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-5">
                        @csrf
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="text-sm font-medium text-text-primary">Name</label>
                                <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="Your name" class="mt-2 w-full rounded-xl bg-bg-secondary px-4 py-3 text-text-primary outline-none shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition focus:shadow-[0_0_0_2px_rgba(14,165,233,.6)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                                @error('name')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="text-sm font-medium text-text-primary">Email</label>
                                <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="you@email.com" class="mt-2 w-full rounded-xl bg-bg-secondary px-4 py-3 text-text-primary outline-none shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition focus:shadow-[0_0_0_2px_rgba(14,165,233,.6)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                                @error('email')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="text-sm font-medium text-text-primary">Subject</label>
                            <input id="subject" name="subject" type="text" required value="{{ old('subject') }}" placeholder="Project inquiry" class="mt-2 w-full rounded-xl bg-bg-secondary px-4 py-3 text-text-primary outline-none shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition focus:shadow-[0_0_0_2px_rgba(14,165,233,.6)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">
                            @error('subject')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="message" class="text-sm font-medium text-text-primary">Message</label>
                            <textarea id="message" name="message" rows="6" required placeholder="Tell me about your project..." class="mt-2 w-full resize-none rounded-xl bg-bg-secondary px-4 py-3 text-text-primary outline-none shadow-[0_0_0_1px_rgba(0,0,0,.08)] transition focus:shadow-[0_0_0_2px_rgba(14,165,233,.6)] dark:shadow-[0_0_0_1px_rgba(255,255,255,.08)]">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-text-primary px-5 py-3 text-sm font-semibold text-bg-primary transition hover:opacity-90">Send message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
