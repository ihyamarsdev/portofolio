@php
    if (!isset($homeSettings) || !$homeSettings) {
        $homeSettings = \App\Models\HomeSettings::getSettings();
    }
    if (!isset($profile) || !$profile) {
        $profile = \App\Models\Profile::where('is_primary', true)->first();
    }
    $hasCertificates = \App\Models\Certificate::query()->exists();
    $currentUrl = preg_replace('/^http:\/\/[^\/]+/', 'https://ihyamars.my.id', url()->current());
    $siteBrand = $homeSettings?->site_brand ?? 'ihyamars';
    $siteTitle = $homeSettings?->site_title ?? 'DevOps Engineer';
    $siteDesc = $homeSettings?->meta_description ?? 'DevOps & Cloud Infrastructure Portfolio';
    $footerText = $homeSettings?->footer_text ?? 'ihyamars';
    $pageTitle = ($title ?? config('app.name', 'DevOps Portfolio')) . ' | ' . $siteTitle;
    $metaDesc = $description ?? $siteDesc;
    $ogImg = $ogImage ?? 'https://s3.ihyamars.my.id/portofolio/project/CIVA%20%28CLI%20Interactive%20VPS%20Automation.jpeg';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="title" content="{{ $pageTitle }}">
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="DevOps, Cloud Infrastructure Engineer, Kubernetes, Helm, Ansible, Docker, Terraform, CI/CD, GitOps, Ihya Muhammad Adam Rakasurya, ihyamars">
    <meta name="author" content="Ihya Muhammad Adam Rakasurya">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ $currentUrl }}">

    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:image" content="{{ $ogImg }}">
    <meta property="og:site_name" content="{{ $siteBrand }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $currentUrl }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $ogImg }}">

    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💻</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "Person",
                "@@id": "https://ihyamars.my.id/#person",
                "name": "Ihya Muhammad Adam Rakasurya",
                "alternateName": "ihyamars",
                "url": "https://ihyamars.my.id",
                "jobTitle": "DevOps & Cloud Infrastructure Engineer",
                "worksFor": { "@@type": "Organization", "name": "Universitas Amikom Yogyakarta" },
                "sameAs": ["https://github.com/ihyamarsdev","https://linkedin.com/in/ihyamars"]
            },
            {
                "@@type": "WebSite",
                "@@id": "https://ihyamars.my.id/#website",
                "url": "https://ihyamars.my.id",
                "name": "{{ e($siteBrand) }}",
                "description": "{{ e($siteDesc) }}",
                "publisher": { "@@id": "https://ihyamars.my.id/#person" },
                "inLanguage": "{{ app()->getLocale() }}"
            }
        ]
    }
    </script>

    @livewireStyles
    <script>
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

