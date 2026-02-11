<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Minimal CV - {{ $profile->name ?? 'Candidate' }}</title>
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
            font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.7;
            color: #2c3e50;
            background: #fff;
        }

        .container {
            max-width: 750px;
            margin: 0 auto;
        }

        /* Minimal Header */
        .header {
            margin-bottom: 45px;
            padding-bottom: 25px;
            border-bottom: 1px solid #e0e0e0;
        }

        .name {
            font-size: 22pt;
            font-weight: 300;
            margin-bottom: 6px;
            color: #1a1a1a;
            letter-spacing: -0.5px;
        }

        .title {
            font-size: 11.5pt;
            color: #7f8c8d;
            margin-bottom: 16px;
            font-weight: 400;
        }

        .contact-info {
            font-size: 9.5pt;
            color: #95a5a6;
            margin-bottom: 5px;
        }

        .contact-info span {
            margin-right: 20px;
        }

        .contact-link {
            color: #3498db;
            text-decoration: none;
        }

        /* Section Headings - Minimal Style */
        h2 {
            font-size: 11pt;
            font-weight: 500;
            color: #2c3e50;
            margin-top: 40px;
            margin-bottom: 22px;
            letter-spacing: 0.5px;
            position: relative;
            padding-bottom: 8px;
        }

        h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 2px;
            background: #3498db;
        }

        .section {
            margin-bottom: 30px;
        }

        /* Experience & Education Items */
        .item {
            margin-bottom: 28px;
            page-break-inside: avoid;
        }

        .item-meta {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
        }

        .position {
            font-weight: 500;
            font-size: 11pt;
            color: #2c3e50;
        }

        .organization {
            font-weight: 400;
            color: #34495e;
            font-size: 10.5pt;
        }

        .location {
            color: #95a5a6;
            font-size: 9.5pt;
        }

        .date {
            color: #95a5a6;
            font-weight: 400;
            font-size: 9.5pt;
            white-space: nowrap;
        }

        .company-info {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .description {
            color: #576574;
            line-height: 1.8;
            margin-top: 10px;
        }

        /* Lists - Minimal Style */
        ul {
            margin-top: 12px;
            margin-bottom: 8px;
            padding-left: 22px;
        }

        li {
            margin-bottom: 6px;
            color: #576574;
            line-height: 1.8;
        }

        li::marker {
            color: #3498db;
        }

        /* Skills - Clean Grid */
        .skills-container {
            margin-top: 15px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .skill-group {
            page-break-inside: avoid;
        }

        .skill-category {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 10px;
            font-size: 10.5pt;
        }

        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 9.5pt;
            color: #2c3e50;
            position: relative;
        }

        .skill-tag::before {
            content: '';
            width: 3px;
            height: 3px;
            background: #3498db;
            border-radius: 50%;
            margin-right: 8px;
        }

        .skill-experience {
            margin-left: 6px;
            font-size: 8.5pt;
            color: #95a5a6;
            font-weight: 500;
        }

        /* Summary - Clean Paragraph */
        .summary {
            color: #576574;
            line-height: 1.8;
        }

        /* Additional Details */
        .meta-info {
            margin-top: 8px;
            color: #95a5a6;
            font-size: 9.5pt;
        }

        .meta-label {
            font-weight: 500;
            color: #7f8c8d;
        }

        /* Print Optimization */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .skills-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Minimal Header --}}
        <div class="header">
            <div class="name">{{ $profile->name ?? 'Your Name' }}</div>
            <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            <div class="contact-info">
                <span>{{ $profile->email ?? 'email@example.com' }}</span>
                <span>{{ $profile->phone ?? 'Phone' }}</span>
                <span>{{ $profile->location ?? 'Location' }}</span>
            </div>
            @if(isset($profile->social_links) && count($profile->social_links) > 0)
                <div class="contact-info" style="margin-top: 5px;">
                    @foreach($profile->social_links as $platform => $url)
                        <span><a href="{{ $url }}" class="contact-link">{{ ucfirst($platform) }}</a></span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Summary --}}
        @if($profile->biography)
            <div class="section">
                <h2>Summary</h2>
                <div class="summary">
                    {{ strip_tags($profile->biography) }}
                </div>
            </div>
        @endif

        {{-- Experience --}}
        <div class="section">
            <h2>Experience</h2>
            @foreach($experiences as $experience)
                <div class="item">
                    <div class="item-meta">
                        <span class="position">{{ $experience->position }}</span>
                        <span class="date">
                            {{ $experience->start_date->format('M Y') }} –
                            {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    <div class="company-info">
                        <span class="organization">{{ $experience->company }}</span>
                        <span class="location">{{ $experience->location }}</span>
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

        {{-- Education --}}
        <div class="section">
            <h2>Education</h2>
            @foreach($educations as $education)
                <div class="item">
                    <div class="item-meta">
                        <span class="organization">{{ $education->institution }}</span>
                        <span class="date">
                            {{ $education->start_date->format('Y') }} –
                            {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                        </span>
                    </div>
                    <div class="position">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                    @if($education->grade || $education->activities)
                        <div class="meta-info" style="margin-top: 8px;">
                            @if($education->grade)
                                <span class="meta-label">GPA:</span> {{ $education->grade }}
                            @endif
                            @if($education->grade && $education->activities)
                                <span style="margin: 0 8px;">•</span>
                            @endif
                            @if($education->activities)
                                <span class="meta-label">Activities:</span> {{ $education->activities }}
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Skills --}}
        <div class="section">
            <h2>Skills</h2>
            <div class="skills-container">
                @php
                    $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
                @endphp
                @foreach($groupedSkills as $categoryName => $categorySkills)
                    <div class="skill-group">
                        <div class="skill-category">{{ $categoryName }}</div>
                        <div class="skill-tags">
                            @foreach($categorySkills as $skill)
                                <div class="skill-tag">
                                    {{ $skill->name }}
                                    @if($skill->years_experience > 0)
                                        @php
                                            $years = floatval($skill->years_experience);
                                            $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                            $yearText = $years == 1 ? 'yr' : 'yrs';
                                        @endphp
                                        <span class="skill-experience">{{ $displayYears }}{{ $yearText }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>