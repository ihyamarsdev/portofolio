<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modern CV - {{ $profile->name ?? 'Candidate' }}</title>
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
            font-family: 'Segoe UI', 'Inter', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        /* Modern Gradient Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 45px;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .name {
            font-size: 28pt;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title {
            font-size: 14pt;
            margin-bottom: 20px;
            font-weight: 400;
            opacity: 0.95;
        }

        .contact-info {
            font-size: 10pt;
            text-align: center;
            margin-top: 15px;
        }

        .contact-info span {
            display: inline-block;
            margin: 0 12px;
            opacity: 0.9;
        }

        .contact-link {
            color: white;
            text-decoration: none;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.5);
        }

        /* Content Area */
        .content {
            padding: 40px 45px;
        }

        /* Section Headings - Modern Gradient Style */
        h2 {
            font-size: 13pt;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 3px solid transparent;
            border-image: linear-gradient(90deg, #667eea 0%, #764ba2 100%) 1;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .section {
            margin-bottom: 25px;
        }

        /* Experience & Education Items */
        .item {
            margin-bottom: 24px;
            padding-left: 20px;
            border-left: 3px solid #667eea;
            page-break-inside: avoid;
            position: relative;
        }

        .item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 8px;
            width: 11px;
            height: 11px;
            background: white;
            border: 3px solid #667eea;
            border-radius: 50%;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .position {
            font-weight: 700;
            font-size: 11.5pt;
            color: #2c3e50;
        }

        .company {
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 11pt;
        }

        .location {
            font-style: italic;
            color: #7f8c8d;
            font-size: 10pt;
        }

        .date {
            color: #7f8c8d;
            font-weight: 600;
            font-size: 10pt;
            white-space: nowrap;
        }

        .company-location {
            margin-bottom: 8px;
        }

        .description {
            margin-top: 8px;
            color: #556;
            text-align: justify;
            line-height: 1.7;
        }

        /* Lists */
        ul {
            margin-top: 10px;
            margin-bottom: 8px;
            padding-left: 22px;
        }

        li {
            margin-bottom: 5px;
            color: #556;
            line-height: 1.6;
        }

        li::marker {
            color: #667eea;
        }

        /* Skills - Modern Card Grid */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .skill-category {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .skill-category-title {
            display: block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            font-size: 10.5pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
        }

        .skill-items {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .skill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #2c3e50;
            font-size: 10pt;
            line-height: 1.5;
        }

        .skill-name {
            flex: 1;
        }

        .skill-years {
            padding: 2px 8px;
            background: white;
            border-radius: 4px;
            font-size: 8.5pt;
            color: #667eea;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Professional Summary */
        .summary {
            text-align: justify;
            color: #556;
            line-height: 1.7;
        }

        /* Additional Meta Info */
        .meta-info {
            margin-top: 8px;
            color: #7f8c8d;
            font-size: 10pt;
        }

        .meta-label {
            font-weight: 600;
            color: #667eea;
        }

        /* Print Optimization */
        @media print {
            body {
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
            }

            .skills-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Modern Gradient Header --}}
        <div class="header">
            <div class="name">{{ $profile->name ?? 'Your Name' }}</div>
            <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            <div class="contact-info">
                <span>{{ $profile->email ?? 'email@example.com' }}</span>
                <span>{{ $profile->phone ?? 'Phone' }}</span>
                <span>{{ $profile->location ?? 'Location' }}</span>
            </div>
            @if(isset($profile->social_links) && count($profile->social_links) > 0)
                <div class="contact-info" style="margin-top: 8px;">
                    @foreach($profile->social_links as $platform => $url)
                        <span>
                            <a href="{{ $url }}" class="contact-link">{{ ucfirst($platform) }}</a>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="content">
            {{-- Professional Summary --}}
            @if($profile->biography)
                <div class="section">
                    <h2>Professional Summary</h2>
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
                        <div class="item-header">
                            <span class="position">{{ $experience->position }}</span>
                            <span class="date">
                                {{ $experience->start_date->format('M Y') }} –
                                {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                            </span>
                        </div>
                        <div class="company-location">
                            <span class="company">{{ $experience->company }}</span>,
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
                        <div class="item-header">
                            <span class="company">{{ $education->institution }}</span>
                            <span class="date">
                                {{ $education->start_date->format('Y') }} –
                                {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                            </span>
                        </div>
                        <div class="position">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                        @if($education->grade || $education->activities)
                            <div class="meta-info">
                                @if($education->grade)
                                    <span class="meta-label">GPA/Grade:</span> {{ $education->grade }}
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
                <div class="skills-grid">
                    @php
                        $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
                    @endphp
                    @foreach($groupedSkills as $categoryName => $categorySkills)
                        <div class="skill-category">
                            <strong class="skill-category-title">{{ $categoryName }}</strong>
                            <div class="skill-items">
                                @foreach($categorySkills as $skill)
                                    <div class="skill-item">
                                        <span class="skill-name">{{ $skill->name }}</span>
                                        @if($skill->years_experience > 0)
                                            @php
                                                $years = floatval($skill->years_experience);
                                                $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                                $yearText = $years == 1 ? 'yr' : 'yrs';
                                            @endphp
                                            <span class="skill-years">{{ $displayYears }} {{ $yearText }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>

</html>