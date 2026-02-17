<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modern CV - {{ $profile->name ?? 'Candidate' }}</title>
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
            font-family: 'Inter', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.6;
            color: #2c3e50;
            background: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Modern Header - Solid color fallback for DomPDF */
        .header {
            background: #088395;
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
            color: #7AB2B2;
        }

        .contact-info {
            font-size: 10pt;
            text-align: center;
            margin-top: 15px;
        }

        .contact-info span {
            display: inline-block;
            margin: 0 12px;
            color: #d0f6ff;
        }

        .contact-link {
            color: #d0f6ff;
            text-decoration: none;
            border-bottom: 1px dotted #d0f6ff;
        }

        /* Content Area */
        .content {
            padding: 40px 45px;
        }

        /* Section Headings - DomPDF Compatible */
        h2 {
            font-size: 13pt;
            color: #088395;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 3px solid #088395;
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
            border-left: 3px solid #088395;
            page-break-inside: avoid;
            position: relative;
        }

        .item-dot {
            position: absolute;
            left: -26px;
            top: 8px;
            width: 11px;
            height: 11px;
            background: white;
            border: 3px solid #088395;
            /* DomPDF doesn't support border-radius well, using square with border */
        }

        .item-header {
            margin-bottom: 8px;
        }

        .item-header .position {
            display: inline-block;
            margin-right: 10px;
        }

        .item-header .date {
            display: inline-block;
        }

        .position {
            font-weight: 700;
            font-size: 11.5pt;
            color: #2c3e50;
        }

        .company {
            font-weight: 600;
            color: #088395;
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
            color: #088395;
        }

        /* Skills - Card Style for DomPDF */
        .skills-container {
            margin-top: 16px;
        }

        .skill-category {
            background: #f0f8f9;
            padding: 16px;
            border-left: 4px solid #088395;
            page-break-inside: avoid;
            margin-bottom: 16px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .category-title {
            display: block;
            color: #088395;
            margin-bottom: 10px;
            font-size: 10.5pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
        }

        .skill-items {
            display: block;
        }

        .skill-item {
            display: block;
            color: #2c3e50;
            font-size: 10pt;
            line-height: 1.8;
            padding-left: 10px;
        }

        .skill-name {
            font-weight: 500;
        }

        .skill-years {
            padding: 2px 8px;
            background: white;
            color: #088395;
            font-weight: 600;
            white-space: nowrap;
            margin-left: 8px;
        }

        /* Summary */
        .summary {
            text-align: justify;
            color: #556;
            line-height: 1.7;
        }

        /* Meta Info */
        .meta-info {
            margin-top: 8px;
            color: #7f8c8d;
            font-size: 10pt;
        }

        .meta-label {
            font-weight: 600;
            color: #088395;
        }

        /* Print Optimization */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .skill-category {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Modern Header --}}
        @include('cv.partials._header', [
            'profile' => $profile,
            'header_class' => 'header',
            'name_class' => 'name',
            'title_class' => 'title',
            'contact_class' => 'contact-info',
            'link_class' => 'contact-link'
        ])

        <div class="content">
            {{-- Professional Summary --}}
            @include('cv.partials._summary', [
                'biography' => $profile->biography,
                'section_class' => 'section',
                'heading_class' => '',
                'content_class' => 'summary'
            ])

            {{-- Experience --}}
            <div class="section">
                <h2>Professional Experience</h2>
                @foreach($experiences as $experience)
                    <div class="item">
                        <div class="item-dot"></div>
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
                        <div class="item-dot"></div>
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
            @include('cv.partials._skills', [
                'skills' => $skills,
                'section_class' => 'section',
                'heading_class' => '',
                'container_class' => 'skills-container',
                'category_class' => 'skill-category',
                'category_title_class' => 'category-title',
                'skill_item_class' => 'skill-item'
            ])
        </div>
    </div>
</body>
</html>
