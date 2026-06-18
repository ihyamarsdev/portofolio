<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin-top: 2.2cm;
            margin-bottom: 2cm;
            margin-left: 0;
            margin-right: 0;
        }
        @page :first {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --accent: #b45309;
            --accent-light: #fef3c7;
            --text-primary: #18181b;
            --text-secondary: #52525b;
            --text-muted: #a1a1aa;
            --bg: #fafafa;
            --paper: #ffffff;
        }

        body {
            font-family: 'Outfit', system-ui, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg);
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: var(--paper);
            display: grid;
            grid-template-columns: 1fr;
            margin: 0 auto;
        }

        @media screen {
            body {
                background: #f1f5f9;
                padding: 2rem 0 6rem 0;
            }
            .page {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }
        }

        /* ═══════════════════════════════════════════════
           HEADER - Asymmetric Zen
           ═══════════════════════════════════════════════ */
        .header {
            padding: 36px 40px 24px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 24px;
            align-items: end;
        }

        .identity {
            position: relative;
        }

        .identity::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent);
        }

        .name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24pt;
            font-weight: 300;
            letter-spacing: -1px;
            color: var(--text-primary);
            line-height: 1.1;
        }

        .title {
            font-size: 10pt;
            font-weight: 400;
            color: var(--accent);
            margin-top: 6px;
            letter-spacing: 0.5px;
        }

        .contact {
            text-align: right;
        }

        .contact-item {
            display: block;
            font-size: 9pt;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-decoration: none;
        }

        .contact-item a {
            color: var(--text-muted);
            text-decoration: none;
        }

        /* ═══════════════════════════════════════════════
           MAIN CONTENT
           ═══════════════════════════════════════════════ */
        .content {
            padding: 0 40px 32px;
            display: block;
        }

        /* Section */
        .section {
            margin-bottom: 24px;
        }
        .section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 7pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e4e4e7;
        }

        /* Summary */
        .summary {
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 300;
            max-width: 480px;
        }

        .experience-list {
            display: block;
        }

        .experience-item {
            page-break-inside: avoid;
            display: block;
            margin-bottom: 16px;
        }

        .experience-item:after {
            content: "";
            display: table;
            clear: both;
        }

        .experience-item:last-child {
            margin-bottom: 0;
        }

        .date-block {
            float: left;
            width: 80px;
            padding-top: 2px;
        }

        .year {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16pt;
            font-weight: 300;
            color: var(--text-muted);
            line-height: 1;
        }

        .month-range {
            font-size: 8pt;
            color: var(--text-muted);
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .experience-content {
            border-left: 1px solid #e4e4e7;
            padding-left: 16px;
            margin-left: 96px;
        }

        .position {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 11pt;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .company-location {
            font-size: 9pt;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .company {
            font-weight: 500;
        }

        .location {
            color: var(--text-muted);
        }

        .description {
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 300;
        }

        ul {
            margin-top: 6px;
            padding-left: 14px;
        }

        li {
            color: var(--text-secondary);
            margin-bottom: 2px;
            line-height: 1.5;
            font-weight: 300;
        }

        li::marker {
            color: var(--accent);
        }

        /* Education */
        .education-list {
            display: block;
        }

        .education-item {
            page-break-inside: avoid;
            margin-bottom: 16px;
        }

        .education-item:last-child {
            margin-bottom: 0;
        }

        .degree {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 10pt;
            font-weight: 500;
            color: var(--text-primary);
        }

        .institution {
            font-size: 9pt;
            color: var(--text-secondary);
            margin-top: 2px;
            font-weight: 300;
        }

        .year {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 8pt;
            color: var(--accent);
            margin-top: 4px;
            letter-spacing: 1px;
        }

        /* Skills */
        .skills-grid {
            display: block;
        }

        .skill-category {
            page-break-inside: avoid;
            margin-bottom: 16px;
        }

        .skill-category:last-child {
            margin-bottom: 0;
        }

        .category-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .skill-items {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .skill-item {
            font-size: 9pt;
            color: var(--text-secondary);
            font-weight: 300;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .skill-name {
            font-weight: 400;
        }

        .skill-years {
            font-size: 8pt;
            color: var(--accent);
            font-weight: 500;
        }

        /* Social Links */
        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .social-item {
            font-size: 8pt;
            color: var(--text-muted);
        }

        /* Footer */
        .footer {
            padding: 24px 48px;
            border-top: 1px solid #f4f4f5;
        }

        .footer-text {
            font-size: 7pt;
            color: var(--text-muted);
            letter-spacing: 1px;
        }

        /* Print */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page {
                display: block !important;
                min-height: auto !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        {{-- Header --}}
        <header class="header">
            <div class="identity">
                <h1 class="name">{{ $profile->name ?? 'Your Name' }}</h1>
                <p class="title">{{ $profile->title ?? 'Professional Title' }}</p>
            </div>
            <div class="contact">
                @if($profile->email ?? false)
                    <span class="contact-item"><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></span>
                @endif
                @if($profile->phone ?? false)
                    <span class="contact-item">{{ $profile->phone }}</span>
                @endif
                @if($profile->location ?? false)
                    <span class="contact-item">{{ $profile->location }}</span>
                @endif
                @if(isset($profile->social_links) && count($profile->social_links) > 0)
                    <div class="social-links">
                        @foreach($profile->social_links as $link)
                            @if(isset($link['platform']) && isset($link['url']))
                                <span class="social-item">{{ $link['platform'] }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        {{-- Main Content --}}
        <main class="content">
            {{-- Summary --}}
            @if($profile->biography ?? false)
                <section class="section">
                    <h2 class="section-title">About</h2>
                    <p class="summary">{{ strip_tags($profile->biography) }}</p>
                </section>
            @endif

            {{-- Experience --}}
            @if($experiences->count() > 0)
                <section class="section">
                    <h2 class="section-title">Experience</h2>
                    <div class="experience-list">
                        @foreach($experiences as $experience)
                            <article class="experience-item">
                                <div class="date-block">
                                    <div class="year">{{ $experience->start_date->format('Y') }}</div>
                                    <div class="month-range">
                                        {{ $experience->start_date->format('M') }} —
                                        {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Now' }}
                                    </div>
                                </div>
                                <div class="experience-content">
                                    <h3 class="position">{{ $experience->position }}</h3>
                                    <p class="company-location">
                                        <span class="company">{{ $experience->company }}</span>
                                        @if($experience->location)
                                            <span class="location"> · {{ $experience->location }}</span>
                                        @endif
                                    </p>
                                    @if($experience->description)
                                        <p class="description">{!! nl2br(e(strip_tags($experience->description))) !!}</p>
                                    @endif
                                    @if($experience->achievements && count($experience->achievements) > 0)
                                        <ul>
                                            @foreach($experience->achievements as $achievement)
                                                <li>{{ $achievement }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if(isset($totalExperiences) && $totalExperiences > 3)
                        <div class="cv-note" style="font-size: 8.5pt; color: var(--text-muted); font-style: italic; margin-top: 12px; border-top: 1px dashed var(--border); padding-top: 12px;">
                            * Note: Showing only the 3 latest experiences. View the remaining {{ $totalExperiences - 3 }} experiences on my website: <a href="{{ route('about') }}" style="color: var(--accent); text-decoration: none;">{{ str_replace(['http://', 'https://'], '', route('about')) }}</a>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Education --}}
            @if($educations->count() > 0)
                <section class="section">
                    <h2 class="section-title">Education</h2>
                    <div class="education-list">
                        @foreach($educations as $education)
                            <article class="education-item">
                                <h3 class="degree">{{ $education->degree }}</h3>
                                <p class="institution">{{ $education->institution }} · {{ $education->field_of_study }}</p>
                                <p class="year">{{ $education->start_date->format('Y') }} —
                                    {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</p>
                            </article>
                        @endforeach
                    </div>
                    @if(isset($totalEducations) && $totalEducations > 3)
                        <div class="cv-note" style="font-size: 8.5pt; color: var(--text-muted); font-style: italic; margin-top: 12px; border-top: 1px dashed var(--border); padding-top: 12px;">
                            * Note: Showing only the 3 latest education histories. View the remaining {{ $totalEducations - 3 }} education histories on my website: <a href="{{ route('about') }}" style="color: var(--accent); text-decoration: none;">{{ str_replace(['http://', 'https://'], '', route('about')) }}</a>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Skills --}}
            @if($skills->count() > 0)
                <section class="section">
                    <h2 class="section-title">Skills</h2>
                    <div class="skills-grid">
                        @php
                            $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
                        @endphp
                        @foreach($groupedSkills as $categoryName => $categorySkills)
                            <div class="skill-category">
                                <h4 class="category-name">{{ $categoryName }}</h4>
                                <div class="skill-items">
                                    @foreach($categorySkills as $skill)
                                        <div class="skill-item">
                                            <span class="skill-name">{{ $skill->name }}</span>
                                            @if($skill->years_experience > 0)
                                                @php
                                                    $years = floatval($skill->years_experience);
                                                    $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                                @endphp
                                                <span class="skill-years">{{ $displayYears }}y</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>
    @include('cv.partials._toolbar')
</body>

</html>