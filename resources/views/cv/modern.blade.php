<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modern CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #667eea;
            background-image: -webkit-linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
            -webkit-print-color-adjust: exact;
        }

        .name {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title {
            font-size: 14pt;
            margin-bottom: 20px;
            font-weight: 300;
            opacity: 0.9;
        }

        .contact-info {
            font-size: 9pt;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .contact-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .content {
            padding: 40px;
        }

        h2 {
            font-size: 12pt;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 30px;
        }

        .item {
            margin-bottom: 20px;
            padding-left: 20px;
            border-left: 3px solid #667eea;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .position {
            font-weight: bold;
            font-size: 11pt;
            color: #333;
        }

        .date {
            color: #666;
            font-weight: bold;
            font-size: 10pt;
        }

        .position {
            font-weight: bold;
            font-size: 11pt;
            color: #333;
        }

        .company {
            font-weight: bold;
            color: #667eea;
            font-size: 11pt;
        }

        .location {
            font-style: italic;
            color: #666;
        }

        .date {
            color: #666;
            font-weight: bold;
            font-size: 10pt;
        }

        .description {
            margin-top: 5px;
            color: #555;
            text-align: justify;
        }

        ul {
            margin-top: 8px;
            margin-bottom: 5px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 4px;
            color: #555;
        }

        .skills-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .skill-category {
            background: #f8f9ff;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }

        .skill-category strong {
            display: block;
            color: #667eea;
            margin-bottom: 8px;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .skill-category span {
            display: block;
            color: #555;
            font-size: 9pt;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="name">{{ $profile->name ?? 'Your Name' }}</div>
            <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            <div class="contact-info">
                <span>
                    <x-heroicon-o-envelope
                        style="width: 14px; height: 14px; vertical-align: middle; display: inline-block;" />
                    {{ $profile->email ?? 'email@example.com' }}
                </span>
                <span>
                    <x-heroicon-o-phone
                        style="width: 14px; height: 14px; vertical-align: middle; display: inline-block;" />
                    {{ $profile->phone ?? 'Phone' }}
                </span>
                <span>
                    <x-heroicon-o-map-pin
                        style="width: 14px; height: 14px; vertical-align: middle; display: inline-block;" />
                    {{ $profile->location ?? 'Location' }}
                </span>
            </div>
        </div>

        <div class="content">
            @if($profile->biography)
                <div class="section">
                    <h2>Professional Summary</h2>
                    <div class="description">
                        {{ strip_tags($profile->biography) }}
                    </div>
                </div>
            @endif

            <div class="section">
                <h2>Experience</h2>
                @foreach($experiences as $experience)
                    <div class="item">
                        <div class="item-header">
                            <span class="position">{{ $experience->position }}</span>
                            <span class="date">
                                {{ $experience->start_date->format('M Y') }} -
                                {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                            </span>
                        </div>
                        <div>
                            <span class="company">{{ $experience->company }}</span>,
                            <span class="location">{{ $experience->location }}</span>
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
                            <span class="company">{{ $education->institution }}</span>
                            <span class="date">
                                {{ $education->start_date->format('Y') }} -
                                {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                            </span>
                        </div>
                        <div class="position">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                        @if($education->grade)
                            <div style="margin-top: 5px; color: #666;">GPA/Grade: {{ $education->grade }}</div>
                        @endif
                        @if($education->activities)
                            <div style="margin-top: 5px; color: #666;">Activities: {{ $education->activities }}</div>
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
                        <div class="skill-category">
                            <strong>{{ $category }}</strong>
                            <span>{{ $categorySkills->pluck('name')->join(', ') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>

</html>