<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CV - {{ $profile->name ?? 'Candidate' }}</title>
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
            font-family: 'Calibri', 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #2c3e50;
            margin: 0;
            padding: 10;
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

        .title {
            font-size: 13pt;
            font-weight: 600;
            color: #088395;
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

        .contact-link {
            color: #088395;
            text-decoration: none;
            font-weight: 500;
        }

        /* Section Headings */
        h2 {
            font-size: 14pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #2c3e50;
            border-bottom: 2px solid #088395;
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

        .position {
            font-weight: 700;
            font-size: 11.5pt;
            color: #2c3e50;
        }

        .company {
            font-weight: 600;
            font-size: 11pt;
            color: #34495e;
        }

        .company-location {
            margin-bottom: 6px;
        }

        .location {
            font-style: italic;
            color: #7f8c8d;
            font-size: 10pt;
        }

        .date {
            font-weight: 600;
            color: #088395;
            font-size: 10pt;
            white-space: nowrap;
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

        li::marker {
            color: #088395;
        }

        /* Skills Section */
        .skills-container {
            margin-top: 10px;
        }

        .skill-category {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .category-title {
            display: block;
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
            border-left: 3px solid #088395;
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

        /* Summary */
        .summary {
            text-align: justify;
            line-height: 1.6;
            color: #555;
        }

        /* Meta Info */
        .meta-info {
            margin-top: 4px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
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
