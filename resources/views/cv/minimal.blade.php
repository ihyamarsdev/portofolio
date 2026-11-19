<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @include('cv.partials._base_styles')

        :root {
            --cv-font: 'Outfit', system-ui, sans-serif;
            --cv-font-display: 'Space Grotesk', sans-serif;
            --cv-body-size: 10pt;
            --cv-body-leading: 1.45;
            --cv-screen-pad: 14mm;
            --cv-accent: #b45309;
            --cv-text: #18181b;
            --cv-text-soft: #3f3f46;
            --cv-muted: #71717a;
            --cv-rule: #e4e4e7;
            --cv-sheet: #ffffff;
            --cv-page-bg: #fafafa;
        }

        .header {
            padding: 30px 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
        }

        .identity { position: relative; padding-left: 12px; }
        .identity::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; background: var(--cv-accent);
        }

        .name { font-family: var(--cv-font-display); font-size: 22pt; font-weight: 300; letter-spacing: -1px; }
        .title { font-size: 10pt; color: var(--cv-accent); margin-top: 4px; }

        .contact { text-align: right; font-size: 9pt; color: var(--cv-muted); }
        .contact a { color: inherit; text-decoration: none; }

        .content { padding: 6px 0 0; }

        .section-title {
            font-family: var(--cv-font-display);
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--cv-muted);
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: none;
        }
        .section-title::after { content: ''; flex: 1; height: 1px; background: var(--cv-rule); }

        .experience-item { margin-bottom: 14px; padding-left: 14px; border-left: 1px solid var(--cv-rule); }
        .entry-role { font-family: var(--cv-font-display); font-size: 11pt; font-weight: 500; }
        .entry-date { color: var(--cv-muted); font-size: 9pt; }
        .company-location { font-size: 9pt; color: var(--cv-text-soft); }
        .description { font-size: 9.5pt; font-weight: 300; }

        .education-item { margin-bottom: 12px; }
        .degree { font-family: var(--cv-font-display); font-size: 10pt; font-weight: 500; }
        .institution { font-size: 9pt; color: var(--cv-text-soft); }
        .meta { font-size: 8.5pt; color: var(--cv-muted); }

        .summary { font-weight: 300; max-width: 480px; }
        .skill-line { font-size: 9.5pt; font-weight: 300; }
        .project-description { font-size: 9.5pt; font-weight: 300; }
        .certificate-ledger { list-style: none; }
        .certificate-ledger li { font-size: 9pt; }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <div class="identity">
                <div class="name">{{ $profile->name ?? 'Your Name' }}</div>
                <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            </div>
            <div class="contact">
                @php
                    $contact = array_filter([$profile->email ?? null, $profile->phone ?? null, $profile->location ?? null]);
                @endphp
                {{ implode(' | ', $contact) }}
            </div>
        </header>

        <main class="content">
            @if($profile->biography ?? false)
                <section class="section">
                    <h2 class="section-title">About</h2>
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
                            <p class="company-location">{{ $experience->company }}@if($experience->location) · {{ $experience->location }}@endif</p>
                            @if($experience->description)<p class="description">{{ strip_tags($experience->description) }}</p>@endif
                            @if($experience->achievements && count($experience->achievements) > 0)
                                <ul class="bullets">@foreach($experience->achievements as $achievement)<li>{{ $achievement }}</li>@endforeach</ul>
                            @endif
                        </article>
                    @endforeach
                    @if(isset($totalExperiences) && $totalExperiences > 3)
                        <div class="cv-note">* Showing 3 most recent of {{ $totalExperiences }} roles. Full history: {{ route('about') }}</div>
                    @endif
                </section>
            @endif

            @include('cv.partials._projects')

            @if($educations->count() > 0)
                <section class="section">
                    <h2 class="section-title">Education</h2>
                    @foreach($educations as $education)
                        <article class="education-item">
                            <div class="entry-head">
                                <span class="degree">{{ $education->degree }}</span>
                                <span class="entry-date">{{ $education->start_date->format('Y') }} – {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</span>
                            </div>
                            <p class="institution">{{ $education->institution }} · {{ $education->field_of_study }}</p>
                        </article>
                    @endforeach
                    @if(isset($totalEducations) && $totalEducations > 3)
                        <div class="cv-note">* Showing 3 most recent of {{ $totalEducations }} education entries. Full history: {{ route('about') }}</div>
                    @endif
                </section>
            @endif

            @if($skills->count() > 0)
                @include('cv.partials._skills', ['heading' => 'Skills'])
            @endif

            @include('cv.partials._certificates')
        </main>
    </div>
    @include('cv.partials._toolbar')
</body>
</html>
