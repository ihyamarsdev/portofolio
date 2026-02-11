<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Calibri', 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .name {
            font-size: 26pt;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 1px;
            color: #1a252f;
        }

        .professional-title {
            font-size: 13pt;
            font-weight: 600;
            color: #3498db;
            margin-bottom: 12px;
        }

        .contact-info {
            font-size: 10pt;
            margin-bottom: 6px;
            color: #555;
        }

        .contact-info span {
            margin: 0 10px;
        }

        .contact-info a {
            color: #3498db;
            text-decoration: none;
        }

        /* Section Headings */
        h2 {
            font-size: 14pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 6px;
            margin-top: 22px;
            margin-bottom: 14px;
            letter-spacing: 0.5px;
        }

        .section {
            margin-bottom: 20px;
        }

        /* Experience & Education Items */
        .item {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }

        .position-title {
            font-weight: 700;
            font-size: 11.5pt;
            color: #2c3e50;
        }

        .organization {
            font-weight: 600;
            font-size: 11pt;
            color: #34495e;
        }

        .location {
            font-style: italic;
            color: #7f8c8d;
            font-size: 10pt;
        }

        .date {
            font-weight: 600;
            color: #3498db;
            font-size: 10pt;
            white-space: nowrap;
        }

        .company-line {
            margin-bottom: 6px;
        }

        .description {
            margin-top: 8px;
            text-align: justify;
            color: #555;
            line-height: 1.6;
        }

        /* Lists */
        ul {
            margin-top: 8px;
            margin-bottom: 6px;
            padding-left: 22px;
        }

        li {
            margin-bottom: 4px;
            line-height: 1.5;
            color: #555;
        }

        /* Skills Section */
        .skills-grid {
            margin-top: 10px;
        }

        .skill-category-block {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .skill-category-title {
            font-weight: 700;
            font-size: 11pt;
            color: #2c3e50;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .skill-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-item {
            display: inline-block;
            padding: 4px 10px;
            background: #ecf0f1;
            border-left: 3px solid #3498db;
            font-size: 10pt;
            color: #2c3e50;
        }

        .skill-item-with-years {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .skill-years {
            font-size: 9pt;
            color: #7f8c8d;
            font-weight: 600;
        }

        /* Professional Summary */
        .summary {
            text-align: justify;
            line-height: 1.6;
            color: #555;
        }

        /* Certification & Achievement Badges */
        .achievement-marker {
            display: inline-block;
            width: 6px;
            height: 6px;
            background: #3498db;
            border-radius: 50%;
            margin-right: 6px;
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
        {{-- Header Section --}}
        <div class="header">
            <div class="name">{{ $profile->name ?? 'YOUR NAME' }}</div>
            <div class="professional-title">{{ $profile->title ?? 'Professional Title' }}</div>
            <div class="contact-info">
                <span>{{ $profile->email ?? 'email@example.com' }}</span> |
                <span>{{ $profile->phone ?? 'Phone' }}</span> |
                <span>{{ $profile->location ?? 'Location' }}</span>
            </div>
            @if(isset($profile->social_links) && count($profile->social_links) > 0)
                <div class="contact-info">
                    @foreach($profile->social_links as $platform => $url)
                        <span><a href="{{ $url }}">{{ ucfirst($platform) }}</a></span>
                        @if(!$loop->last) | @endif
                    @endforeach
                </div>
            @endif
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
                        <span class="position-title">{{ $experience->position }}</span>
                        <span class="date">
                            {{ $experience->start_date->format('M Y') }} -
                            {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    <div class="company-line">
                        <span class="organization">{{ $experience->company }}</span>,
                        <span class="location">{{ $experience->location }}</span>
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
                        <span class="organization">{{ $education->institution }}</span>
                        <span class="date">
                            {{ $education->start_date->format('Y') }} -
                            {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                        </span>
                    </div>
                    <div class="position-title" style="font-size: 11pt;">
                        {{ $education->degree }} in {{ $education->field_of_study }}
                    </div>
                    @if($education->grade)
                        <div style="margin-top: 4px; color: #7f8c8d; font-size: 10pt;">
                            GPA/Grade: {{ $education->grade }}
                        </div>
                    @endif
                    @if($education->activities)
                        <div style="margin-top: 4px; color: #555; font-size: 10pt;">
                            Activities: {{ $education->activities }}
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
                    <div class="skill-category-block">
                        <div class="skill-category-title">{{ $categoryName }}</div>
                        <div class="skill-items">
                            @foreach($categorySkills as $skill)
                                <div class="skill-item">
                                    <div class="skill-item-with-years">
                                        <span>{{ $skill->name }}</span>
                                        @if($skill->years_experience > 0)
                                            @php
                                                $years = floatval($skill->years_experience);
                                                $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                                $yearText = $years == 1 ? 'yr' : 'yrs';
                                            @endphp
                                            <span class="skill-years">({{ $displayYears }} {{ $yearText }})</span>
                                        @endif
                                    </div>
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