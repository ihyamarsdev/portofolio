<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }} | CV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Epilogue:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --accent: #3b82f6;
            --accent-light: #dbeafe;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --bg: #ffffff;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: var(--text-primary);
            background: var(--bg);
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            display: grid;
            grid-template-rows: auto 1fr;
            background: var(--bg);
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
           HEADER - Swiss Grid Style
           ═══════════════════════════════════════════════ */
        .header {
            background: var(--text-primary);
            color: white;
            padding: 32px 40px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(59, 130, 246, 0.3) 100%);
        }

        .header-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 24px;
            align-items: end;
        }

        .name-block {
            position: relative;
            z-index: 1;
        }

        .name {
            font-family: 'Epilogue', sans-serif;
            font-size: 32pt;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .title {
            font-family: 'Epilogue', sans-serif;
            font-size: 12pt;
            font-weight: 500;
            color: var(--accent);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .contact-block {
            display: flex;
            flex-direction: column;
            gap: 4px;
            text-align: right;
            position: relative;
            z-index: 1;
        }

        .contact-item {
            font-size: 9pt;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .contact-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        /* ═══════════════════════════════════════════════
           MAIN CONTENT
           ═══════════════════════════════════════════════ */
        .content {
            padding: 32px 40px;
            display: block;
        }

        /* Section */
        .section {
            margin-bottom: 28px;
        }
        .section:last-child {
            margin-bottom: 0;
        }

        .section-header {
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border);
        }

        .section-number {
            display: inline-block;
            vertical-align: middle;
            font-family: 'Epilogue', sans-serif;
            font-size: 8pt;
            font-weight: 700;
            color: var(--accent);
            background: var(--accent-light);
            padding: 4px 8px;
            letter-spacing: 1px;
            margin-right: 12px;
        }

        .section-title {
            display: inline-block;
            vertical-align: middle;
            font-family: 'Epilogue', sans-serif;
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-primary);
        }

        /* Summary */
        .summary {
            color: var(--text-secondary);
            line-height: 1.7;
            text-align: justify;
        }

        /* Experience Grid */
        .experience-grid {
            display: block;
        }

        .experience-item {
            display: block;
            padding-bottom: 16px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border);
            page-break-inside: avoid;
        }

        .experience-item:after {
            content: "";
            display: table;
            clear: both;
        }

        .experience-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .date-column {
            float: left;
            width: 120px;
            font-family: 'Epilogue', sans-serif;
            font-size: 9pt;
            font-weight: 600;
            color: var(--accent);
            padding-top: 2px;
        }

        .date-range {
            display: block;
        }

        .duration {
            font-size: 8pt;
            color: var(--text-muted);
            font-weight: 400;
            margin-top: 2px;
        }

        .content-column {
            margin-left: 136px;
        }

        .position {
            font-family: 'Epilogue', sans-serif;
            font-size: 11pt;
            font-weight: 700;
            color: var(--text-primary);
        }

        .company-location {
            font-size: 10pt;
            color: var(--text-secondary);
        }

        .company {
            font-weight: 600;
        }

        .location {
            color: var(--text-muted);
        }

        .description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-top: 4px;
        }

        ul {
            margin-top: 8px;
            padding-left: 16px;
        }

        li {
            color: var(--text-secondary);
            margin-bottom: 3px;
            line-height: 1.5;
        }

        li::marker {
            color: var(--accent);
        }

        /* Education Grid */
        .education-grid {
            display: block;
        }

        .education-item {
            display: block;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--border);
            page-break-inside: avoid;
        }

        .education-item:after {
            content: "";
            display: table;
            clear: both;
        }

        .education-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .education-item > div:last-child {
            margin-left: 76px;
        }

        .year-column {
            font-family: 'Epilogue', sans-serif;
            font-size: 10pt;
            font-weight: 700;
            color: var(--accent);
        }

        .degree {
            font-family: 'Epilogue', sans-serif;
            font-size: 11pt;
            font-weight: 700;
            color: var(--text-primary);
        }

        .institution {
            font-size: 10pt;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .meta {
            font-size: 9pt;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Skills Grid */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .skill-category {
            page-break-inside: avoid;
        }

        .category-name {
            font-family: 'Epilogue', sans-serif;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--border);
        }

        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .skill-tag {
            font-size: 9pt;
            padding: 4px 10px;
            background: var(--accent-light);
            color: var(--accent);
            font-weight: 500;
        }

        .skill-years {
            font-size: 8pt;
            opacity: 0.7;
            margin-left: 2px;
        }

        /* Social Links */
        .social-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .social-link {
            font-size: 8pt;
            color: var(--text-muted);
            text-decoration: none;
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
            <div class="header-grid">
                <div class="name-block">
                    <h1 class="name">{{ $profile->name ?? 'YOUR NAME' }}</h1>
                    <p class="title">{{ $profile->title ?? 'Professional Title' }}</p>
                </div>
                <div class="contact-block">
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
                                    <span class="social-link">{{ strtoupper($link['platform']) }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="content">
            {{-- Summary --}}
            @if($profile->biography ?? false)
                <section class="section">
                    <div class="section-header">
                        <span class="section-number">01</span>
                        <h2 class="section-title">Profile</h2>
                    </div>
                    <p class="summary">{{ strip_tags($profile->biography) }}</p>
                </section>
            @endif

            {{-- Experience --}}
            @if($experiences->count() > 0)
                <section class="section">
                    <div class="section-header">
                        <span class="section-number">02</span>
                        <h2 class="section-title">Experience</h2>
                    </div>
                    <div class="experience-grid">
                        @foreach($experiences as $experience)
                            <div class="experience-item">
                                <div class="date-column">
                                    <span class="date-range">
                                        {{ $experience->start_date->format('M Y') }} —
                                        {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Now' }}
                                    </span>
                                </div>
                                <div class="content-column">
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
                            </div>
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
                    <div class="section-header">
                        <span class="section-number">03</span>
                        <h2 class="section-title">Education</h2>
                    </div>
                    <div class="education-grid">
                        @foreach($educations as $education)
                            <div class="education-item">
                                <div class="year-column">
                                    {{ $education->start_date->format('Y') }} —
                                    {{ $education->end_date ? $education->end_date->format('Y') : 'Now' }}
                                </div>
                                <div>
                                    <h3 class="degree">{{ $education->degree }}</h3>
                                    <p class="institution">{{ $education->institution }} · {{ $education->field_of_study }}</p>
                                    @if($education->grade || $education->activities)
                                        <p class="meta">
                                            @if($education->grade)GPA: {{ $education->grade }}@endif
                                            @if($education->grade && $education->activities) · @endif
                                            @if($education->activities){{ $education->activities }}@endif
                                        </p>
                                    @endif
                                </div>
                            </div>
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
                    <div class="section-header">
                        <span class="section-number">04</span>
                        <h2 class="section-title">Skills</h2>
                    </div>
                    <div class="skills-grid">
                        @php
                            $groupedSkills = $skills->groupBy(fn($skill) => $skill->category_name ?? $skill->category?->name ?? (is_string($skill->category ?? null) ? $skill->category : 'General'));
                        @endphp
                        @foreach($groupedSkills as $categoryName => $categorySkills)
                            <div class="skill-category">
                                <h4 class="category-name">{{ $categoryName }}</h4>
                                <div class="skill-tags">
                                    @foreach($categorySkills as $skill)
                                        <span class="skill-tag">
                                            {{ $skill->name }}
                                            @if($skill->years_experience > 0)
                                                @php
                                                    $years = floatval($skill->years_experience);
                                                    $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                                @endphp
                                                <span class="skill-years">{{ $displayYears }}y</span>
                                            @endif
                                        </span>
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
