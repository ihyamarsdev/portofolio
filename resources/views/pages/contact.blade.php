<x-layouts.app title="Contact">
    <section class="py-20 bg-bg-primary">
        <div class="section-container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div>
                    <h1 class="text-4xl font-bold text-[--color-text-primary] mb-4">Get In Touch</h1>
                    <p class="text-lg text-[--color-text-secondary] mb-8">
                        Have a project in mind or want to discuss DevOps opportunities? I'd love to hear from you.
                    </p>

                    <div class="space-y-6 mb-8">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-[--color-bg-secondary] rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[--color-text-primary]">Email</h3>
                                <p class="text-[--color-text-secondary]">{{ $profile->email ?? 'contact@example.com' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-[--color-bg-secondary] rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[--color-text-primary]">Phone</h3>
                                <p class="text-[--color-text-secondary]">{{ $profile->phone ?? '+62 123 4567 890' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-[--color-bg-secondary] rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[--color-text-primary]">Location</h3>
                                <p class="text-[--color-text-secondary]">{{ $profile->location ?? 'Indonesia' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-[--color-bg-secondary] rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[--color-accent-emerald]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-[--color-text-primary]">Response Time</h3>
                                <p class="text-[--color-text-secondary]">Usually within 24 hours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="glass-card p-8">
                    <h2 class="text-xl font-semibold text-[--color-text-primary] mb-6">Send a Message</h2>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name"
                                    class="block text-sm font-medium text-[--color-text-primary] mb-2">Name</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                    class="w-full px-4 py-3 bg-[--color-bg-tertiary] border border-[--color-border] rounded-lg text-[--color-text-primary] placeholder-gray-400 focus:outline-none focus:border-[--color-accent-emerald] transition-colors"
                                    placeholder="Your Name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-[--color-text-primary] mb-2">Email</label>
                                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                    class="w-full px-4 py-3 bg-[--color-bg-tertiary] border border-[--color-border] rounded-lg text-[--color-text-primary] placeholder-gray-400 focus:outline-none focus:border-[--color-accent-emerald] transition-colors"
                                    placeholder="your@email.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject"
                                class="block text-sm font-medium text-[--color-text-primary] mb-2">Subject</label>
                            <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                                    class="w-full px-4 py-3 bg-[--color-bg-tertiary] border border-[--color-border] rounded-lg text-[--color-text-primary] placeholder-gray-400 focus:outline-none focus:border-[--color-accent-emerald] transition-colors"
                                    placeholder="Project Inquiry">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message"
                                class="block text-sm font-medium text-[--color-text-primary] mb-2">Message</label>
                            <textarea name="message" id="message" rows="5" required
                                class="w-full px-4 py-3 bg-[--color-bg-tertiary] border border-[--color-border] rounded-lg text-[--color-text-primary] placeholder-gray-400 focus:outline-none focus:border-[--color-accent-emerald] transition-colors resize-none"
                                placeholder="Tell me about your project...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center">
                            <x-heroicon-o-paper-airplane class="w-5 h-5" />
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>