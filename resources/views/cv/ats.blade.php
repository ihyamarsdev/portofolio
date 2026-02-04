<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CV - {{ $profile->name ?? 'Candidate' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .name {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .contact-info {
            font-size: 10pt;
            margin-bottom: 5px;
        }

        .contact-info span {
            margin: 0 5px;
        }

        h2 {
            font-size: 14pt;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #000;
        }

        .section {
            margin-bottom: 15px;
        }

        .item {
            margin-bottom: 12px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }

        .title {
            font-weight: bold;
            font-size: 11pt;
        }

        .organization {
            font-weight: bold;
            font-size: 11pt;
        }

        .location {
            font-style: italic;
        }

        .date {
            float: right;
            font-weight: bold;
        }

        .description {
            margin-top: 5px;
            text-align: justify;
        }

        ul {
            margin-top: 5px;
            margin-bottom: 5px;
            padding-left: 18px;
        }

        li {
            margin-bottom: 2px;
        }

        .skills-list {
            margin-top: 5px;
        }

        .skill-category {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="name">{{ $profile->name ?? 'Your Name' }}</div>
        <div class="contact-info">
            {{ $profile->title ?? 'Professional Title' }}
        </div>
        <div class="contact-info">
            <span>{{ $profile->email ?? 'email@example.com' }}</span> |
            <span>{{ $profile->phone ?? 'Phone' }}</span> |
            <span>{{ $profile->location ?? 'Location' }}</span>
        </div>
        <div class="contact-info">
            @if(isset($profile->social_links))
                @foreach($profile->social_links as $platform => $url)
                    <span><a href="{{ $url }}" style="color: #333; text-decoration: none;">{{ ucfirst($platform) }}</a></span>
                    @if(!$loop->last) | @endif
                @endforeach
            @endif
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
        <h2>Experience</h2>
        @foreach($experiences as $experience)
            <div class="item">
                <div class="item-header">
                    <span class="title">{{ $experience->position }}</span>
                    <span class="date">
                        {{ $experience->start_date->format('M Y') }} -
                        {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                    </span>
                </div>
                <div>
                    <span class="organization">{{ $experience->company }}</span>,
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
                    <span class="organization">{{ $education->institution }}</span>
                    <span class="date">
                        {{ $education->start_date->format('Y') }} -
                        {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                    </span>
                </div>
                <div class="title">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                @if($education->grade)
                    <div>GPA/Grade: {{ $education->grade }}</div>
                @endif
                @if($education->activities)
                    <div>Activities: {{ $education->activities }}</div>
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
                <div>
                    <span class="skill-category">{{ $category }}:</span>
                    {{ $categorySkills->pluck('name')->join(', ') }}
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>