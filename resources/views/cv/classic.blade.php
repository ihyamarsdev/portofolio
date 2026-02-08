<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Classic CV - {{ $profile->name ?? 'Candidate' }}</title>
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 40px 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }

        .name {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 12pt;
            font-style: italic;
            margin-bottom: 12px;
        }

        .contact-info {
            font-size: 10pt;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .contact-info span {
            margin: 0 8px;
        }

        h2 {
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 20px;
        }

        .item {
            margin-bottom: 18px;
        }

        .item-header {
            margin-bottom: 8px;
        }

        .title-text {
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .organization {
            font-weight: bold;
            font-size: 11pt;
            font-style: italic;
        }

        .location {
            font-style: italic;
        }

        .date {
            text-align: right;
            font-weight: bold;
            float: right;
            margin-left: 20px;
        }

        .description {
            margin-top: 8px;
            text-align: justify;
            text-indent: 20px;
        }

        ul {
            margin-top: 8px;
            margin-bottom: 8px;
            padding-left: 30px;
            text-align: justify;
        }

        li {
            margin-bottom: 4px;
            text-indent: -20px;
            padding-left: 20px;
        }

        .skills-list {
            margin-top: 10px;
            text-align: justify;
        }

        .skill-category {
            font-weight: bold;
            margin-right: 5px;
        }

        .company-line {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">{{ $profile->name ?? 'YOUR NAME' }}</div>
        <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
        <div class="contact-info">
            <span>{{ $profile->email ?? 'email@example.com' }}</span> |
            <span>{{ $profile->phone ?? 'Phone' }}</span> |
            <span>{{ $profile->location ?? 'Location' }}</span>
        </div>
    </div>

    @if($profile->biography)
        <div class="section">
            <h2>Professional Summary</h2>
            <div class="description">
                {{ strip_tags($profile->biography) }}
            </div>
        </div>
    @endif

    <div class="section">
        <h2>Professional Experience</h2>
        @foreach($experiences as $experience)
            <div class="item">
                <div class="item-header">
                    <div>
                        <span class="title-text">{{ $experience->position }}</span>
                        <span class="date">
                            {{ $experience->start_date->format('M Y') }} -
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

                    @if($experience->achievements)
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

    <div class="section">
        <h2>Education</h2>
        @foreach($educations as $education)
            <div class="item">
                <div class="item-header">
                    <div>
                        <span class="organization">{{ $education->institution }}</span>
                        <span class="date">
                            {{ $education->start_date->format('Y') }} -
                            {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                        </span>
                    </div>
                </div>
                <div class="title-text">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                @if($education->grade)
                    <div style="margin-top: 5px;">GPA/Grade: {{ $education->grade }}</div>
                @endif
                @if($education->activities)
                    <div style="margin-top: 5px;">Activities: {{ $education->activities }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="section">
        <h2>Skills & Expertise</h2>
        <div class="skills-list">
            @php
                $groupedSkills = $skills->groupBy('category');
            @endphp
            @foreach($groupedSkills as $category => $categorySkills)
                <div style="margin-bottom: 10px;">
                    <span class="skill-category">{{ strtoupper($category) }}:</span>
                    {{ $categorySkills->pluck('name')->join(', ') }}
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
