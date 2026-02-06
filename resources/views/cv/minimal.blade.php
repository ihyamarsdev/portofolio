<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Minimal CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            font-size: 10pt;
            line-height: 1.7;
            color: #2c3e50;
            background: #fff;
            padding: 50px;
        }

        .header {
            margin-bottom: 40px;
        }

        .name {
            font-size: 20pt;
            font-weight: 300;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .title {
            font-size: 11pt;
            color: #7f8c8d;
            margin-bottom: 15px;
            font-weight: 400;
        }

        .contact-info {
            font-size: 9pt;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .contact-info span {
            margin-right: 25px;
        }

        h2 {
            font-size: 11pt;
            font-weight: 400;
            color: #2c3e50;
            margin-top: 35px;
            margin-bottom: 20px;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .section {
            margin-bottom: 30px;
        }

        .item {
            margin-bottom: 25px;
        }

        .item-header {
            margin-bottom: 8px;
        }

        .position {
            font-weight: 500;
            font-size: 10.5pt;
            color: #2c3e50;
        }

        .organization {
            font-weight: 400;
            color: #34495e;
            font-size: 10pt;
        }

        .location {
            color: #7f8c8d;
            font-size: 9pt;
        }

        .date {
            color: #7f8c8d;
            font-weight: 400;
            float: right;
            font-size: 9pt;
        }

        .description {
            margin-top: 8px;
            color: #576574;
            line-height: 1.8;
        }

        ul {
            margin-top: 10px;
            margin-bottom: 5px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 5px;
            color: #576574;
            line-height: 1.8;
        }

        .skills-list {
            margin-top: 15px;
        }

        .skill-category {
            color: #2c3e50;
            font-weight: 500;
            margin-right: 8px;
            font-size: 10pt;
        }

        .company-line {
            display: flex;
            align-items: baseline;
            gap: 10px;
            flex-wrap: wrap;
        }

        .summary {
            color: #576574;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="name">{{ $profile->name ?? 'Your Name' }}</div>
        <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
        <div class="contact-info">
            <span>{{ $profile->email ?? 'email@example.com' }}</span>
            <span>{{ $profile->phone ?? 'Phone' }}</span>
            <span>{{ $profile->location ?? 'Location' }}</span>
        </div>
    </div>

    @if($profile->biography)
        <div class="section">
            <h2>Summary</h2>
            <div class="summary">
                {{ strip_tags($profile->biography) }}
            </div>
        </div>
    @endif

    <div class="section">
        <h2>Experience</h2>
        @foreach($experiences as $experience)
            <div class="item">
                <div class="item-header">
                    <div class="company-line">
                        <span class="position">{{ $experience->position }}</span>
                        <span class="date">
                            {{ $experience->start_date->format('M Y') }} -
                            {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                        </span>
                    </div>
                    <div class="company-line">
                        <span class="organization">{{ $experience->company }}</span>
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
                    <div class="company-line">
                        <span class="organization">{{ $education->institution }}</span>
                        <span class="date">
                            {{ $education->start_date->format('Y') }} -
                            {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                        </span>
                    </div>
                </div>
                <div class="position">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                @if($education->grade)
                    <div style="margin-top: 8px; color: #7f8c8d; font-size: 9pt;">GPA/Grade: {{ $education->grade }}</div>
                @endif
                @if($education->activities)
                    <div style="margin-top: 8px; color: #7f8c8d; font-size: 9pt;">Activities: {{ $education->activities }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="section">
        <h2>Skills</h2>
        <div class="skills-list">
            @php
                $groupedSkills = $skills->groupBy('category');
            @endphp
            @foreach($groupedSkills as $category => $categorySkills)
                <div style="margin-bottom: 12px;">
                    <span class="skill-category">{{ $category }}</span>
                    <span style="color: #576574;">{{ $categorySkills->pluck('name')->join(', ') }}</span>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
