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
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23141413'/><text x='50' y='68' text-anchor='middle' font-family='monospace' font-size='52' fill='%23C84B1F'>&gt;_</text></svg>">

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
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</head>

<body class="min-h-screen bg-canvas text-ink flex flex-col antialiased">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b border-line bg-canvas/95">
        <div class="section-container">
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-2.5 py-2 font-mono text-sm font-semibold tracking-tight text-ink">
                    <span class="inline-flex h-7 w-7 items-center justify-center border border-ink text-[11px] leading-none transition-colors duration-150 group-hover:border-accent group-hover:text-accent">&gt;_</span>
                    <span class="transition-colors duration-150 group-hover:text-accent">{{ $siteBrand }}</span>
                    <span class="hidden font-normal text-ink-3 sm:inline">/ {{ $siteTitle }}</span>
                </a>

                <div class="hidden items-center gap-7 lg:flex">
                    @php($navItems = [
                        ['route' => 'home', 'pattern' => 'home', 'label' => __('messages.nav.home')],
                        ['route' => 'projects', 'pattern' => 'projects*', 'label' => __('messages.nav.projects')],
                        ['route' => 'about', 'pattern' => 'about', 'label' => __('messages.nav.about')],
                    ])
                    @if($hasCertificates)
                        @php($navItems[] = ['route' => 'certificates', 'pattern' => 'certificates', 'label' => __('messages.nav.certificates')])
                    @endif
                    @php($navItems[] = ['route' => 'blog', 'pattern' => 'blog*', 'label' => __('messages.nav.blog')])
                    @php($navItems[] = ['route' => 'contact', 'pattern' => 'contact', 'label' => __('messages.nav.contact')])

                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}"
                            class="border-b-2 py-5 text-sm transition-colors duration-150 {{ request()->routeIs($item['pattern']) ? 'border-accent text-ink' : 'border-transparent text-ink-2 hover:text-ink' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="flex items-center gap-2">
                    <div class="hidden items-center gap-1 border border-line px-1 py-0.5 sm:flex">
                        <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 font-mono text-[11px] uppercase tracking-wider transition-colors duration-150 {{ app()->getLocale() === 'en' ? 'text-accent' : 'text-ink-3 hover:text-ink' }}">EN</a>
                        <span class="text-line">/</span>
                        <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 font-mono text-[11px] uppercase tracking-wider transition-colors duration-150 {{ app()->getLocale() === 'id' ? 'text-accent' : 'text-ink-3 hover:text-ink' }}">ID</a>
                    </div>

                    <button type="button" onclick="toggleTheme()" class="inline-flex h-9 w-9 items-center justify-center border border-line text-ink-2 transition-colors duration-150 hover:border-ink hover:text-ink" aria-label="Toggle theme">
                        <svg class="h-4 w-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg class="h-4 w-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>

                    <button type="button" id="menu-toggle" class="inline-flex h-9 w-9 items-center justify-center border border-line text-ink lg:hidden" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu" onclick="toggleMenu()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile drawer -->
            <div id="mobile-menu" class="hidden border-t border-line py-3 lg:hidden">
                <div class="flex flex-col">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="border-b border-line py-3 text-sm {{ request()->routeIs($item['pattern']) ? 'text-accent' : 'text-ink-2' }}">{{ $item['label'] }}</a>
                    @endforeach
                    <div class="flex gap-4 pt-4 font-mono text-[11px] uppercase tracking-wider sm:hidden">
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'text-accent' : 'text-ink-3' }}">EN</a>
                        <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'text-accent' : 'text-ink-3' }}">ID</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-24 border-t border-line bg-surface">
        <div class="section-container py-12">
            <div class="grid gap-10 md:grid-cols-12">
                <div class="md:col-span-5">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-mono text-sm font-semibold text-ink">
                        <span class="inline-flex h-6 w-6 items-center justify-center border border-ink text-[10px]">&gt;_</span>
                        {{ $siteBrand }}
                    </a>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-2">{{ $siteTitle }} — {{ $siteDesc }}</p>
                </div>

                <div class="md:col-span-3">
                    <h3 class="font-mono text-[11px] uppercase tracking-[0.16em] text-ink-3">{{ __('messages.footer.quick_links') }}</h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><a href="{{ route('projects') }}" class="text-ink-2 transition-colors duration-150 hover:text-accent">{{ __('messages.nav.projects') }}</a></li>
                        <li><a href="{{ route('blog') }}" class="text-ink-2 transition-colors duration-150 hover:text-accent">{{ __('messages.nav.blog') }}</a></li>
                        <li><a href="{{ route('cv.preview') }}" class="text-ink-2 transition-colors duration-150 hover:text-accent">{{ __('messages.nav.cv') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-ink-2 transition-colors duration-150 hover:text-accent">{{ __('messages.nav.contact') }}</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4">
                    <h3 class="font-mono text-[11px] uppercase tracking-[0.16em] text-ink-3">{{ __('messages.footer.connect') }}</h3>
                    <ul class="mt-4 space-y-2 font-mono text-[11px] uppercase tracking-wider">
                        @if($profile?->email)
                            <li><a href="mailto:{{ $profile->email }}" class="text-ink-2 transition-colors duration-150 hover:text-accent">{{ $profile->email }}</a></li>
                        @endif
                        @if($profile?->github_url)
                            <li><a href="{{ str_starts_with($profile->github_url, 'http') ? $profile->github_url : 'https://github.com/'.$profile->github_url }}" target="_blank" rel="noopener noreferrer" class="text-ink-2 transition-colors duration-150 hover:text-accent">GitHub ↗</a></li>
                        @endif
                        @if($profile?->linkedin_url)
                            <li><a href="{{ str_starts_with($profile->linkedin_url, 'http') ? $profile->linkedin_url : 'https://linkedin.com/in/'.$profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-ink-2 transition-colors duration-150 hover:text-accent">LinkedIn ↗</a></li>
                        @endif
                        @if($profile?->twitter_url)
                            <li><a href="{{ str_starts_with($profile->twitter_url, 'http') ? $profile->twitter_url : 'https://x.com/'.$profile->twitter_url }}" target="_blank" rel="noopener noreferrer" class="text-ink-2 transition-colors duration-150 hover:text-accent">X ↗</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col gap-2 border-t border-line pt-6 font-mono text-[11px] uppercase tracking-wider text-ink-3 sm:flex-row sm:justify-between">
                <span>© {{ date('Y') }} {{ $footerText }} — {{ __('messages.footer.rights') }}</span>
                <span>{{ $homeSettings?->site_brand }}</span>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('menu-toggle');
            const isHidden = menu.classList.toggle('hidden') === false;
            button.setAttribute('aria-expanded', String(isHidden));
        }
    </script>

    @livewireScripts
    @stack('scripts')
</body>

</html>
