<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Classic CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        @page {
            size: A4;
            margin: 22mm 30mm;
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
            border-bottom: 2px double #088395;
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
            color: #088395;
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

        .contact-link {
            color: #088395;
            text-decoration: none;
            font-weight: 500;
        }

        /* Section Headings - Classic Style */
        h2 {
            font-size: 13pt;
            text-transform: uppercase;
            letter-spacing: 3px;
            border-bottom: 1.5px solid #088395;
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

        .item-header > div {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .position {
            font-weight: 600;
            font-size: 11.5pt;
            color: #1a1a1a;
            letter-spacing: 0.3px;
        }

        .company {
            font-weight: 600;
            font-size: 11pt;
            font-style: italic;
            color: #088395;
        }

        .company-location {
            margin-bottom: 8px;
            color: #444;
        }

        .location {
            font-style: italic;
            color: #666;
            font-size: 10pt;
        }

        .date {
            font-weight: 600;
            color: #088395;
            font-size: 10pt;
            white-space: nowrap;
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
            color: #088395;
        }

        /* Skills - Elegant Inline Style */
        .skills-container {
            margin-top: 12px;
        }

        .skill-category {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .category-title {
            font-weight: 700;
            font-size: 10.5pt;
            color: #088395;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .skill-items {
            line-height: 1.8;
            color: #444;
        }

        .skill-item {
            display: inline;
        }

        .skill-name {
            font-weight: 500;
        }

        .skill-years {
            font-size: 9pt;
            color: #088395;
            font-style: italic;
        }

        .skill-separator {
            margin: 0 6px;
            color: #999;
        }

        /* Summary - Classic Paragraph */
        .summary {
            text-align: justify;
            line-height: 1.7;
            color: #333;
            text-indent: 2em;
        }

        /* Meta Info */
        .meta-info {
            margin-top: 4px;
            color: #666;
            font-size: 10pt;
        }

        .meta-label {
            font-weight: 600;
            color: #088395;
            font-style: italic;
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
        @include('cv.partials._header', [
            'profile' => $profile,
            'header_class' => 'header',
            'name_class' => 'name',
            'title_class' => 'title',
            'contact_class' => 'contact-info',
            'link_class' => 'contact-link'
        ])

        {{-- Professional Summary --}}
        @include('cv.partials._summary', [
            'biography' => $profile->biography,
            'section_class' => 'section',
            'heading_class' => '',
            'content_class' => 'summary'
        ])

        {{-- Professional Experience --}}
        @include('cv.partials._experience', [
            'experiences' => $experiences,
            'section_class' => 'section',
            'heading_class' => '',
            'item_class' => 'item',
            'position_class' => 'position',
            'company_class' => 'company',
            'location_class' => 'location',
            'date_class' => 'date',
            'description_class' => 'description',
            'list_class' => ''
        ])

        {{-- Education --}}
        @include('cv.partials._education', [
            'educations' => $educations,
            'section_class' => 'section',
            'heading_class' => '',
            'item_class' => 'item',
            'institution_class' => 'company',
            'degree_class' => 'position',
            'date_class' => 'date',
            'meta_class' => 'meta-info'
        ])

        {{-- Skills --}}
        <div class="section">
            <h2>Skills & Expertise</h2>
            <div class="skills-container">
                @php
                    $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
                @endphp
                @foreach($groupedSkills as $categoryName => $categorySkills)
                    <div class="skill-category">
                        <span class="category-title">{{ $categoryName }}</span>
                        <div class="skill-items">
                            @foreach($categorySkills as $skill)
                                <span class="skill-item">
                                    <span class="skill-name">{{ $skill->name }}</span>
                                    @if($skill->years_experience > 0)
                                        @php
                                            $years = floatval($skill->years_experience);
                                            $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                            $yearText = $years == 1 ? 'yr' : 'yrs';
                                        @endphp
                                        <span class="skill-years"> ({{ $displayYears }} {{ $yearText }})</span>
                                    @endif
                                </span>
                                @if(!$loop->last)<span class="skill-separator">•</span>@endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
