            <div class="tab-pane-content animate-fade-in am-scope">
                <div class="am-layout">

                    {{-- Kolom input --}}
                    <div class="am-col-input">
                        <div class="am-card">
                            <div class="am-card-head">
                                <span class="am-ico"><x-heroicon-o-briefcase class="w-4.5 h-4.5" /></span>
                                <div>
                                    <h3 class="am-title">{{ __('cv-aimatch.input.title') }}</h3>
                                    <p class="am-hint">{{ __('cv-aimatch.input.hint') }}</p>
                                </div>
                            </div>

                            <div class="am-field">
                                <label class="am-label" for="am-target-role">{{ __('cv-aimatch.input.target_role') }}</label>
                                <select id="am-target-role" wire:model.live="targetRole" class="am-select">
                                    <option value="Devops Engineer">{{ __('cv-aimatch.input.roles.devops') }}</option>
                                    <option value="Devsecops Engineer">{{ __('cv-aimatch.input.roles.devsecops') }}</option>
                                    <option value="Cloud Engineer">{{ __('cv-aimatch.input.roles.cloud') }}</option>
                                    <option value="Site Reliability Engineer">{{ __('cv-aimatch.input.roles.sre') }}</option>
                                </select>
                            </div>

                            <div class="am-field">
                                <label class="am-label" for="am-jobdesc">
                                    {{ __('cv-aimatch.input.jobdesc') }} <span class="am-optional">{{ __('cv-aimatch.input.optional') }}</span>
                                </label>
                                <textarea id="am-jobdesc" wire:model="jobDescription" rows="8" class="am-textarea"
                                          placeholder="{{ __('cv-aimatch.input.jobdesc_placeholder') }}"></textarea>
                                <p class="am-hint">{{ __('cv-aimatch.input.jobdesc_hint') }}</p>
                            </div>

                            <x-filament::button wire:click="analyzeJobMatch" color="primary" class="w-full" :loading="$isAnalyzing">
                                <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                                <span>{{ __('cv-aimatch.input.run_analysis') }}</span>
                            </x-filament::button>
                        </div>

                        <x-cv-analysis-progress />

                        @if($history && count(is_countable($history) ? $history : []) > 0)
                            <div class="am-card">
                                <div class="am-card-head">
                                    <span class="am-ico"><x-heroicon-o-clock class="w-4.5 h-4.5" /></span>
                                    <div>
                                        <h3 class="am-title">{{ __('cv-aimatch.history.title') }}</h3>
                                        <p class="am-hint">{{ __('cv-aimatch.history.hint') }}</p>
                                    </div>
                                </div>
                                <div class="am-history">
                                    @foreach($history as $item)
                                        @php
                                            $histRole = $item->job_title_targeted ?? __('cv-aimatch.history.default_title');
                                            $histDate = $item->created_at->translatedFormat('d M Y, H:i');
                                        @endphp
                                        <button type="button" wire:key="am-hist-{{ $item->id }}"
                                                wire:click="loadAnalysisIntoWorkspace({{ $item->id }})"
                                                aria-label="{{ __('cv-aimatch.history.load_aria', ['role' => $histRole, 'date' => $histDate]) }}"
                                                class="am-history-item {{ $currentAnalysis?->id === $item->id ? 'is-active' : '' }}">
                                            <span class="am-history-main">
                                                <span class="am-history-role">{{ $histRole }}</span>
                                                <span class="am-history-date">{{ $histDate }}</span>
                                            </span>
                                            @if($item->job_match_score !== null)
                                                <span class="am-chip {{ $item->job_match_score >= 70 ? 'am-chip--ok' : ($item->job_match_score >= 50 ? 'am-chip--warn' : 'am-chip--bad') }}">
                                                    {{ $item->job_match_score }}%
                                                </span>
                                            @else
                                                <span class="am-chip am-chip--neutral">ATS {{ $item->ats_score }}%</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom hasil --}}
                    <div class="am-col-result">
                        @if(!$isAnalyzing && $analysisResult)
                            @php
                                // Profil metrik mengikuti role target agar panel tidak menjadi
                                // daftar persentase generik.
                                $roleKey = strtolower(trim($analysisResult['job_title_targeted'] ?? $targetRole ?? ''));
                                $roleKey = str_contains($roleKey, 'devsecops') ? 'devsecops engineer'
                                    : (str_contains($roleKey, 'reliability') ? 'site reliability engineer'
                                    : (str_contains($roleKey, 'cloud') ? 'cloud engineer' : 'devops engineer'));

                                $roleProfiles = [
                                    'devops engineer' => [
                                        'domain' => 'devops',
                                        'tagline' => __('cv-aimatch.summary.role_taglines.devops engineer'),
                                        'core' => ['role_alignment_score', 'reliability_score', 'format_score'],
                                    ],
                                    'devsecops engineer' => [
                                        'domain' => 'devsecops',
                                        'tagline' => __('cv-aimatch.summary.role_taglines.devsecops engineer'),
                                        'core' => ['security_score', 'role_alignment_score'],
                                    ],
                                    'cloud engineer' => [
                                        'domain' => 'cloud_engineer',
                                        'tagline' => __('cv-aimatch.summary.role_taglines.cloud engineer'),
                                        'core' => ['cloud_score', 'impact_score'],
                                    ],
                                    'site reliability engineer' => [
                                        'domain' => 'sre',
                                        'tagline' => __('cv-aimatch.summary.role_taglines.site reliability engineer'),
                                        'core' => ['reliability_score', 'impact_score'],
                                    ],
                                ];
                                $roleProfile = $roleProfiles[$roleKey];

                                $knownMetricKeys = [
                                    'role_alignment_score',
                                    'security_score',
                                    'reliability_score',
                                    'cloud_score',
                                    'ats_score',
                                    'format_score',
                                    'about_me_score',
                                    'impact_score',
                                    'job_match_score',
                                ];
                                $fitmentMetrics = [];
                                foreach ($knownMetricKeys as $mKey) {
                                    $fitmentMetrics[$mKey] = [
                                        'label' => __("cv-aimatch.metrics.items.{$mKey}.label"),
                                        'desc' => __("cv-aimatch.metrics.items.{$mKey}.desc"),
                                    ];
                                }

                                $getMetricLabel = function (string $key) use ($fitmentMetrics): string {
                                    if (isset($fitmentMetrics[$key]['label'])) {
                                        return $fitmentMetrics[$key]['label'];
                                    }
                                    $transKey = "cv-aimatch.metrics.items.{$key}.label";
                                    $translated = __($transKey);
                                    return $translated !== $transKey ? $translated : str_replace('_', ' ', \Illuminate\Support\Str::title($key));
                                };

                                $getMetricDesc = function (string $key) use ($fitmentMetrics): string {
                                    if (isset($fitmentMetrics[$key]['desc'])) {
                                        return $fitmentMetrics[$key]['desc'];
                                    }
                                    $transKey = "cv-aimatch.metrics.items.{$key}.desc";
                                    $translated = __($transKey);
                                    return $translated !== $transKey ? $translated : '';
                                };
                                $heroStatKeys = array_values(array_unique(array_merge($roleProfile['core'], ['ats_score'])));

                                // Deduplicate metrics: exclude metrics already surfaced in hero quick-stats
                                // so each assessment metric appears once across the entire AI match view.
                                $orderedMetrics = collect(array_keys($fitmentMetrics))
                                    ->diff($heroStatKeys)
                                    ->values();

                                $heroDomain = $analysisResult['industry_match'][$roleProfile['domain']] ?? null;
                                $jobMatchValue = is_numeric($analysisResult['job_match_score'] ?? null) ? (int) $analysisResult['job_match_score'] : null;
                                $heroPct = $jobMatchValue ?? (is_numeric($heroDomain) ? (int) $heroDomain : ($analysisResult['ats_score'] ?? null));

                                $detail = $analysisResult['job_match_detail'] ?? null;
                                $overall = is_array($detail) && isset($detail['overall']) ? (int) $detail['overall'] : null;

                                $rawBandLabel = $detail['band']['label'] ?? '';
                                $bandLabel = !empty($rawBandLabel)
                                    ? (__("cv-aimatch.bands.{$rawBandLabel}") !== "cv-aimatch.bands.{$rawBandLabel}"
                                        ? __("cv-aimatch.bands.{$rawBandLabel}")
                                        : $rawBandLabel)
                                    : null;
                                $bandDefinition = !empty($rawBandLabel)
                                    ? (__("cv-aimatch.band_definitions.{$rawBandLabel}") !== "cv-aimatch.band_definitions.{$rawBandLabel}"
                                        ? __("cv-aimatch.band_definitions.{$rawBandLabel}")
                                        : ($detail['band']['definition'] ?? ''))
                                    : ($detail['band']['definition'] ?? '');
                                $bandTone = $overall === null ? 'neutral' : ($overall >= 70 ? 'ok' : ($overall >= 50 ? 'warn' : 'bad'));

                                $statusMeta = [
                                    'verified' => ['label' => __('cv-aimatch.status.verified'), 'class' => 'am-chip--ok'],
                                    'claimed' => ['label' => __('cv-aimatch.status.claimed'), 'class' => 'am-chip--warn'],
                                    'missing' => ['label' => __('cv-aimatch.status.missing'), 'class' => 'am-chip--bad'],
                                ];
                                $focusAreas = collect($orderedMetrics)
                                    ->reject(fn ($key) => $key === 'job_match_score')
                                    ->map(fn ($key) => ['key' => $key] + $fitmentMetrics[$key] + ['value' => $analysisResult[$key] ?? null])
                                    ->filter(fn ($metric) => is_numeric($metric['value']) && (int) $metric['value'] < 70)
                                    ->sortBy('value')
                                    ->take(3)
                                    ->values();

                                $requirements = is_array($detail['requirements'] ?? null) ? $detail['requirements'] : [];
                                $mustHave = array_values(array_filter($requirements, fn ($r) => ($r['type'] ?? 'must_have') !== 'nice_to_have'));
                                $niceToHave = array_values(array_filter($requirements, fn ($r) => ($r['type'] ?? 'must_have') === 'nice_to_have'));

                                $foundKeywords = is_array($analysisResult['keywords']['found'] ?? null) ? $analysisResult['keywords']['found'] : [];
                                $weakKeywords = is_array($analysisResult['keywords']['weak_evidence'] ?? null) ? $analysisResult['keywords']['weak_evidence'] : [];
                                $missingKeywords = is_array($analysisResult['keywords']['missing'] ?? null) ? $analysisResult['keywords']['missing'] : [];
                                $suggestions = is_array($analysisResult['suggestions'] ?? null) ? $analysisResult['suggestions'] : [];
                                $strengths = is_array($analysisResult['strengths'] ?? null) ? $analysisResult['strengths'] : [];
                                $criticalGaps = is_array($analysisResult['critical_gaps'] ?? null) ? $analysisResult['critical_gaps'] : [];
                                $missingMetrics = is_array($analysisResult['missing_metrics'] ?? null) ? $analysisResult['missing_metrics'] : [];
                                $rewritePriorities = is_array($analysisResult['rewrite_priorities'] ?? null) ? $analysisResult['rewrite_priorities'] : [];
                                $experienceReview = is_array($analysisResult['experience_review'] ?? null) ? array_values(array_filter($analysisResult['experience_review'])) : [];
                                $industryMatch = is_array($analysisResult['industry_match'] ?? null) ? $analysisResult['industry_match'] : [];
                                // Executive-first data.
                                $density = is_array($detail['density'] ?? null) ? $detail['density'] : null;
                                $evidenceTiers = is_array($detail['evidence_tiers'] ?? null) ? $detail['evidence_tiers'] : [];
                                $rewriteSuggestions = is_array($detail['rewrite_suggestions'] ?? null) ? $detail['rewrite_suggestions'] : [];
                                $readinessScore = is_numeric($analysisResult['ats_score'] ?? null) ? (int) $analysisResult['ats_score'] : null;
                                $coverageScore = $overall;
                                // Priority fixes: deterministic Before -> After first, then top focus areas.
                                $priorityFixes = [];
                                foreach (array_slice($rewriteSuggestions, 0, 5) as $rw) {
                                    if (is_array($rw) && !empty($rw['after'])) {
                                        $priorityFixes[] = [
                                            'title' => (string) ($rw['requirement'] ?? ''),
                                            'before' => (string) ($rw['before'] ?? ''),
                                            'after' => (string) ($rw['after'] ?? ''),
                                        ];
                                    }
                                }
                                foreach ($focusAreas as $area) {
                                    if (count($priorityFixes) >= 5) { break; }
                                    $priorityFixes[] = [
                                        'title' => (string) ($area['label'] ?? ''),
                                        'before' => (string) ($area['desc'] ?? ''),
                                        'after' => __('cv-aimatch.improvements.lowest_scoring').': '.(int) $area['value'].'% — '.(string) ($area['desc'] ?? ''),
                                    ];
                                }
                            @endphp

                            <div class="am-result">
                                {{-- 1. Executive Summary Strip --}}
                                <div class="am-card am-exec-strip">
                                    <div class="am-exec-stat">
                                        <span class="am-exec-value am-tone-{{ $readinessScore !== null && $readinessScore >= 70 ? 'ok' : 'warn' }}">{{ $readinessScore ?? '-' }}%</span>
                                        <span class="am-exec-label">{{ __('cv-aimatch.exec.readiness') }}</span>
                                    </div>
                                    <div class="am-exec-stat">
                                        <span class="am-exec-value am-tone-{{ $readinessScore !== null && $readinessScore >= 70 ? 'ok' : 'warn' }}">{{ $readinessScore ?? '-' }}%</span>
                                        <span class="am-exec-label">{{ __('cv-aimatch.exec.ats') }}</span>
                                    </div>
                                    <div class="am-exec-stat">
                                        <span class="am-exec-value am-tone-{{ $coverageScore !== null && $coverageScore >= 70 ? 'ok' : 'warn' }}">{{ $coverageScore ?? '-' }}%</span>
                                        <span class="am-exec-label">{{ __('cv-aimatch.exec.coverage') }}</span>
                                    </div>
                                    <div class="am-exec-stat">
                                        <span class="am-exec-value am-tone-neutral">{{ $density['pages'] ?? 1 }}</span>
                                        <span class="am-exec-label">{{ __('cv-aimatch.exec.density') }} @if($density)· {{ __('cv-aimatch.exec.words', ['count' => $density['words']]) }}@endif</span>
                                    </div>
                                </div>

                                {{-- 2. Priority Actionable Fixes --}}
                                <div class="am-card am-priority">
                                    <div class="am-card-head">
                                        <span class="am-ico"><x-heroicon-o-bolt class="w-4.5 h-4.5" /></span>
                                        <div>
                                            <h3 class="am-title">{{ __('cv-aimatch.exec.priority_title') }}</h3>
                                            <p class="am-hint">{{ __('cv-aimatch.exec.priority_hint') }}</p>
                                        </div>
                                    </div>
                                    @if($priorityFixes !== [])
                                        <div class="am-priority-list">
                                            @foreach($priorityFixes as $fix)
                                                <div class="am-priority-item">
                                                    <h5 class="am-subtitle">{{ $fix['title'] }}</h5>
                                                    <div class="am-ba">
                                                        <div class="am-ba-col am-ba-before">
                                                            <span class="am-ba-tag">{{ __('cv-aimatch.exec.before') }}</span>
                                                            <p>{{ $fix['before'] }}</p>
                                                        </div>
                                                        <div class="am-ba-col am-ba-after">
                                                            <span class="am-ba-tag">{{ __('cv-aimatch.exec.after') }}</span>
                                                            <p>{{ $fix['after'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="am-hint">{{ __('cv-aimatch.exec.no_priority') }}</p>
                                    @endif
                                </div>

                                {{-- 3. Deep-dive (existing sections) --}}
                                <details class="am-section" open>
                                    <summary class="am-section-head">
                                        <span class="am-section-title">
                                            <x-heroicon-o-chart-bar class="w-4.5 h-4.5" />
                                            <span>{{ __('cv-aimatch.exec.deep_dive') }}</span>
                                        </span>
                                        <span class="am-section-meta">{{ __('cv-aimatch.exec.deep_dive_hint') }}</span>
                                        <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                    </summary>
                                    <div class="am-section-body">
                                {{-- Ringkasan utama --}}
                                <div class="am-card am-summary">
                                    <div class="am-summary-top">
                                        <div class="am-hero-ring">
                                            <x-score-ring :value="$heroPct" :size="112" :stroke-width="6" center-class="text-lg" variant="muted" />
                                            <span class="am-ring-caption">{{ $jobMatchValue !== null ? __('cv-aimatch.summary.ring_job_match') : __('cv-aimatch.summary.ring_role_fitment') }}</span>
                                        </div>
                                        <div class="am-summary-body">
                                            <div class="am-summary-head">
                                                <h3 class="am-title-lg">{{ __('cv-aimatch.summary.match_for', ['role' => $analysisResult['job_title_targeted'] ?? $targetRole]) }}</h3>
                                                @if($bandLabel)
                                                    <span class="am-chip am-chip--{{ $bandTone }}">{{ $bandLabel }}</span>
                                                @endif
                                            </div>
                                            <p class="am-summary-tagline">{{ $roleProfile['tagline'] }}</p>
                                            <div class="am-tags">
                                                @if(!empty($analysisResult['target_seniority']))
                                                    <span class="am-chip am-chip--neutral">{{ __('cv-aimatch.summary.seniority', ['level' => $analysisResult['target_seniority']]) }}</span>
                                                @endif
                                                @if(is_numeric($heroDomain))
                                                    <span class="am-chip am-chip--neutral">{{ __('cv-aimatch.summary.domain', ['name' => strtoupper(str_replace('_', ' ', $roleProfile['domain'])), 'score' => (int) $heroDomain]) }}</span>
                                                @endif
                                                @if($jobMatchValue !== null)
                                                    <span class="am-chip am-chip--{{ $bandTone }}">{{ __('cv-aimatch.summary.job_match', ['score' => $jobMatchValue]) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($analysisResult['summary']))
                                        <p class="am-summary-text">{{ $analysisResult['summary'] }}</p>
                                    @endif

                                    <div class="am-quick-grid">
                                        @foreach($roleProfile['core'] as $coreKey)
                                            @php $coreValue = $analysisResult[$coreKey] ?? null; @endphp
                                            <div class="am-quick">
                                                <span class="am-quick-value am-tone-{{ is_numeric($coreValue) ? ((int) $coreValue >= 80 ? 'ok' : ((int) $coreValue >= 60 ? 'warn' : 'bad')) : 'neutral' }}">
                                                    {{ is_numeric($coreValue) ? (int) $coreValue.'%' : '-' }}
                                                </span>
                                                <span class="am-quick-label">{{ $getMetricLabel($coreKey) }}</span>
                                            </div>
                                        @endforeach
                                        <div class="am-quick">
                                            <span class="am-quick-value am-tone-neutral">{{ $analysisResult['ats_score'] ?? '-' }}%</span>
                                            <span class="am-quick-label">{{ __('cv-aimatch.metrics.items.ats_score.label') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Metrik lengkap --}}
                                <div class="am-card">
                                    <div class="am-card-head">
                                        <span class="am-ico"><x-heroicon-o-chart-bar class="w-4.5 h-4.5" /></span>
                                        <div>
                                            <h3 class="am-title">{{ __('cv-aimatch.metrics.title') }}</h3>
                                            <p class="am-hint">{{ __('cv-aimatch.metrics.hint') }}</p>
                                        </div>
                                    </div>
                                    <div class="am-metrics">
                                        @foreach($orderedMetrics as $metricKey)
                                            @php
                                                $metricLabel = $getMetricLabel($metricKey);
                                                $metricDesc = $getMetricDesc($metricKey);
                                                $isCore = in_array($metricKey, $roleProfile['core']);
                                                $metricValue = $analysisResult[$metricKey] ?? null;
                                            @endphp
                                            <div class="am-metric {{ $isCore ? 'is-core' : '' }}">
                                                <div class="am-metric-top">
                                                    <span class="am-metric-label">{{ $metricLabel }}</span>
                                                    @if($isCore)<span class="am-chip am-chip--accent am-chip--xs">{{ __('cv-aimatch.metrics.core_badge') }}</span>@endif
                                                </div>
                                                <div class="am-meter">
                                                    <div class="am-meter-fill am-tone-bg-{{ is_numeric($metricValue) ? ((int) $metricValue >= 80 ? 'ok' : ((int) $metricValue >= 60 ? 'warn' : 'bad')) : 'neutral' }}"
                                                         style="width: {{ is_numeric($metricValue) ? (int) max(0, min(100, $metricValue)) : 0 }}%"></div>
                                                </div>
                                                <div class="am-metric-bottom">
                                                    <span class="am-metric-desc">{{ $metricDesc }}</span>
                                                    <span class="am-metric-value">{{ is_numeric($metricValue) ? (int) $metricValue.'%' : '-' }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- A. Checklist requirement --}}
                                @if(is_array($detail) && $requirements !== [])
                                    <details class="am-section" open>
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-clipboard-document-check class="w-4.5 h-4.5" />
                                                <span>{{ __('cv-aimatch.requirements.title') }}</span>
                                            </span>
                                            <span class="am-section-meta">{{ __('cv-aimatch.requirements.count', ['count' => count($requirements)]) }}</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-coverage-grid">
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value am-tone-{{ $bandTone }}">{{ $detail['overall'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">{{ __('cv-aimatch.requirements.overall_score') }}</span>
                                                </div>
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value">{{ $detail['must_have_coverage'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">{{ __('cv-aimatch.requirements.must_have') }}</span>
                                                </div>
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value">{{ $detail['nice_to_have_coverage'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">{{ __('cv-aimatch.requirements.nice_to_have') }}</span>
                                                </div>
                                                <div class="am-coverage">
                                                    <span class="am-coverage-value">{{ $detail['evidence_quality'] ?? 0 }}%</span>
                                                    <span class="am-coverage-label">{{ __('cv-aimatch.requirements.evidence_quality') }}</span>
                                                </div>
                                            </div>

                                            @if(!empty($bandDefinition))
                                                <div class="am-callout am-callout--accent">
                                                    <strong>{{ $bandLabel ?? ($detail['band']['label'] ?? '') }}</strong>
                                                    <span>{{ $bandDefinition }}</span>
                                                </div>
                                            @endif

                                            @foreach([['title' => __('cv-aimatch.requirements.must_have'), 'items' => $mustHave], ['title' => __('cv-aimatch.requirements.nice_to_have'), 'items' => $niceToHave]] as $group)
                                                @if($group['items'] !== [])
                                                    <div class="am-req-group">
                                                        <h5 class="am-subtitle">{{ $group['title'] }} <span class="am-subtitle-count">{{ count($group['items']) }}</span></h5>
                                                        <div class="am-req-list">
                                                            @foreach($group['items'] as $req)
                                                                @php
                                                                    $status = $req['status'] ?? 'missing';
                                                                    $meta = $statusMeta[$status] ?? $statusMeta['missing'];
                                                                @endphp
                                                                <div class="am-req">
                                                                    <div class="am-req-head">
                                                                        <span class="am-req-name">{{ $req['requirement'] ?? '' }}</span>
                                                                        <span class="am-chip {{ $meta['class'] }} am-chip--xs">{{ $meta['label'] }}</span>
                                                                    </div>
                                                                    @if(!empty($req['evidence_quote']))
                                                                        <blockquote class="am-quote">{{ $req['evidence_quote'] }}</blockquote>
                                                                    @endif
                                                                    @if(!empty($req['reasoning']))
                                                                        <p class="am-req-reason">{{ $req['reasoning'] }}</p>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <details class="am-subsection">
                                                <summary class="am-subsection-head">
                                                    <x-heroicon-o-information-circle class="w-4 h-4" />
                                                    <span>{{ __('cv-aimatch.requirements.how_calculated') }}</span>
                                                    <x-heroicon-o-chevron-down class="am-chevron w-3.5 h-3.5" />
                                                </summary>
                                                <div class="am-subsection-body">
                                                    <p>{{ __('cv-aimatch.requirements.formula_overall') }}</p>
                                                    <p>{{ __('cv-aimatch.requirements.formula_status') }}</p>
                                                    <p>{{ __('cv-aimatch.requirements.formula_quality') }}</p>
                                                    <ul class="am-band-list">
                                                        <li><span class="am-tone-ok">{{ __('cv-aimatch.requirements.band_scale.strong') }}</span> {{ __('cv-aimatch.requirements.band_scale.strong_desc') }}</li>
                                                        <li><span class="am-tone-accent">{{ __('cv-aimatch.requirements.band_scale.good') }}</span> {{ __('cv-aimatch.requirements.band_scale.good_desc') }}</li>
                                                        <li><span class="am-tone-warn">{{ __('cv-aimatch.requirements.band_scale.partial') }}</span> {{ __('cv-aimatch.requirements.band_scale.partial_desc') }}</li>
                                                        <li><span class="am-tone-bad">{{ __('cv-aimatch.requirements.band_scale.weak') }}</span> {{ __('cv-aimatch.requirements.band_scale.weak_desc') }}</li>
                                                    </ul>
                                                    <p class="am-subsection-note">{{ __('cv-aimatch.requirements.formula_note') }}</p>
                                                </div>
                                            </details>
                                        </div>
                                    </details>
                                @endif

                                {{-- B. Audit kata kunci --}}
                                @if($foundKeywords !== [] || $weakKeywords !== [] || $missingKeywords !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-tag class="w-4.5 h-4.5" />
                                                <span>{{ __('cv-aimatch.keywords.title') }}</span>
                                            </span>
                                            <span class="am-section-meta">{{ __('cv-aimatch.keywords.meta', ['found' => count($foundKeywords), 'missing' => count($missingKeywords)]) }}</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-kw-grid">
                                                <div class="am-kw am-kw--ok">
                                                    <h5 class="am-kw-title"><x-heroicon-o-check-circle class="w-4 h-4" /> {{ __('cv-aimatch.keywords.found', ['count' => count($foundKeywords)]) }}</h5>
                                                    <div class="am-chip-wrap">
                                                        @forelse($foundKeywords as $kw)
                                                            <span class="am-chip am-chip--ok am-chip--xs">{{ $kw }}</span>
                                                        @empty
                                                            <span class="am-empty-note">{{ __('cv-aimatch.keywords.empty_none') }}</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="am-kw am-kw--warn">
                                                    <h5 class="am-kw-title"><x-heroicon-o-exclamation-triangle class="w-4 h-4" /> {{ __('cv-aimatch.keywords.weak', ['count' => count($weakKeywords)]) }}</h5>
                                                    <div class="am-chip-wrap">
                                                        @forelse($weakKeywords as $kw)
                                                            @php
                                                                $weakLabel = is_array($kw) ? (string) ($kw['keyword'] ?? $kw['reason'] ?? '') : (string) $kw;
                                                                $weakReason = is_array($kw) ? (string) ($kw['reason'] ?? '') : '';
                                                            @endphp
                                                            @if(trim($weakLabel) !== '')
                                                                <span class="am-chip am-chip--warn am-chip--xs" @if(trim($weakReason) !== '') title="{{ $weakReason }}" @endif>{{ $weakLabel }}</span>
                                                            @endif
                                                        @empty
                                                            <span class="am-empty-note">{{ __('cv-aimatch.keywords.empty_none') }}</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="am-kw am-kw--bad">
                                                    <h5 class="am-kw-title"><x-heroicon-o-x-circle class="w-4 h-4" /> {{ __('cv-aimatch.keywords.missing', ['count' => count($missingKeywords)]) }}</h5>
                                                    <div class="am-chip-wrap">
                                                        @forelse($missingKeywords as $kw)
                                                            <span class="am-chip am-chip--bad am-chip--xs">{{ $kw }}</span>
                                                        @empty
                                                            <span class="am-empty-note">{{ __('cv-aimatch.keywords.all_covered') }}</span>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                @endif

                                {{-- C. Fokus perbaikan --}}
                                @if($focusAreas->isNotEmpty() || $suggestions !== [] || $rewritePriorities !== [])
                                    <details class="am-section" open>
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-wrench-screwdriver class="w-4.5 h-4.5" />
                                                <span>{{ __('cv-aimatch.improvements.title') }}</span>
                                            </span>
                                            <span class="am-section-meta">{{ __('cv-aimatch.improvements.meta', ['count' => $focusAreas->count() + count($suggestions)]) }}</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            @if($focusAreas->isNotEmpty())
                                                <div class="am-focus">
                                                    <h5 class="am-subtitle">{{ __('cv-aimatch.improvements.lowest_scoring') }}</h5>
                                                    <div class="am-chip-wrap">
                                                        @foreach($focusAreas as $area)
                                                            <span class="am-chip am-chip--warn">
                                                                <span class="am-chip-strong">{{ (int) $area['value'] }}%</span>
                                                                {{ $area['label'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if($suggestions !== [])
                                                <div>
                                                    <h5 class="am-subtitle">{{ __('cv-aimatch.improvements.action_steps') }}</h5>
                                                    <ol class="am-numbered">
                                                        @foreach($suggestions as $idx => $suggestion)
                                                            <li>
                                                                <span class="am-numbered-idx">{{ $idx + 1 }}</span>
                                                                <span class="am-numbered-text">{{ $suggestion }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                </div>
                                            @endif

                                            @if($rewritePriorities !== [])
                                                <div>
                                                    <h5 class="am-subtitle">{{ __('cv-aimatch.improvements.rewrite_priorities') }}</h5>
                                                    <ul class="am-bullets">
                                                        @foreach($rewritePriorities as $priority)
                                                            <li>{{ $priority }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif

                                {{-- D. Kekuatan & celah --}}
                                @if($strengths !== [] || $criticalGaps !== [] || $missingMetrics !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-scale class="w-4.5 h-4.5" />
                                                <span>{{ __('cv-aimatch.strengths_gaps.title') }}</span>
                                            </span>
                                            <span class="am-section-meta">{{ __('cv-aimatch.strengths_gaps.meta', ['strengths' => count($strengths), 'gaps' => count($criticalGaps)]) }}</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-two-col">
                                                @if($strengths !== [])
                                                    <div class="am-callout am-callout--ok">
                                                        <h5 class="am-subtitle am-tone-ok">{{ __('cv-aimatch.strengths_gaps.professional_strengths') }}</h5>
                                                        <ul class="am-bullets">
                                                            @foreach($strengths as $strength)
                                                                <li>{{ $strength }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                @if($criticalGaps !== [])
                                                    <div class="am-callout am-callout--bad">
                                                        <h5 class="am-subtitle am-tone-bad">{{ __('cv-aimatch.strengths_gaps.critical_gaps') }}</h5>
                                                        <ul class="am-bullets">
                                                            @foreach($criticalGaps as $gap)
                                                                <li>{{ $gap }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($missingMetrics !== [])
                                                <div class="am-callout am-callout--warn">
                                                    <h5 class="am-subtitle am-tone-warn">{{ __('cv-aimatch.strengths_gaps.missing_metrics') }}</h5>
                                                    <ul class="am-bullets">
                                                        @foreach($missingMetrics as $metric)
                                                            <li>{{ $metric }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif

                                {{-- E. Review pengalaman --}}
                                @if($experienceReview !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-clipboard-document-check class="w-4.5 h-4.5" />
                                                <span>{{ __('cv-aimatch.experience.title') }}</span>
                                            </span>
                                            <span class="am-section-meta">{{ __('cv-aimatch.experience.meta', ['count' => count($experienceReview)]) }}</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-review-list">
                                                @foreach($experienceReview as $review)
                                                    @if(is_string($review))
                                                        <div class="am-review-note">
                                                            <x-heroicon-o-information-circle class="w-4 h-4" />
                                                            <p>{{ $review }}</p>
                                                        </div>
                                                    @elseif(is_array($review))
                                                        @php
                                                            $reviewSection = trim((string) ($review['section'] ?? ''));
                                                            $reviewStrength = trim((string) ($review['strength'] ?? ''));
                                                            $reviewGap = trim((string) ($review['gap'] ?? ''));
                                                            $reviewHint = trim((string) ($review['rewrite_hint'] ?? ''));
                                                        @endphp
                                                        <div class="am-review">
                                                            @if($reviewSection !== '')
                                                                <h5 class="am-review-head">{{ $reviewSection }}</h5>
                                                            @endif
                                                            @if($reviewStrength !== '')
                                                                <div class="am-review-row">
                                                                    <span class="am-review-tag am-tone-ok">{{ __('cv-aimatch.experience.strength_tag') }}</span>
                                                                    <p>{{ $reviewStrength }}</p>
                                                                </div>
                                                            @endif
                                                            @if($reviewGap !== '')
                                                                <div class="am-review-row">
                                                                    <span class="am-review-tag am-tone-bad">{{ __('cv-aimatch.experience.gap_tag') }}</span>
                                                                    <p>{{ $reviewGap }}</p>
                                                                </div>
                                                            @endif
                                                            @if($reviewHint !== '')
                                                                <div class="am-review-row am-review-row--hint">
                                                                    <span class="am-review-tag am-tone-accent">{{ __('cv-aimatch.experience.rewrite_hint_tag') }}</span>
                                                                    <p>{{ $reviewHint }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                @endif

                                {{-- F. Detail domain --}}
                                @if($industryMatch !== [])
                                    <details class="am-section">
                                        <summary class="am-section-head">
                                            <span class="am-section-title">
                                                <x-heroicon-o-globe-alt class="w-4.5 h-4.5" />
                                                <span>{{ __('cv-aimatch.domains.title') }}</span>
                                            </span>
                                            <span class="am-section-meta">{{ __('cv-aimatch.domains.meta', ['count' => count($industryMatch)]) }}</span>
                                            <x-heroicon-o-chevron-down class="am-chevron w-4 h-4" />
                                        </summary>
                                        <div class="am-section-body">
                                            <div class="am-domain-grid">
                                                @foreach($industryMatch as $domainKey => $score)
                                                    @php $isTargetDomain = $domainKey === $roleProfile['domain']; @endphp
                                                    <div class="am-domain {{ $isTargetDomain ? 'is-target' : '' }}">
                                                        <span class="am-domain-name">
                                                            {{ strtoupper(str_replace('_', ' ', $domainKey)) }}
                                                            @if($isTargetDomain)<span class="am-chip am-chip--accent am-chip--xs">{{ __('cv-aimatch.domains.target_badge') }}</span>@endif
                                                        </span>
                                                        <span class="am-domain-value">{{ $score }}%</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                @endif
                                    </div>
                                </details>
                            </div>
                        @elseif($isAnalyzing)
                            <div class="am-card am-skeleton">
                                <div class="am-skel am-skel--title"></div>
                                <div class="am-skel-grid">
                                    @for($i = 0; $i < 4; $i++)
                                        <div class="am-skel am-skel--stat"></div>
                                    @endfor
                                </div>
                                <div class="am-skel am-skel--line"></div>
                                <div class="am-skel am-skel--line am-skel--short"></div>
                            </div>
                        @else
                            <div class="am-card am-empty">
                                <span class="am-empty-ico"><x-heroicon-o-sparkles class="w-6 h-6" /></span>
                                <h4 class="am-title-lg">{{ __('cv-aimatch.empty_state.title') }}</h4>
                                <p class="am-hint am-empty-hint">{{ __('cv-aimatch.empty_state.hint') }}</p>
                                <x-filament::button wire:click="analyzeJobMatch" color="primary">
                                    <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5" />
                                    <span>{{ __('cv-aimatch.empty_state.run_analysis') }}</span>
                                </x-filament::button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
