@php
    if (!isset($homeSettings)) {
        $homeSettings = \App\Models\HomeSettings::getSettings();
    }
    if (!isset($profile)) {
        $profile = \App\Models\Profile::where('is_primary', true)->first();
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'DevOps Portfolio') }} | {{ $homeSettings->site_title }}</title>
    <meta name="description" content="{{ $description ?? $homeSettings->meta_description }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💻</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @livewireStyles
    <script>
        // Check local storage or default to dark mode
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</head>

<body class="min-h-screen bg-bg-body text-text-primary font-sans antialiased flex flex-col">
    <!-- Navigation -->
    <nav
        class="fixed top-0 left-0 right-0 z-50 bg-brand-primary backdrop-blur-lg">
        <div class="section-container">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl">💻</span>
                    <span
                        class="font-mono font-bold text-lg text-text-secondary transition-colors">
                        {{ $homeSettings->site_brand }}
                    </span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('projects') }}"
                        class="nav-link {{ request()->routeIs('projects*') ? 'active' : '' }}">Projects</a>
                    <a href="{{ route('about') }}"
                        class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                    <a href="{{ route('certificates') }}"
                        class="nav-link {{ request()->routeIs('certificates') ? 'active' : '' }}">Certificates</a>
                    <a href="{{ route('blog') }}"
                        class="nav-link {{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a>
                    <a href="{{ route('contact') }}"
                        class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                </div>

                <div class="flex items-center gap-4">

                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()"
                        class="p-2 text-[--color-text-secondary] hover:text-[--color-text-primary] transition-colors rounded-lg hover:bg-[--color-bg-secondary]">
                        <!-- Sun Icon (Show in Dark Mode) -->
                        <svg class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <!-- Moon Icon (Show in Light Mode) -->
                        <svg class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button type="button"
                        class="md:hidden p-2 text-[--color-text-secondary] hover:text-[--color-text-primary]"
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden py-4 space-y-2">
                <a href="{{ route('home') }}"
                    class="block py-2 px-4 rounded-lg nav-link {{ request()->routeIs('home') ? 'active bg-[--color-bg-secondary]' : '' }}">Home</a>
                <a href="{{ route('projects') }}"
                    class="block py-2 px-4 rounded-lg nav-link {{ request()->routeIs('projects*') ? 'active bg-[--color-bg-secondary]' : '' }}">Projects</a>
                <a href="{{ route('about') }}"
                    class="block py-2 px-4 rounded-lg nav-link {{ request()->routeIs('about') ? 'active bg-[--color-bg-secondary]' : '' }}">About</a>
                <a href="{{ route('certificates') }}"
                    class="block py-2 px-4 rounded-lg nav-link {{ request()->routeIs('certificates') ? 'active bg-[--color-bg-secondary]' : '' }}">Certificates</a>
                <a href="{{ route('blog') }}"
                    class="block py-2 px-4 rounded-lg nav-link {{ request()->routeIs('blog*') ? 'active bg-[--color-bg-secondary]' : '' }}">Blog</a>
                <a href="{{ route('contact') }}"
                    class="block py-2 px-4 rounded-lg nav-link {{ request()->routeIs('contact') ? 'active bg-[--color-bg-secondary]' : '' }}">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-18 flex-grow w-full">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-bg-primary border-t border-[--color-border] mt-10">
        <div class="section-container py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl">💻</span>
                        <span class="font-mono font-bold text-lg text-[--color-text-primary]">{{ $homeSettings->site_brand }}</span>
                    </div>
                    <p class="text-[--color-text-secondary] text-sm">
                        {{ $homeSettings->site_title }} specializing in cloud infrastructure, CI/CD pipelines, and
                        container orchestration.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold text-[--color-text-primary] mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('projects') }}"
                                class="text-[--color-text-secondary] hover:text-[--color-brand-primary] transition-colors">Projects</a>
                        </li>
                        <li><a href="{{ route('blog') }}"
                                class="text-[--color-text-secondary] hover:text-[--color-brand-primary] transition-colors">Blog</a>
                        </li>
                        <li><a href="{{ route('contact') }}"
                                class="text-[--color-text-secondary] hover:text-[--color-brand-primary] transition-colors">Contact</a>
                        </li>
                    </ul>
                </div>

                <!-- Connect -->
                <div>
                    <h3 class="font-semibold text-[--color-text-primary] mb-4">Connect</h3>
                    <div class="flex gap-4">
                        <a href="{{ $profile?->github_url ?? '#' }}"
                            class="text-[--color-text-secondary] hover:text-[--color-brand-primary] transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                            </svg>
                        </a>
                        <a href="{{ $profile?->linkedin_url ?? '#' }}"
                            class="text-[--color-text-secondary] hover:text-[--color-brand-primary] transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                        <a href="{{ $profile?->twitter_url ?? '#' }}"
                            class="text-[--color-text-secondary] hover:text-[--color-brand-primary] transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-[--color-border] mt-5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[--color-text-secondary]">
                    © {{ date('Y') }} {{ $homeSettings->footer_text }}
                </p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>

</html>