<body class="min-h-screen bg-[#FFFBF0] dark:bg-[#0F172A] text-[#111] dark:text-[#FFFBF0] font-sans antialiased flex flex-col selection:bg-[#2563EB] selection:text-white">
    <!-- Navigation — Neo Brutalism -->
    <nav class="sticky top-0 z-50 bg-white dark:bg-[#1E293B] border-b-[3px] border-black dark:border-[#CBD5E1]">
        <div class="section-container">
            <div class="flex items-center justify-between h-[68px] gap-2">
                <!-- Logo — brutal stamp -->
                <a href="{{ route('home') }}" class="flex items-center gap-1.5 sm:gap-2 group min-h-[44px] min-w-0 shrink">
                    <span class="inline-flex shrink-0 items-center justify-center w-10 h-10 bg-[#2563EB] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[8px] text-[20px] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] group-active:translate-x-[2px] group-active:translate-y-[2px] group-active:shadow-none transition-all">💻</span>
                    <span class="font-black text-[16px] sm:text-[18px] tracking-[-0.04em] uppercase border-[2.5px] border-black dark:border-[#CBD5E1] px-2 sm:px-2.5 py-1.5 rounded-[8px] bg-white dark:bg-[#1E293B] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] group-active:translate-x-[2px] group-active:translate-y-[2px] group-active:shadow-none transition-all truncate">
                        {{ $homeSettings->site_brand }}
                    </span>
                </a>
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center gap-1.5">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.nav.home') }}</a>
                    <a href="{{ route('projects') }}" class="nav-link {{ request()->routeIs('projects*') ? 'active' : '' }}">{{ __('messages.nav.projects') }}</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">{{ __('messages.nav.about') }}</a>
                    @if($hasCertificates)
                        <a href="{{ route('certificates') }}" class="nav-link {{ request()->routeIs('certificates') ? 'active' : '' }}">{{ __('messages.nav.certificates') }}</a>
                    @endif
                    <a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog*') ? 'active' : '' }}">{{ __('messages.nav.blog') }}</a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('messages.nav.contact') }}</a>
                </div>

                <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                    <!-- Language Switcher — brutal pill -->
                    <div class="flex items-center gap-1 p-1 bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[10px] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] text-xs font-black uppercase">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="min-w-[44px] min-h-[44px] px-3 py-2 rounded-[6px] border-2 transition-all inline-flex items-center justify-center gap-1 {{ app()->getLocale() === 'en' ? 'bg-black text-white border-black dark:bg-[#2563EB] dark:text-white dark:border-[#CBD5E1] shadow-[2px_2px_0_0_#111]' : 'border-transparent text-[#666666] dark:text-[#adadad] hover:text-black dark:hover:text-white' }}">
                            <span>EN</span>
                        </a>
                        <a href="{{ route('lang.switch', 'id') }}"
                            class="min-w-[44px] min-h-[44px] px-3 py-2 rounded-[6px] border-2 transition-all inline-flex items-center justify-center gap-1 {{ app()->getLocale() === 'id' ? 'bg-black text-white border-black dark:bg-[#2563EB] dark:text-white dark:border-[#CBD5E1] shadow-[2px_2px_0_0_#111]' : 'border-transparent text-[#666666] dark:text-[#adadad] hover:text-black dark:hover:text-white' }}">
                            <span>ID</span>
                        </a>
                    </div>

                    <!-- Theme Toggle — brutal square -->
                    <button onclick="toggleTheme()"
                        class="w-11 h-11 inline-flex items-center justify-center bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[10px] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all"
                        aria-label="Toggle theme">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button type="button"
                        class="lg:hidden w-11 h-11 inline-flex items-center justify-center bg-[#2563EB] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[10px] shadow-[3px_3px_0_0_#111] dark:shadow-[3px_3px_0_0_rgba(203,213,225,0.9)] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all"
                        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" aria-label="Menu">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu — brutal dropdown -->
            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <div class="mt-2 bg-white dark:bg-[#1E293B] border-[2.5px] border-black dark:border-[#CBD5E1] rounded-[12px] shadow-[5px_5px_0_0_#111] dark:shadow-[4px_4px_0_0_rgba(203,213,225,0.9)] p-2 space-y-1">
                    <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded-[8px] font-black uppercase text-sm tracking-tight {{ request()->routeIs('home') ? 'bg-[#2563EB] text-white border-2 border-black' : 'hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black border-2 border-transparent' }}">{{ __('messages.nav.home') }}</a>
                    <a href="{{ route('projects') }}" class="block py-2.5 px-4 rounded-[8px] font-black uppercase text-sm tracking-tight {{ request()->routeIs('projects*') ? 'bg-[#2563EB] text-white border-2 border-black' : 'hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black border-2 border-transparent' }}">{{ __('messages.nav.projects') }}</a>
                    <a href="{{ route('about') }}" class="block py-2.5 px-4 rounded-[8px] font-black uppercase text-sm tracking-tight {{ request()->routeIs('about') ? 'bg-[#2563EB] text-white border-2 border-black' : 'hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black border-2 border-transparent' }}">{{ __('messages.nav.about') }}</a>
                    @if($hasCertificates)
                        <a href="{{ route('certificates') }}" class="block py-2.5 px-4 rounded-[8px] font-black uppercase text-sm tracking-tight {{ request()->routeIs('certificates') ? 'bg-[#2563EB] text-white border-2 border-black' : 'hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black border-2 border-transparent' }}">{{ __('messages.nav.certificates') }}</a>
                    @endif
                    <a href="{{ route('blog') }}" class="block py-2.5 px-4 rounded-[8px] font-black uppercase text-sm tracking-tight {{ request()->routeIs('blog*') ? 'bg-[#2563EB] text-white border-2 border-black' : 'hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black border-2 border-transparent' }}">{{ __('messages.nav.blog') }}</a>
                    <a href="{{ route('contact') }}" class="block py-2.5 px-4 rounded-[8px] font-black uppercase text-sm tracking-tight {{ request()->routeIs('contact') ? 'bg-black text-white dark:bg-[#2563EB] dark:text-white border-2 border-black' : 'bg-[#2563EB] text-white border-2 border-black shadow-[3px_3px_0_0_#111] text-center mt-2' }}">{{ __('messages.nav.contact') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

    <!-- Footer — brutal black bar -->
    <footer class="mt-16 bg-[#111] dark:bg-[#0F172A] text-[#FFFBF0] border-t-[3px] border-black dark:border-[#CBD5E1]">
        <div class="section-container py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex w-8 h-8 items-center justify-center bg-[#2563EB] border-2 border-black rounded-[6px] text-lg">💻</span>
                        <span class="font-black text-lg tracking-[-0.04em] uppercase bg-white text-black px-2 py-0.5 rounded-[6px] border-2 border-black">{{ $homeSettings->site_brand }}</span>
                    </div>
                    <p class="text-sm leading-6 text-[#d6d6d6] max-w-sm">
                        {{ $homeSettings->site_title }} — cloud infrastructure, CI/CD pipelines, and container orchestration. Minimal, reliable, brutal.
                    </p>
                    <div class="mt-4 inline-flex items-center gap-2 font-mono text-xs font-bold uppercase tracking-widest bg-[#2563EB] text-white px-3 py-1.5 border-2 border-black rounded-full">
                        <span class="w-2 h-2 bg-black rounded-full animate-pulse"></span> Available for work
                    </div>
                </div>

                <div>
                    <h3 class="font-black uppercase tracking-tight text-sm mb-4 flex items-center gap-2"><span class="w-2 h-8 bg-[#2563EB] border-2 border-black rounded-full"></span> Quick Links</h3>
                    <ul class="space-y-2 text-sm font-bold">
                        <li><a href="{{ route('projects') }}" class="inline-flex items-center min-h-[44px] bg-white text-black px-4 py-2 rounded-[8px] border-2 border-black hover:bg-[#2563EB] hover:text-white transition-colors">Projects →</a></li>
                        <li><a href="{{ route('blog') }}" class="inline-flex items-center min-h-[44px] bg-white text-black px-4 py-2 rounded-[8px] border-2 border-black hover:bg-[#00D1FF] transition-colors">Blog →</a></li>
                        <li><a href="{{ route('contact') }}" class="inline-flex items-center min-h-[44px] bg-[#2563EB] text-white px-4 py-2 rounded-[8px] border-2 border-black shadow-[3px_3px_0_0_#fff] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-none transition-all">Contact →</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-black uppercase tracking-tight text-sm mb-4 flex items-center gap-2"><span class="w-2 h-8 bg-[#00D1FF] border-2 border-black rounded-full"></span> Connect</h3>
                    <div class="flex gap-3">
                        <a href="{{ $profile?->github_url ? 'https://github.com/' . $profile->github_url : '#' }}" class="w-11 h-11 inline-flex items-center justify-center bg-white text-black border-[2.5px] border-black rounded-[10px] shadow-[3px_3px_0_0_#FFFBF0] hover:bg-[#2563EB] hover:text-white active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all" aria-label="GitHub">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg>
                        </a>
                        <a href="{{ $profile?->linkedin_url ? 'https://linkedin.com/in/' . $profile->linkedin_url : '#' }}" class="w-11 h-11 inline-flex items-center justify-center bg-white text-black border-[2.5px] border-black rounded-[10px] shadow-[3px_3px_0_0_#FFFBF0] hover:bg-[#00D1FF] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all" aria-label="LinkedIn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>
                        </a>
                        <a href="{{ $profile?->twitter_url ? 'https://x.com/' . $profile->twitter_url : '#' }}" class="w-11 h-11 inline-flex items-center justify-center bg-white text-black border-[2.5px] border-black rounded-[10px] shadow-[3px_3px_0_0_#FFFBF0] hover:bg-[#FF90E8] active:translate-x-[2px] active:translate-y-[2px] active:shadow-none transition-all" aria-label="X">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t-[2px] border-[#2a2a2a] flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="font-mono text-xs font-bold uppercase tracking-widest text-[#adadad]">
                    © {{ date('Y') }} {{ $homeSettings->footer_text }} — Built brutal, shipped minimal.
                </p>
                <span class="font-mono text-xs font-black uppercase tracking-[0.12em] bg-white text-black px-2 py-1 rounded border-2 border-black">Neo-Brutalism × Minimalist</span>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>

</html>