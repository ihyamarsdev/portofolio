<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }} | Curriculum Vitae</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Crimson+Pro:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        @include('cv.partials._base_styles')

        :root {
            --cv-font: 'Crimson Pro', Georgia, serif;
            --cv-font-display: 'Cormorant Garamond', serif;
            --cv-body-size: 10.5pt;
            --cv-body-leading: 1.5;
            --cv-section-gap: 24px;
            --cv-accent: #881337;
            --cv-accent-soft: #ffe4e6;
            --cv-text: #1c1917;
            --cv-text-soft: #44403c;
            --cv-muted: #78716c;
            --cv-rule: #d6d3d1;
            --cv-sheet: #ffffff;
            --cv-page-bg: #fafaf9;
        }

        .page { position: relative; }

        .page::before {
            content: '';
            position: absolute;
            top: 16mm; left: 16mm; right: 16mm; bottom: 16mm;
            border: 1px solid var(--cv-rule);
            pointer-events: none;
        }

        .header {
            padding: 34mm 24mm 22mm;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%;
            transform: translateX(-50%);
            width: 90px; height: 2px;
            background: var(--cv-accent);
        }

        .name {
            font-family: var(--cv-font-display);
            font-size: 30pt;
            font-weight: 600;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .title {
            font-family: var(--cv-font);
            font-size: 12pt;
            font-style: italic;
            color: var(--cv-accent);
            margin-top: 8px;
        }

        .contact-line { margin-top: 14px; font-size: 9.5pt; color: var(--cv-muted); }
        .contact-line a { color: inherit; text-decoration: none; }

        .content { padding: 10mm 24mm 26mm; }

        .section-title {
            font-family: var(--cv-font-display);
            font-size: 11pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 4px;
            text-align: center;
            color: var(--cv-text);
            border-bottom: none;
        }

        .section-title::before, .section-title::after {
            content: '';
            display: inline-block;
            width: 40px;
            height: 1px;
            background: var(--cv-rule);
            vertical-align: middle;
            margin: 0 12px;
        }

        .experience-item { padding-left: 20px; position: relative; margin-bottom: 18px; }
        .experience-item::before {
            content: '§';
            position: absolute; left: 0; top: 0;
            font-family: var(--cv-font-display);
            color: var(--cv-accent);
        }
        .entry-role { font-family: var(--cv-font-display); font-size: 12pt; font-weight: 600; }
        .entry-date { font-style: italic; color: var(--cv-accent); }
        .company-location { font-size: 9.8pt; color: var(--cv-muted); margin-top: 2px; }
        .description { font-size: 10pt; margin-top: 5px; }

        .education-item { text-align: center; margin-bottom: 14px; }
        .degree { font-family: var(--cv-font-display); font-size: 12pt; font-weight: 600; }
        .institution { font-size: 9.8pt; font-style: italic; color: var(--cv-muted); }
        .meta { font-size: 9pt; color: var(--cv-muted); margin-top: 2px; }

        .summary { text-align: center; font-style: italic; max-width: 460px; margin: 0 auto; }
        .skill-line { text-align: center; font-size: 9.8pt; }
        .project-description { font-size: 9.8pt; }
        .certificate-ledger { list-style: none; text-align: center; }
        .certificate-ledger li { font-size: 9.8pt; }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <h1 class="name">{{ $profile->name ?? 'Your Name' }}</h1>
            <p class="title">{{ $profile->title ?? 'Professional Title' }}</p>
            <div class="contact-line">
                @php
                    $contact = array_filter([$profile->email ?? null, $profile->phone ?? null, $profile->location ?? null]);
                    if (isset($profile->social_links) && is_iterable($profile->social_links)) {
                        foreach ($profile->social_links as $link) { if (isset($link['platform'])) { $contact[] = ucfirst($link['platform']); } }
                    }
                @endphp
                {{ implode('  ·  ', $contact) }}
            </div>
        </header>

        <main class="content">
            @if($profile->biography ?? false)
                <section class="section">
                    <h2 class="section-title">Profile</h2>
                    <p class="summary">{{ strip_tags($profile->biography) }}</p>
                </section>
            @endif

            @if($experiences->count() > 0)
                <section class="section">
                    <h2 class="section-title">Experience</h2>
                    @foreach($experiences as $experience)
                        <article class="experience-item">
                            <div class="entry-head">
                                <span class="entry-role">{{ $experience->position }}</span>
                                <span class="entry-date">{{ $experience->start_date->format('M Y') }} – {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}</span>
                            </div>
                            <p class="company-location">{{ $experience->company }}@if($experience->location), {{ $experience->location }}@endif</p>
                            @if($experience->description)<p class="description">{{ strip_tags($experience->description) }}</p>@endif
                            @if($experience->achievements && count($experience->achievements) > 0)
                                <ul class="bullets">@foreach($experience->achievements as $achievement)<li>{{ $achievement }}</li>@endforeach</ul>
                            @endif
                        </article>
                    @endforeach
                    @if(isset($totalExperiences) && $totalExperiences > 3)
                        <div class="cv-note" style="text-align:center;">* Showing 3 most recent of {{ $totalExperiences }} roles. Full history: {{ route('about') }}</div>
                    @endif
                </section>
            @endif

            @include('cv.partials._projects')

            @if($educations->count() > 0)
                <section class="section">
                    <h2 class="section-title">Education</h2>
                    @foreach($educations as $education)
                        <article class="education-item">
                            <h3 class="degree">{{ $education->degree }}</h3>
                            <p class="institution">{{ $education->institution }} — {{ $education->field_of_study }}</p>
                            <p class="entry-date">{{ $education->start_date->format('Y') }} – {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</p>
                            @if($education->grade || $education->activities)
                                <p class="meta">@if($education->grade)GPA: {{ $education->grade }}@endif@if($education->grade && $education->activities) · @endif@if($education->activities){{ $education->activities }}@endif</p>
                            @endif
                        </article>
                    @endforeach
                    @if(isset($totalEducations) && $totalEducations > 3)
                        <div class="cv-note" style="text-align:center;">* Showing 3 most recent of {{ $totalEducations }} education entries. Full history: {{ route('about') }}</div>
                    @endif
                </section>
            @endif

            @if($skills->count() > 0)
                @include('cv.partials._skills', ['heading' => 'Expertise'])
            @endif

            @include('cv.partials._certificates')
        </main>
    </div>
    @include('cv.partials._toolbar')
</body>
</html>
