<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Minimal CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        @page {
            size: A4;
            margin: 22mm 28mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
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
            color: #088395;
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
            color: #088395;
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
            background: #088395;
        }

        .section {
            margin-bottom: 30px;
        }

        /* Experience & Education Items */
        .item {
            margin-bottom: 28px;
            page-break-inside: avoid;
        }

        .item-header {
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

        .company {
            font-weight: 400;
            color: #34495e;
            font-size: 10.5pt;
        }

        .company-location {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
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
            color: #088395;
        }

        /* Skills - Clean Grid */
        .skills-container {
            margin-top: 15px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .skill-category {
            page-break-inside: avoid;
        }

        .category-title {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 10px;
            font-size: 10.5pt;
        }

        .skill-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-item {
            display: flex;
            align-items: center;
            padding: 5px 12px;
            background: #f8f9fa;
            border-radius: 4px;
            font-size: 9.5pt;
            color: #2c3e50;
            position: relative;
        }

        .skill-item::before {
            content: '';
            width: 3px;
            height: 3px;
            background: #088395;
            border-radius: 50%;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .skill-years {
            margin-left: 6px;
            font-size: 8.5pt;
            color: #088395;
            font-weight: 500;
        }

        /* Summary - Clean Paragraph */
        .summary {
            color: #576574;
            line-height: 1.8;
        }

        /* Meta Info */
        .meta-info {
            margin-top: 8px;
            color: #95a5a6;
            font-size: 9.5pt;
        }

        .meta-label {
            font-weight: 500;
            color: #088395;
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
        @include('cv.partials._header', [
            'profile' => $profile,
            'header_class' => 'header',
            'name_class' => 'name',
            'title_class' => 'title',
            'contact_class' => 'contact-info',
            'link_class' => 'contact-link'
        ])

        {{-- Summary --}}
        @include('cv.partials._summary', [
            'biography' => $profile->biography,
            'section_class' => 'section',
            'heading_class' => '',
            'content_class' => 'summary'
        ])

        {{-- Experience --}}
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
</body>
</html>
