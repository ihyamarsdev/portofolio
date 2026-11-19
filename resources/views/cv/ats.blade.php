<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }} | CV</title>
    <style>
        @include('cv.partials._base_styles')

        /* ATS template: strictly single column, no decoration, no icons. */
        :root {
            --cv-font: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --cv-font-display: 'DM Sans', sans-serif;
            --cv-body-size: 10pt;
            --cv-body-leading: 1.4;
            --cv-section-gap: 20px;
            --cv-accent: #1d4ed8;
            --cv-screen-pad: 14mm;
            --cv-text-soft: #334155;
            --cv-muted: #64748b;
        }

        .page { display: block; }

        .header {
            padding: 22px 0 16px;
            border-bottom: 2px solid var(--cv-text);
            text-align: left;
        }

        .name {
            font-family: var(--cv-font-display);
            font-size: 22pt;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }

        .title {
            font-size: 11pt;
            font-weight: 600;
            color: var(--cv-text-soft);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        .contact-line {
            margin-top: 8px;
            font-size: 9.5pt;
            color: var(--cv-text-soft);
            font-variant-numeric: tabular-nums;
        }

        .contact-line a { color: inherit; text-decoration: none; }

        .content { padding: 18px 0 0; }

        .section-title {
            font-family: var(--cv-font-display);
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--cv-text);
            border-bottom: 1px solid var(--cv-rule);
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .experience-item {
            padding-bottom: 12px;
            margin-bottom: 14px;
            border-bottom: 1px solid var(--cv-rule);
        }

        .experience-item:last-child { border-bottom: none; margin-bottom: 0; }

        .position { font-size: 10.5pt; }

        .company-location { font-size: 9.8pt; margin-top: 1px; }

        .description { font-size: 9.5pt; margin-top: 4px; color: var(--cv-text-soft); }

        .education-item {
            padding-bottom: 10px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--cv-rule);
        }

        .education-item:last-child { border-bottom: none; margin-bottom: 0; }

        .degree { font-size: 10.5pt; font-weight: 700; }
        .institution { font-size: 9.8pt; color: var(--cv-text-soft); }
        .meta { font-size: 9pt; color: var(--cv-muted); margin-top: 2px; }

        .skill-line { font-size: 9.8pt; margin-bottom: 4px; }
        .skill-years { color: var(--cv-muted); }

        .project-item { margin-bottom: 12px; }
        .project-description { font-size: 9.5pt; color: var(--cv-text-soft); margin-top: 3px; }
        .certificate-ledger { list-style: none; }
        .certificate-ledger li { font-size: 9.5pt; margin-bottom: 3px; }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <div class="name">{{ $profile->name ?? 'YOUR NAME' }}</div>
            <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            <div class="contact-line">
                @php
                    $contact = array_filter([
                        $profile->email ?? null,
                        $profile->phone ?? null,
                        $profile->location ?? null,
                    ]);
                    if (isset($profile->social_links) && is_iterable($profile->social_links)) {
                        foreach ($profile->social_links as $link) {
                            if (isset($link['url'])) {
                                $contact[] = $link['url'];
                            }
                        }
                    }
                @endphp
                {{ implode(' | ', $contact) }}
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
                        <div class="experience-item">
                            <div class="entry-head">
                                <span><span class="entry-role">{{ $experience->position }}</span> — <span class="entry-company">{{ $experience->company }}</span></span>
                                <span class="entry-date">{{ $experience->start_date->format('M Y') }} – {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}</span>
                            </div>
                            @if($experience->location)
                                <div class="entry-context">{{ $experience->location }}</div>
                            @endif
                            @if($experience->description)
                                <p class="description">{{ strip_tags($experience->description) }}</p>
                            @endif
                            @if($experience->achievements && count($experience->achievements) > 0)
                                <ul class="bullets">
                                    @foreach($experience->achievements as $achievement)
                                        <li>{{ $achievement }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
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
                        <div class="education-item">
                            <div class="entry-head">
                                <span class="degree">{{ $education->degree }}</span>
                                <span class="entry-date">{{ $education->start_date->format('Y') }} – {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</span>
                            </div>
                            <p class="institution">{{ $education->institution }} · {{ $education->field_of_study }}</p>
                            @if($education->grade || $education->activities)
                                <p class="meta">@if($education->grade)GPA: {{ $education->grade }}@endif@if($education->grade && $education->activities) · @endif@if($education->activities){{ $education->activities }}@endif</p>
                            @endif
                        </div>
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
