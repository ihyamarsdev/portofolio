<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }} | Curriculum Vitae</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Crimson+Pro:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
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
            --accent: #881337;
            --accent-light: #ffe4e6;
            --text-primary: #1c1917;
            --text-secondary: #44403c;
            --text-muted: #78716c;
            --border: #d6d3d1;
            --bg: #fafaf9;
            --paper: #ffffff;
        }

        body {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg);
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: var(--paper);
            position: relative;
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

        /* Decorative Border */
        .page::before {
            content: '';
            position: absolute;
            top: 20mm;
            left: 20mm;
            right: 20mm;
            bottom: 20mm;
            border: 1px solid var(--border);
            pointer-events: none;
        }

        .page::after {
            content: '';
            position: absolute;
            top: 22mm;
            left: 22mm;
            right: 22mm;
            bottom: 22mm;
            border: 1px solid var(--border);
            pointer-events: none;
        }

        /* ═══════════════════════════════════════════════
           HEADER - Editorial Style
           ═══════════════════════════════════════════════ */
        .header {
            padding: 48mm 40mm 32mm;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 3px;
            background: var(--accent);
        }

        .name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 36pt;
            font-weight: 600;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .title {
            font-family: 'Crimson Pro', serif;
            font-size: 13pt;
            font-style: italic;
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 400;
        }

        .contact-line {
            font-family: 'Crimson Pro', serif;
            font-size: 10pt;
            color: var(--text-muted);
            letter-spacing: 1px;
        }

        .contact-line span {
            margin: 0 12px;
        }

        .contact-line a {
            color: var(--text-muted);
            text-decoration: none;
        }

        /* ═══════════════════════════════════════════════
           MAIN CONTENT
           ═══════════════════════════════════════════════ */
        .content {
            padding: 8mm 40mm 40mm;
        }

        /* Section */
        .section {
            margin-bottom: 28px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 11pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: var(--text-primary);
            white-space: nowrap;
        }

        /* Summary */
        .summary {
            color: var(--text-secondary);
            line-height: 1.8;
            text-align: center;
            font-style: italic;
            max-width: 450px;
            margin: 0 auto;
        }

        .experience-list {
            display: block;
        }

        .experience-item {
            page-break-inside: avoid;
            position: relative;
            padding-left: 24px;
            margin-bottom: 20px;
        }

        .experience-item:last-child {
            margin-bottom: 0;
        }

        .experience-item::before {
            content: '§';
            position: absolute;
            left: 0;
            top: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: 14pt;
            color: var(--accent);
        }

        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
            gap: 16px;
        }

        .position {
            font-family: 'Cormorant Garamond', serif;
            font-size: 12pt;
            font-weight: 600;
            color: var(--text-primary);
        }

        .date {
            font-family: 'Crimson Pro', serif;
            font-size: 10pt;
            font-style: italic;
            color: var(--accent);
            white-space: nowrap;
        }

        .company-location {
            font-size: 10pt;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .company {
            font-weight: 500;
            color: var(--text-secondary);
        }

        .description {
            color: var(--text-secondary);
            line-height: 1.7;
            margin-top: 6px;
        }

        ul {
            margin-top: 8px;
            padding-left: 20px;
        }

        li {
            color: var(--text-secondary);
            margin-bottom: 4px;
            line-height: 1.6;
        }

        li::marker {
            color: var(--accent);
        }

        .education-list {
            display: block;
        }

        .education-item {
            page-break-inside: avoid;
            text-align: center;
            margin-bottom: 16px;
        }

        .education-item:last-child {
            margin-bottom: 0;
        }

        .degree {
            font-family: 'Cormorant Garamond', serif;
            font-size: 12pt;
            font-weight: 600;
            color: var(--text-primary);
        }

        .institution {
            font-size: 10pt;
            color: var(--text-muted);
            font-style: italic;
            margin-top: 2px;
        }

        .year {
            font-family: 'Crimson Pro', serif;
            font-size: 9pt;
            color: var(--accent);
            margin-top: 4px;
        }

        .meta {
            font-size: 9pt;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Skills */
        .skills-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .skill-category {
            text-align: center;
            page-break-inside: avoid;
        }

        .category-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 10pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .skill-items {
            font-size: 10pt;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .skill-item {
            display: inline;
        }

        .skill-separator {
            color: var(--accent);
            margin: 0 6px;
        }

        .skill-years {
            font-size: 9pt;
            color: var(--text-muted);
            font-style: italic;
        }

        /* Social Links */
        .social-links {
            text-align: center;
            margin-top: 16px;
        }

        .social-item {
            font-size: 9pt;
            color: var(--text-muted);
            margin: 0 8px;
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
            <h1 class="name">{{ $profile->name ?? 'Your Name' }}</h1>
            <p class="title">{{ $profile->title ?? 'Professional Title' }}</p>
            <div class="contact-line">
                @if($profile->email ?? false)
                    <span><a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></span>
                @endif
                @if($profile->phone ?? false)
                    <span>{{ $profile->phone }}</span>
                @endif
                @if($profile->location ?? false)
                    <span>{{ $profile->location }}</span>
                @endif
            </div>
            @if(isset($profile->social_links) && count($profile->social_links) > 0)
                <div class="social-links">
                    @foreach($profile->social_links as $link)
                        @if(isset($link['platform']) && isset($link['url']))
                            <span class="social-item">{{ $link['platform'] }}</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </header>

        {{-- Main Content --}}
        <main class="content">
            {{-- Summary --}}
            @if($profile->biography ?? false)
                <section class="section">
                    <div class="section-header">
                        <div class="section-line"></div>
                        <h2 class="section-title">Profile</h2>
                        <div class="section-line"></div>
                    </div>
                    <p class="summary">{{ strip_tags($profile->biography) }}</p>
                </section>
            @endif

            {{-- Experience --}}
            @if($experiences->count() > 0)
                <section class="section">
                    <div class="section-header">
                        <div class="section-line"></div>
                        <h2 class="section-title">Experience</h2>
                        <div class="section-line"></div>
                    </div>
                    <div class="experience-list">
                        @foreach($experiences as $experience)
                            <article class="experience-item">
                                <div class="experience-header">
                                    <h3 class="position">{{ $experience->position }}</h3>
                                    <span class="date">
                                        {{ $experience->start_date->format('M Y') }} —
                                        {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                                    </span>
                                </div>
                                <p class="company-location">
                                    <span class="company">{{ $experience->company }}</span>
                                    @if($experience->location)
                                        , {{ $experience->location }}
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
                            </article>
                        @endforeach
                    </div>
                    @if(isset($totalExperiences) && $totalExperiences > 3)
                        <div class="cv-note" style="font-size: 8.5pt; color: var(--text-muted); font-style: italic; margin-top: 12px; border-top: 1px dashed var(--border); padding-top: 12px; text-align: center;">
                            * Note: Showing only the 3 latest experiences. View the remaining {{ $totalExperiences - 3 }} experiences on my website: <a href="{{ route('about') }}" style="color: var(--accent); text-decoration: none;">{{ str_replace(['http://', 'https://'], '', route('about')) }}</a>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Education --}}
            @if($educations->count() > 0)
                <section class="section">
                    <div class="section-header">
                        <div class="section-line"></div>
                        <h2 class="section-title">Education</h2>
                        <div class="section-line"></div>
                    </div>
                    <div class="education-list">
                        @foreach($educations as $education)
                            <article class="education-item">
                                <h3 class="degree">{{ $education->degree }}</h3>
                                <p class="institution">{{ $education->institution }} — {{ $education->field_of_study }}</p>
                                <p class="year">{{ $education->start_date->format('Y') }} — {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</p>
                                @if($education->grade || $education->activities)
                                    <p class="meta">
                                        @if($education->grade)GPA: {{ $education->grade }}@endif
                                        @if($education->grade && $education->activities) · @endif
                                        @if($education->activities){{ $education->activities }}@endif
                                    </p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                    @if(isset($totalEducations) && $totalEducations > 3)
                        <div class="cv-note" style="font-size: 8.5pt; color: var(--text-muted); font-style: italic; margin-top: 12px; border-top: 1px dashed var(--border); padding-top: 12px; text-align: center;">
                            * Note: Showing only the 3 latest education histories. View the remaining {{ $totalEducations - 3 }} education histories on my website: <a href="{{ route('about') }}" style="color: var(--accent); text-decoration: none;">{{ str_replace(['http://', 'https://'], '', route('about')) }}</a>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Skills --}}
            @if($skills->count() > 0)
                <section class="section">
                    <div class="section-header">
                        <div class="section-line"></div>
                        <h2 class="section-title">Expertise</h2>
                        <div class="section-line"></div>
                    </div>
                    <div class="skills-container">
                        @php
                            $groupedSkills = $skills->groupBy(fn($skill) => $skill->category_name ?? $skill->category?->name ?? (is_string($skill->category ?? null) ? $skill->category : 'General'));
                        @endphp
                        @foreach($groupedSkills as $categoryName => $categorySkills)
                            <div class="skill-category">
                                <h4 class="category-name">{{ $categoryName }}</h4>
                                <p class="skill-items">
                                    @foreach($categorySkills as $skill)
                                        <span class="skill-item">
                                            {{ $skill->name }}
                                            @if($skill->years_experience > 0)
                                                @php
                                                    $years = floatval($skill->years_experience);
                                                    $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                                @endphp
                                                <span class="skill-years">({{ $displayYears }}y)</span>
                                            @endif
                                        </span>
                                        @if(!$loop->last)<span class="skill-separator">·</span>@endif
                                    @endforeach
                                </p>
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
