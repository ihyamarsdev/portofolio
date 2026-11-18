<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name ?? 'Candidate' }} | CV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        @include('cv.partials._base_styles')

        :root {
            --cv-font: 'IBM Plex Sans', sans-serif;
            --cv-font-display: 'IBM Plex Mono', monospace;
            --cv-font-mono: 'IBM Plex Mono', monospace;
            --cv-body-size: 9.8pt;
            --cv-body-leading: 1.45;
            --cv-section-gap: 20px;
            --cv-accent: #0891b2;
            --cv-accent-soft: #164e63;
            --cv-text: #0f172a;
            --cv-text-soft: #334155;
            --cv-muted: #64748b;
            --cv-rule: #cbd5e1;
            --cv-sheet: #f8fafc;
            --cv-page-bg: #020617;
        }

        .page { display: flex; flex-direction: row; }

        .sidebar {
            width: 34%;
            padding: 24px 20px;
            background: #0f172a;
            color: #e2e8f0;
            position: relative;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .sidebar::after {
            content: '';
            position: absolute; right: 0; top: 0; bottom: 0;
            width: 3px; background: #06b6d4;
        }

        .prompt { font-family: var(--cv-font-mono); font-size: 8.5pt; color: #22d3ee; margin-bottom: 3px; }
        .prompt::before { content: '$ '; color: #67e8f9; }

        .name { font-family: var(--cv-font-mono); font-size: 20pt; font-weight: 700; color: #fff; line-height: 1.2; }
        .title { font-size: 10pt; color: #22d3ee; margin: 4px 0 20px; }

        .contact-block { margin-bottom: 22px; padding-top: 12px; border-top: 1px solid #334155; }
        .contact-line { font-family: var(--cv-font-mono); font-size: 8.5pt; color: #94a3b8; line-height: 1.7; }
        .contact-line a { color: #67e8f9; text-decoration: none; }
        .contact-line::before { content: '> '; color: #22d3ee; }

        .sidebar .section-title {
            font-family: var(--cv-font-mono);
            font-size: 8.5pt;
            color: #22d3ee;
            border-bottom: 1px solid #334155;
            padding-bottom: 6px;
        }
        .sidebar .skill-line { color: #cbd5e1; font-size: 8.5pt; }
        .sidebar .skill-line-label { color: #e2e8f0; }
        .sidebar .skill-years { color: #22d3ee; }

        .main-content { width: 66%; padding: 24px 22px; background: #f8fafc; }

        .main-content .section-title {
            font-family: var(--cv-font-mono);
            font-size: 9pt;
            color: #0f172a;
            border-bottom: 2px dashed #06b6d4;
            padding-bottom: 6px;
        }
        .main-content .section-title::before { content: '// '; color: #0891b2; }

        .experience-item { margin-bottom: 16px; padding-left: 12px; border-left: 3px solid #06b6d4; position: relative; }
        .experience-item::before {
            content: '■'; position: absolute; left: -6px; top: 1px;
            color: #06b6d4; font-size: 8pt;
        }
        .entry-role { font-size: 10.5pt; font-weight: 600; }
        .entry-date { font-family: var(--cv-font-mono); font-size: 8.5pt; color: #0e7490; background: #e0f2fe; padding: 2px 6px; }
        .company-location { font-size: 9.5pt; margin-top: 2px; }
        .description { font-size: 9.5pt; }

        .education-item { margin-bottom: 14px; padding-left: 12px; border-left: 3px solid #06b6d4; position: relative; }
        .education-item::before {
            content: '■'; position: absolute; left: -6px; top: 1px;
            color: #06b6d4; font-size: 8pt;
        }
        .degree { font-size: 9.8pt; }
        .institution { font-weight: 600; color: #0f172a; font-size: 10pt; }
        .meta { font-size: 8.5pt; color: var(--cv-muted); }

        .project-description { font-size: 9.5pt; }
        .certificate-ledger { list-style: none; }
        .certificate-ledger li { font-size: 9pt; }
    </style>
</head>
<body>
    <div class="page">
        <div class="sidebar">
            <div class="identity">
                <div class="prompt">whoami</div>
                <div class="name">{{ $profile->name ?? 'YOUR NAME' }}</div>
                <div class="title">{{ $profile->title ?? 'Professional Title' }}</div>
            </div>

            <div class="contact-block">
                <div class="prompt">contact</div>
                <div class="contact-line">
                    @php
                        $contact = array_filter([$profile->email ?? null, $profile->phone ?? null, $profile->location ?? null]);
                    @endphp
                    {{ implode(' | ', $contact) }}
                </div>
            </div>

            @if($skills->count() > 0)
                <div class="section">
                    <h2 class="section-title">skills --list</h2>
                    @include('cv.partials._skills', ['heading' => '', 'section_class' => '', 'heading_class' => 'section-title'])
                </div>
            @endif
        </div>

        <div class="main-content">
            @if($profile->biography ?? false)
                <section class="section">
                    <h2 class="section-title">PROFESSIONAL SUMMARY</h2>
                    <p class="summary">{{ strip_tags($profile->biography) }}</p>
                </section>
            @endif

            @if($experiences->count() > 0)
                <section class="section">
                    <h2 class="section-title">EXPERIENCE</h2>
                    @foreach($experiences as $experience)
                        <div class="experience-item">
                            <div class="entry-head">
                                <span class="entry-role">{{ $experience->position }}</span>
                                <span class="entry-date">{{ $experience->start_date->format('Y.m') }} – {{ $experience->end_date ? $experience->end_date->format('Y.m') : 'Present' }}</span>
                            </div>
                            <div class="company-location">{{ $experience->company }}@if($experience->location) · {{ $experience->location }}@endif</div>
                            @if($experience->description)<p class="description">{{ strip_tags($experience->description) }}</p>@endif
                            @if($experience->achievements && count($experience->achievements) > 0)
                                <ul class="bullets">@foreach($experience->achievements as $achievement)<li>{{ $achievement }}</li>@endforeach</ul>
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
                    <h2 class="section-title">EDUCATION</h2>
                    @foreach($educations as $education)
                        <div class="education-item">
                            <div class="entry-head">
                                <span class="institution">{{ $education->institution }}</span>
                                <span class="entry-date">{{ $education->start_date->format('Y') }} – {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}</span>
                            </div>
                            <div class="degree">{{ $education->degree }} in {{ $education->field_of_study }}</div>
                            @if($education->grade || $education->activities)
                                <div class="meta">@if($education->grade)GPA: {{ $education->grade }}@endif@if($education->grade && $education->activities) · @endif@if($education->activities)Activities: {{ $education->activities }}@endif</div>
                            @endif
                        </div>
                    @endforeach
                    @if(isset($totalEducations) && $totalEducations > 3)
                        <div class="cv-note">* Showing 3 most recent of {{ $totalEducations }} education entries. Full history: {{ route('about') }}</div>
                    @endif
                </section>
            @endif

            @include('cv.partials._certificates')
        </div>
    </div>
    @include('cv.partials._toolbar')
</body>
</html>
