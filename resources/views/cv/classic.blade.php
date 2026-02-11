<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Classic CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 25mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Garamond', 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #1a1a1a;
            background: #fff;
        }

        .container {
            max-width: 750px;
            margin: 0 auto;
        }

        /* Elegant Header */
        .header {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 2px double #2c3e50;
        }

        .name {
            font-size: 28pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 12px;
            color: #1a1a1a;
        }

        .title {
            font-size: 13pt;
            font-style: italic;
            margin-bottom: 15px;
            color: #555;
            font-weight: 400;
        }

        .contact-info {
            font-size: 10pt;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
            color: #444;
        }

        .contact-info span {
            margin: 0 12px;
        }

        .contact-divider {
            margin: 0 6px;
            color: #999;
        }

        /* Section Headings - Classic Style */
        h2 {
            font-size: 13pt;
            text-transform: uppercase;
            letter-spacing: 3px;
            border-bottom: 1.5px solid #2c3e50;
            padding-bottom: 8px;
            margin-top: 30px;
            margin-bottom: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .section {
            margin-bottom: 25px;
        }

        /* Experience & Education Items */
        .item {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .item-header {
            margin-bottom: 10px;
        }

        .title-line {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .position-title {
            font-weight: 600;
            font-size: 11.5pt;
            color: #1a1a1a;
            letter-spacing: 0.3px;
        }

        .organization {
            font-weight: 600;
            font-size: 11pt;
            font-style: italic;
            color: #2c3e50;
        }

        .location {
            font-style: italic;
            color: #666;
            font-size: 10pt;
        }

        .date {
            font-weight: 600;
            color: #555;
            font-size: 10pt;
            white-space: nowrap;
        }

        .company-line {
            margin-bottom: 8px;
            color: #444;
        }

        .description {
            text-align: justify;
            color: #333;
            line-height: 1.7;
            text-indent: 0;
            margin-top: 8px;
        }

        /* Lists - Classic Style */
        ul {
            margin-top: 10px;
            margin-bottom: 8px;
            padding-left: 30px;
        }

        li {
            margin-bottom: 6px;
            line-height: 1.6;
            color: #333;
        }

        li::marker {
            color: #2c3e50;
        }

        /* Skills - Elegant Grid */
        .skills-grid {
            margin-top: 12px;
        }

        .skill-category-row {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .skill-category-label {
            font-weight: 700;
            font-size: 10.5pt;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .skill-list-inline {
            line-height: 1.8;
            color: #444;
        }

        .skill-item-inline {
            display: inline;
        }

        .skill-separator {
            margin: 0 4px;
            color: #999;
        }

        .skill-with-experience {
            font-weight: 500;
        }

        .skill-years-inline {
            font-size: 9pt;
            color: #666;
            font-style: italic;
        }

        /* Summary - Classic Paragraph */
        .summary {
            text-align: justify;
            line-height: 1.7;
            color: #333;
            text-indent: 2em;
        }

        /* Decorative Elements */
        .section-ornament {
            text-align: center;
            color: #ddd;
            margin: 20px 0;
            font-size: 8pt;
        }

        /* Print Optimization */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Classic Header --}}
        <div class="header">
            <div class="name">{{ $profile->name ?? 'YOUR NAME' }}</div>
            <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            <div class="contact-info">
                <span>{{ $profile->email ?? 'email@example.com' }}</span>
                <span class="contact-divider">•</span>
                <span>{{ $profile->phone ?? 'Phone' }}</span>
                <span class="contact-divider">•</span>
                <span>{{ $profile->location ?? 'Location' }}</span>
            </div>
        </div>

        {{-- Professional Summary --}}
        @if($profile->biography)
            <div class="section">
                <h2>Professional Summary</h2>
                <div class="summary">
                    {{ strip_tags($profile->biography) }}
                </div>
            </div>
        @endif

        {{-- Professional Experience --}}
        <div class="section">
            <h2>Professional Experience</h2>
            @foreach($experiences as $experience)
                <div class="item">
                    <div class="item-header">
                        <div class="title-line">
                            <span class="position-title">{{ $experience->position }}</span>
                            <span class="date">
                                {{ $experience->start_date->format('M Y') }} –
                                {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                            </span>
                        </div>
                        <div class="company-line">
                            <span class="organization">{{ $experience->company }}</span>,
                            <span class="location">{{ $experience->location }}</span>
                        </div>
                    </div>
                    <div class="description">
                        @if($experience->description)
                            {!! nl2br(e(strip_tags($experience->description))) !!}
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

        {{-- Education --}}
        <div class="section">
            <h2>Education</h2>
            @foreach($educations as $education)
                <div class="item">
                    <div class="item-header">
                        <div class="title-line">
                            <span class="organization">{{ $education->institution }}</span>
                            <span class="date">
                                {{ $education->start_date->format('Y') }} –
                                {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                            </span>
                        </div>
                    </div>
                    <div class="position-title" style="font-size: 11pt; margin-bottom: 6px;">
                        {{ $education->degree }} in {{ $education->field_of_study }}
                    </div>
                    @if($education->grade)
                        <div style="color: #666; font-size: 10pt; margin-top: 4px;">
                            <em>GPA/Grade:</em> {{ $education->grade }}
                        </div>
                    @endif
                    @if($education->activities)
                        <div style="color: #666; font-size: 10pt; margin-top: 4px;">
                            <em>Activities:</em> {{ $education->activities }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Skills & Expertise --}}
        <div class="section">
            <h2>Skills & Expertise</h2>
            <div class="skills-grid">
                @php
                    $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
                @endphp
                @foreach($groupedSkills as $categoryName => $categorySkills)
                    <div class="skill-category-row">
                        <div class="skill-category-label">{{ $categoryName }}</div>
                        <div class="skill-list-inline">
                            @foreach($categorySkills as $skill)
                                <span class="skill-item-inline skill-with-experience">
                                    {{ $skill->name }}@if($skill->years_experience > 0)
                                        @php
                                            $years = floatval($skill->years_experience);
                                            $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                            $yearText = $years == 1 ? 'year' : 'years';
                                        @endphp
                                        <span class="skill-years-inline"> ({{ $displayYears }} {{ $yearText }})</span>
                                    @endif
                                </span>@if(!$loop->last)<span class="skill-separator">•</span>@endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>