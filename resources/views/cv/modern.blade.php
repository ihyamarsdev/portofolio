<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $profile->name ?? 'Candidate' }} | CV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #1e293b;
            background: #0f172a;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            display: flex;
            flex-direction: row;
            background: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ═══════════════════════════════════════════════
           SIDEBAR - 35% width
           ═══════════════════════════════════════════════ */
        .sidebar {
            width: 35%;
            padding: 28px 22px;
            background: #0f172a;
            color: #e2e8f0;
            position: relative;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #06b6d4;
        }

        /* Terminal Prompt */
        .prompt {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9pt;
            color: #06b6d4;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .prompt::before {
            content: '$ ';
            color: #67e8f9;
        }

        /* Identity Section */
        .identity {
            margin-bottom: 28px;
            page-break-inside: avoid;
        }

        .name {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 22pt;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 11pt;
            color: #06b6d4;
            font-weight: 400;
            margin-bottom: 20px;
        }

        /* Contact Block */
        .contact-block {
            margin-bottom: 28px;
            padding-top: 16px;
            border-top: 1px solid #334155;
            page-break-inside: avoid;
        }

        .contact-item {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9pt;
            color: #94a3b8;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .contact-item::before {
            content: '>';
            color: #06b6d4;
            font-weight: 600;
            flex-shrink: 0;
        }

        .contact-link {
            color: #67e8f9;
            text-decoration: none;
            word-break: break-all;
        }

        /* Skills in Sidebar */
        .skills-section {
            page-break-inside: avoid;
        }

        .skills-header {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9pt;
            color: #06b6d4;
            margin-bottom: 16px;
            font-weight: 600;
            padding-bottom: 8px;
            border-bottom: 1px solid #334155;
        }

        .skills-header::before {
            content: '$ ';
            color: #67e8f9;
            font-weight: 500;
        }

        .skill-category {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .category-name {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .skill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .skill-item {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 8.5pt;
            color: #e2e8f0;
            padding: 4px 8px;
            background: #1e293b;
            border-left: 2px solid #06b6d4;
            margin-bottom: 2px;
        }

        .skill-years {
            color: #06b6d4;
            font-weight: 500;
            margin-left: 4px;
        }

        /* ═══════════════════════════════════════════════
           MAIN CONTENT - 65% width
           ═══════════════════════════════════════════════ */
        .main-content {
            width: 65%;
            padding: 28px 28px 28px 32px;
            background: #f8fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Section Headers */
        .section {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }

        .section-header {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9pt;
            color: #0f172a;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px dashed #06b6d4;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .section-header::before {
            content: '// ';
            color: #06b6d4;
            font-weight: 500;
        }

        /* Summary */
        .summary {
            color: #334155;
            line-height: 1.7;
            text-align: justify;
        }

        /* Experience Items */
        .item {
            margin-bottom: 20px;
            padding-left: 14px;
            border-left: 3px solid #06b6d4;
            position: relative;
            page-break-inside: avoid;
        }

        .item::before {
            content: '■';
            position: absolute;
            left: -6px;
            top: 2px;
            color: #06b6d4;
            font-size: 8pt;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .position {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 11pt;
            font-weight: 600;
            color: #0f172a;
        }

        .date {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 8.5pt;
            color: #06b6d4;
            font-weight: 500;
            background: #e0f2fe;
            padding: 2px 8px;
        }

        .company-location {
            margin-bottom: 8px;
            font-size: 10pt;
        }

        .company {
            font-weight: 500;
            color: #334155;
        }

        .location {
            color: #64748b;
            font-style: italic;
        }

        .description {
            color: #475569;
            line-height: 1.6;
            margin-top: 8px;
        }

        /* Achievement Lists */
        ul {
            margin-top: 10px;
            padding-left: 18px;
        }

        li {
            color: #475569;
            margin-bottom: 4px;
            line-height: 1.5;
        }

        li::marker {
            color: #06b6d4;
            font-size: 8pt;
        }

        /* Education */
        .education-item {
            margin-bottom: 16px;
            padding-left: 14px;
            border-left: 3px solid #06b6d4;
            position: relative;
            page-break-inside: avoid;
        }

        .education-item::before {
            content: '■';
            position: absolute;
            left: -6px;
            top: 2px;
            color: #06b6d4;
            font-size: 8pt;
        }

        .institution {
            font-weight: 600;
            color: #0f172a;
            font-size: 10.5pt;
        }

        .degree {
            color: #334155;
            font-size: 10pt;
            margin-top: 2px;
        }

        .meta-info {
            margin-top: 6px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 8.5pt;
            color: #64748b;
        }

        .meta-label {
            color: #06b6d4;
            font-weight: 500;
        }

        /* Social Links */
        .social-links {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #334155;
        }

        .social-item {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 8.5pt;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .social-item a {
            color: #67e8f9;
            text-decoration: none;
        }

        /* Print Optimization */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .page {
                break-after: page;
            }

            .section, .item, .education-item, .skill-category {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- ═══════════════════════════════════════════════
             SIDEBAR
             ═══════════════════════════════════════════════ --}}
        <div class="sidebar">
            {{-- Identity --}}
            <div class="identity">
                <div class="prompt">whoami</div>
                <div class="name">{{ $profile->name ?? 'YOUR NAME' }}</div>
                <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            </div>

            {{-- Contact --}}
            <div class="contact-block">
                <div class="prompt">contact</div>
                @if($profile->email ?? false)
                    <div class="contact-item">
                        <a href="mailto:{{ $profile->email }}" class="contact-link">{{ $profile->email }}</a>
                    </div>
                @endif
                @if($profile->phone ?? false)
                    <div class="contact-item">{{ $profile->phone }}</div>
                @endif
                @if($profile->location ?? false)
                    <div class="contact-item">{{ $profile->location }}</div>
                @endif

                @if(isset($profile->social_links) && count($profile->social_links) > 0)
                    <div class="social-links">
                        @foreach($profile->social_links as $link)
                            @if(isset($link['platform']) && isset($link['url']))
                                <div class="social-item">
                                    {{ strtoupper($link['platform']) }}: <a href="{{ $link['url'] }}">{{ $link['url'] }}</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Skills --}}
            @if($skills->count() > 0)
                <div class="skills-section">
                    <div class="skills-header">skills --list</div>
                    @php
                        $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
                    @endphp
                    @foreach($groupedSkills as $categoryName => $categorySkills)
                        <div class="skill-category">
                            <div class="category-name">{{ $categoryName }}</div>
                            <div class="skill-list">
                                @foreach($categorySkills as $skill)
                                    <div class="skill-item">
                                        {{ $skill->name }}
                                        @if($skill->years_experience > 0)
                                            @php
                                                $years = floatval($skill->years_experience);
                                                $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                                $yearText = $years == 1 ? 'y' : 'y';
                                            @endphp
                                            <span class="skill-years">{{ $displayYears }}{{ $yearText }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════
             MAIN CONTENT
             ═══════════════════════════════════════════════ --}}
        <div class="main-content">
            {{-- Professional Summary --}}
            @if($profile->biography ?? false)
                <div class="section">
                    <div class="section-header">PROFESSIONAL SUMMARY</div>
                    <div class="summary">
                        {{ strip_tags($profile->biography) }}
                    </div>
                </div>
            @endif

            {{-- Experience --}}
            @if($experiences->count() > 0)
                <div class="section">
                    <div class="section-header">EXPERIENCE</div>
                    @foreach($experiences as $experience)
                        <div class="item">
                            <div class="item-header">
                                <span class="position">{{ $experience->position }}</span>
                                <span class="date">{{ $experience->start_date->format('Y.m') }} — {{ $experience->end_date ? $experience->end_date->format('Y.m') : 'Present' }}</span>
                            </div>
                            <div class="company-location">
                                <span class="company">{{ $experience->company }}</span>
                                @if($experience->location)
                                    <span class="location"> · {{ $experience->location }}</span>
                                @endif
                            </div>
                            @if($experience->description)
                                <div class="description">
                                    {!! nl2br(e(strip_tags($experience->description))) !!}
                                </div>
                            @endif
                            @if($experience->achievements && count($experience->achievements) > 0)
                                <ul>
                                    @foreach($experience->achievements as $achievement)
                                        <li>{{ $achievement }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Education --}}
            @if($educations->count() > 0)
                <div class="section">
                    <div class="section-header">EDUCATION</div>
                    @foreach($educations as $education)
                        <div class="education-item">
                            <div class="item-header">
                                <span class="institution">{{ $education->institution }}</span>
                                <span class="date">{{ $education->start_date->format('Y') }} — {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</span>
                            </div>
                            <div class="degree">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                            @if($education->grade || $education->activities)
                                <div class="meta-info">
                                    @if($education->grade)
                                        <span class="meta-label">GPA:</span> {{ $education->grade }}
                                    @endif
                                    @if($education->grade && $education->activities)
                                        <span> · </span>
                                    @endif
                                    @if($education->activities)
                                        <span class="meta-label">Activities:</span> {{ $education->activities }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
