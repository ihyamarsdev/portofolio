<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\Project;

/**
 * Deterministic, evidence-based job match engine.
 *
 * Objective-scoring principles applied here:
 * - BARS (Behaviorally Anchored Rating Scales): every score band is anchored to
 *   observable evidence, never to free-form judgment.
 * - STAR: a requirement is only "verified" when the CV contains a measurable
 *   result (metrics), not just a mention.
 * - Reproducibility: all numbers are computed in PHP from fixed, versioned
 *   formulas. The AI never produces match scores — it only classifies evidence
 *   and this service validates those classifications against the CV text.
 */
class JobMatchService
{
    /**
     * Version of the score formula. Bump when weights/points change so stored
     * analyses remain auditable against the formula that produced them.
     */
    public const FORMULA_VERSION = 'v2-evidence-based';

    public const WEIGHTS = [
        'must_have' => 0.6,
        'nice_to_have' => 0.2,
        'evidence_quality' => 0.2,
    ];

    public const STATUS_POINTS = [
        'verified' => 1.0,
        'claimed' => 0.4,
        'missing' => 0.0,
    ];

    public const BANDS = [
        'strong' => [
            'min' => 85,
            'label' => 'Strong Match',
            'definition' => 'Majority of must-have requirements are verified with quantified evidence (metrics, scale, outcomes).',
        ],
        'good' => [
            'min' => 70,
            'label' => 'Good Match',
            'definition' => 'Must-have requirements are covered, but several are only claimed without measurable proof.',
        ],
        'partial' => [
            'min' => 50,
            'label' => 'Partial Match',
            'definition' => 'Noticeable gaps in must-have requirements, or evidence is predominantly weak (claims without metrics).',
        ],
        'weak' => [
            'min' => 0,
            'label' => 'Weak Match',
            'definition' => 'Critical must-have requirements are missing from the CV.',
        ],
    ];

    /**
     * Common alias expansions so exact keyword matching does not miss
     * equivalent terminology (e.g. K8s = Kubernetes).
     */
    protected array $aliasMap = [
        'k8s' => ['kubernetes'],
        'kubernetes' => ['k8s'],
        'aws' => ['amazon web services'],
        'amazon web services' => ['aws'],
        'gcp' => ['google cloud'],
        'google cloud' => ['gcp'],
        'iac' => ['infrastructure as code', 'infrastructure-as-code'],
        'infrastructure as code' => ['iac', 'terraform'],
        'ci/cd' => ['ci cd', 'cicd', 'continuous integration', 'continuous delivery'],
        'cicd' => ['ci/cd', 'ci cd'],
        'ci cd' => ['ci/cd', 'cicd'],
        'sre' => ['site reliability'],
        'site reliability' => ['sre'],
        'observability' => ['monitoring'],
        'monitoring' => ['observability'],
        'terraform' => ['iac', 'infrastructure as code'],
        'ansible' => ['configuration management'],
        'container' => ['containers', 'docker'],
        'docker' => ['containers'],
        'postgresql' => ['postgres'],
        'postgres' => ['postgresql'],
        'gitops' => ['argocd', 'flux'],
        'github actions' => ['github', 'ci/cd'],
        'jenkins' => ['ci/cd'],
        'linux' => ['unix'],
    ];

    /**
     * Terms too generic to be useful for matching a requirement.
     */
    protected array $stopWords = [
        'the', 'and', 'for', 'with', 'a', 'an', 'of', 'to', 'in', 'on', 'or',
        'at', 'is', 'are', 'be', 'as', 'by', 'from', 'that', 'this', 'it',
        'experience', 'experienced', 'years', 'year', 'strong', 'good',
        'knowledge', 'understanding', 'familiarity', 'ability', 'skills',
        'skill', 'plus', 'proficiency', 'proficient', 'working', 'using',
        'role', 'work', 'candidate', 'will', 'you', 'your', 'our', 'we',
        'must', 'should', 'have', 'has', 'had', 'any', 'other', 'etc',
        'requirements', 'required', 'preferred', 'responsibilities', 'bonus',
        'nice', 'minimum', 'least', 'than', 'more', 'new', 'existing',
    ];

    public function __construct(protected KeywordService $keywordService) {}

    /**
     * Deterministic fallback requirement extraction (used when the LLM
     * extraction agent fails or returns nothing). Requirements are taken from
     * reference keywords found in the job description itself.
     */
    public function extractRequirementsDeterministic(string $jobDescription, string $targetRole = ''): array
    {
        $combined = $this->keywordService->getCombinedKeywords();
        $matched = $this->keywordService->matchKeywords($jobDescription, $combined);

        $requirements = [];
        foreach (array_slice($matched['found'], 0, 10) as $keyword) {
            $requirements[] = [
                'requirement' => $keyword,
                'type' => 'must_have',
                'weight' => 3,
            ];
        }

        $senioritySignals = [];
        if (preg_match('/(\d+)\s*\+?\s*(?:to|-|–)\s*(\d+)\s*\+?\s*years?/i', $jobDescription, $m)) {
            $senioritySignals[] = $m[1].'-'.$m[2].' years of experience required';
        } elseif (preg_match('/(\d+)\s*\+?\s*years?/i', $jobDescription, $m)) {
            $senioritySignals[] = $m[1].'+ years of experience required';
        }

        return [
            'requirements' => $requirements,
            'seniority_signals' => $senioritySignals,
            'inferred_title' => $targetRole !== '' ? $targetRole : 'Unknown',
            'inferred_seniority' => $this->inferSeniorityFromYears($senioritySignals),
            'source' => 'deterministic',
        ];
    }

    /**
     * Match each requirement against the CV content and classify the evidence
     * level deterministically (verified / claimed / missing).
     *
     * @return array<int, array{requirement: string, type: string, weight: int, status: string, evidence_quote: string, reasoning: string}>
     */
    public function matchRequirements(array $requirements, string $cvContent): array
    {
        $lines = $this->splitIntoEvidenceLines($cvContent);

        $matches = [];
        foreach ($requirements as $requirement) {
            $text = trim((string) ($requirement['requirement'] ?? ''));

            if ($text === '') {
                continue;
            }

            $type = in_array($requirement['type'] ?? '', ['must_have', 'nice_to_have'], true)
                ? $requirement['type']
                : 'must_have';
            $weight = max(1, min(5, (int) ($requirement['weight'] ?? 3)));

            $match = $this->findBestEvidenceLine($text, $lines);

            if ($match === null) {
                $matches[] = [
                    'requirement' => $text,
                    'type' => $type,
                    'weight' => $weight,
                    'status' => 'missing',
                    'evidence_quote' => '',
                    'reasoning' => 'No matching evidence found in the CV.',
                ];

                continue;
            }

            [$line, $coverage] = $match;
            $quantified = $this->hasQuantifiedEvidence($line);

            $matches[] = [
                'requirement' => $text,
                'type' => $type,
                'weight' => $weight,
                'status' => $quantified ? 'verified' : 'claimed',
                'evidence_quote' => $this->truncateQuote($line),
                'reasoning' => sprintf(
                    'Matched %.0f%% of requirement terms in the CV (%s quantified evidence).',
                    $coverage * 100,
                    $quantified ? 'with' : 'without'
                ),
            ];
        }

        return $matches;
    }

    /**
     * Merge AI verification results into the deterministic preliminary matches.
     *
     * Anti-hallucination guardrails:
     * - Unknown statuses are rejected (preliminary status is kept).
     * - A "verified" status is downgraded to "claimed" when the AI-supplied
     *   evidence quote cannot be found in the actual CV content.
     */
    public function reconcile(array $preliminary, array $verification, string $cvContent): array
    {
        $verificationByKey = [];
        foreach ($verification as $item) {
            $key = $this->normalize($item['requirement'] ?? '');
            if ($key !== '') {
                $verificationByKey[$key] = $item;
            }
        }

        $reconciled = [];
        foreach ($preliminary as $match) {
            $key = $this->normalize($match['requirement'] ?? '');
            $aiItem = $verificationByKey[$key] ?? null;
            unset($verificationByKey[$key]);

            if ($aiItem === null) {
                $reconciled[] = $match;

                continue;
            }

            $status = $aiItem['status'] ?? '';
            if (! in_array($status, ['verified', 'claimed', 'missing'], true)) {
                $reconciled[] = $match;

                continue;
            }

            $quote = trim((string) ($aiItem['evidence_quote'] ?? ''));
            $reasoning = trim((string) ($aiItem['reasoning'] ?? ''));

            // A verified status must carry a quote that actually exists in the CV.
            if ($status === 'verified' && ($quote === '' || ! $this->quoteExistsInCv($quote, $cvContent))) {
                $status = 'claimed';
                $reasoning = trim('Evidence quote not verifiable in CV; downgraded to claimed. '.$reasoning);
            }

            if ($status === 'missing') {
                $quote = '';
            }

            $reconciled[] = [
                'requirement' => $match['requirement'],
                'type' => $match['type'],
                'weight' => $match['weight'],
                'status' => $status,
                'evidence_quote' => $this->truncateQuote($quote),
                'reasoning' => $reasoning !== '' ? $reasoning : ($match['reasoning'] ?? ''),
            ];
        }

        // Append valid AI-identified requirements that were not in the
        // preliminary set (extraction may have surfaced extra requirements).
        foreach ($verificationByKey as $item) {
            $text = trim((string) ($item['requirement'] ?? ''));
            $status = $item['status'] ?? '';

            if ($text === '' || ! in_array($status, ['verified', 'claimed', 'missing'], true)) {
                continue;
            }

            $quote = trim((string) ($item['evidence_quote'] ?? ''));
            if ($status === 'verified' && ($quote === '' || ! $this->quoteExistsInCv($quote, $cvContent))) {
                $status = 'claimed';
            }

            $reconciled[] = [
                'requirement' => $text,
                'type' => 'must_have',
                'weight' => 3,
                'status' => $status,
                'evidence_quote' => $status === 'missing' ? '' : $this->truncateQuote($quote),
                'reasoning' => trim((string) ($item['reasoning'] ?? '')),
            ];
        }

        return $reconciled;
    }

    /**
     * Compute the final objective score from requirement statuses.
     *
     * overall = (w_must_have + w_nice_to_have) * weighted_coverage
     *         + w_evidence_quality * evidence_quality
     */
    public function computeScore(array $matches): array
    {
        $groups = ['must_have' => [], 'nice_to_have' => []];

        $counts = ['verified' => 0, 'claimed' => 0, 'missing' => 0];
        $totalWeight = 0;
        $weightedPoints = 0.0;

        foreach ($matches as $match) {
            $status = in_array($match['status'] ?? '', ['verified', 'claimed', 'missing'], true)
                ? $match['status']
                : 'missing';

            $counts[$status]++;
            $weight = max(1, min(5, (int) ($match['weight'] ?? 3)));
            $points = self::STATUS_POINTS[$status];

            $type = $match['type'] ?? 'must_have';
            $groups[$type][] = ['weight' => $weight, 'points' => $points];

            $totalWeight += $weight;
            $weightedPoints += $weight * $points;
        }

        $mustHave = $this->coverage($groups['must_have']);
        $niceToHave = $this->coverage($groups['nice_to_have']);

        $matchedCount = $counts['verified'] + $counts['claimed'];
        $evidenceQuality = $matchedCount > 0 ? $counts['verified'] / $matchedCount : 0.0;

        $overall = 0;
        if ($totalWeight > 0) {
            $weightedCoverage = $weightedPoints / $totalWeight;
            $overall = (int) round(100 * (
                (self::WEIGHTS['must_have'] + self::WEIGHTS['nice_to_have']) * $weightedCoverage
                + self::WEIGHTS['evidence_quality'] * $evidenceQuality
            ));
        }

        return [
            'formula_version' => self::FORMULA_VERSION,
            'overall' => $overall,
            'band' => $this->getScoreBand($overall),
            'must_have_coverage' => (int) round($mustHave * 100),
            'nice_to_have_coverage' => (int) round($niceToHave * 100),
            'evidence_quality' => (int) round($evidenceQuality * 100),
            'counts' => $counts,
            'requirements' => $matches,
            'evidence_tiers' => $this->classifyEvidenceTiers($matches),
            'rewrite_suggestions' => $this->buildRewriteSuggestions($matches),
        ];
    }

    /**
     * Classify matched requirements into the three HR-facing evidence tiers:
     * verified (backed by a record or a quantified bullet), transferable /
     * adjacent (implied by related work), and missing (no support at all).
     *
     * This is presentation-only: it does NOT alter the deterministic status or
     * score, so stored analyses and widgets stay compatible.
     *
     * @return array{verified: array<int,string>, adjacent: array<int,string>, missing: array<int,string>}
     */
    public function classifyEvidenceTiers(array $matches): array
    {
        $tiers = ['verified' => [], 'adjacent' => [], 'missing' => []];

        foreach ($matches as $match) {
            $requirement = trim((string) ($match['requirement'] ?? ''));
            if ($requirement === '') {
                continue;
            }

            $status = $match['status'] ?? 'missing';
            $reasoning = mb_strtolower((string) ($match['reasoning'] ?? ''));

            if ($status === 'verified') {
                $tiers['verified'][] = $requirement;
            } elseif ($status === 'missing') {
                $tiers['missing'][] = $requirement;
            } elseif (str_contains($reasoning, 'adjacent') || str_contains($reasoning, 'transferable')) {
                $tiers['adjacent'][] = $requirement;
            } else {
                // A "claimed" match is an unquantified mention — treat it as
                // transferable/adjacent evidence, never as verified.
                $tiers['adjacent'][] = $requirement;
            }
        }

        return $tiers;
    }

    /**
     * Build copy-ready Before -> After bullet rewrites for the most valuable
     * gaps. Only requirements with a real evidence quote can be rewritten —
     * missing requirements get a placeholder-based suggestion instead, so no
     * skill or result is ever fabricated.
     *
     * @return array<int, array{requirement: string, before: string, after: string}>
     */
    public function buildRewriteSuggestions(array $matches, int $limit = 5): array
    {
        $suggestions = [];

        foreach ($matches as $match) {
            if (count($suggestions) >= $limit) {
                break;
            }

            $requirement = trim((string) ($match['requirement'] ?? ''));
            if ($requirement === '') {
                continue;
            }

            $status = $match['status'] ?? 'missing';
            $quote = trim((string) ($match['evidence_quote'] ?? ''));

            if ($status === 'missing') {
                $suggestions[] = [
                    'requirement' => $requirement,
                    'before' => '(no evidence of '.$requirement.' in the CV)',
                    'after' => 'Add a truthful bullet showing where you used '.$requirement
                        .' and its outcome, e.g. "Used '.$requirement.' to [insert task] with [insert measurable result]". Only add this if it is factually true.',
                ];

                continue;
            }

            if ($quote === '') {
                continue;
            }

            $before = $quote;
            $after = $this->strengthenBullet($before, $requirement);

            if ($after === $before) {
                continue;
            }

            $suggestions[] = [
                'requirement' => $requirement,
                'before' => $before,
                'after' => $after,
            ];
        }

        return $suggestions;
    }

    /**
     * Build structured, actionable patch items from the reconciled matches.
     *
     * This is the deterministic foundation for the Review & Apply Studio:
     * - claimed requirements backed by a real CV quote become a
     *   rewrite_experience patch with before_text = the exact quote;
     * - missing requirements become advisory items with honest guidance
     *   (never a fabricated skill or metric);
     * - no target_id is guessed here — the apply engine hydrates IDs from the
     *   database using the requirement's evidence quote at read time.
     *
     * Stored in job_match_detail.actionable_suggestions. The legacy
     * `suggestions` string list and `rewrite_suggestions` remain untouched.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildActionablePatches(array $matches, int $limit = 6): array
    {
        $patches = [];

        foreach ($matches as $match) {
            if (count($patches) >= $limit) {
                break;
            }

            $requirement = trim((string) ($match['requirement'] ?? ''));
            if ($requirement === '') {
                continue;
            }

            $status = $match['status'] ?? 'missing';
            $quote = trim((string) ($match['evidence_quote'] ?? ''));
            $reasoning = trim((string) ($match['reasoning'] ?? ''));

            if ($status === 'missing') {
                $patches[] = [
                    'action_type' => 'advisory',
                    'target_id' => null,
                    'target_label' => $requirement,
                    'impact' => 'high',
                    'rationale' => $reasoning !== '' ? $reasoning : 'No evidence of '.$requirement.' in the CV.',
                    'before_text' => '',
                    'suggested_text' => 'If you have hands-on experience with '.$requirement
                        .', add a truthful bullet describing where you used it and the outcome. Never list a skill you cannot back up.',
                ];

                continue;
            }

            if ($quote === '') {
                continue;
            }

            $after = $this->strengthenBullet($quote, $requirement);
            if ($after === $quote) {
                continue;
            }

            $patches[] = [
                'action_type' => 'rewrite_experience',
                'target_id' => null, // hydrated from DB by evidence quote
                'target_label' => '',
                'impact' => $status === 'verified' ? 'medium' : 'high',
                'rationale' => $reasoning !== '' ? $reasoning : 'Strengthen the evidence for '.$requirement.'.',
                'before_text' => $quote,
                'suggested_text' => $after,
            ];
        }

        return $patches;
    }

    /**
     * Resolve a patch's target_id from its before_text (an exact CV quote)
     * by searching Experience / Project database records. Returns the first
     * matching model's id and label, or nulls when no record owns the text.
     *
     * @return array{0: int|null, 1: string, 2: string|null} [id, label, action]
     */
    public function resolveTargetFromQuote(string $quote, string $locale = 'en'): array
    {
        $needle = mb_strtolower(trim($quote));
        if ($needle === '') {
            return [null, '', null];
        }

        foreach (Experience::all() as $exp) {
            $haystacks = [
                $this->localeText($exp, 'description', $locale),
                implode(' ', is_array($exp->achievements) ? $exp->achievements : []),
            ];
            foreach ($haystacks as $haystack) {
                if ($haystack !== '' && str_contains(mb_strtolower($haystack), $needle)) {
                    $label = trim($exp->getTranslation('position', $locale).' @ '.$exp->getTranslation('company', $locale));

                    return [$exp->id, $label, 'rewrite_experience'];
                }
            }
        }

        foreach (Project::all() as $project) {
            $haystack = $this->localeText($project, 'description', $locale);
            if ($haystack !== '' && str_contains(mb_strtolower($haystack), $needle)) {
                return [$project->id, 'Project: '.$project->getTranslation('title', $locale), 'tailor_project'];
            }
        }

        return [null, '', null];
    }

    protected function localeText($model, string $field, string $locale): string
    {
        if (! method_exists($model, 'getTranslation')) {
            return (string) ($model->{$field} ?? '');
        }

        $value = $model->getTranslation($field, $locale, true);

        return is_string($value) ? $value : '';
    }

    /**
     * Deterministically strengthen a duty-style bullet into an outcome-first
     * XYZ-style statement. Never invents a metric — absent numbers become
     * explicit [insert ...] placeholders the candidate must fill truthfully.
     */
    protected function strengthenBullet(string $bullet, string $requirement): string
    {
        $text = trim(preg_replace('/^\s*[-•*+\d.]+\s+/', '', $bullet) ?? $bullet);
        $lower = mb_strtolower($text);

        $dutyPhrases = [
            'responsible for',
            'tasked with',
            'worked on',
            'helped with',
            'involved in',
            'worked with',
        ];

        $isDuty = false;
        foreach ($dutyPhrases as $phrase) {
            if (str_contains($lower, $phrase)) {
                $isDuty = true;
                $text = preg_replace('/'.preg_quote($phrase, '/').'/i', 'Delivered', $text) ?? $text;

                break;
            }
        }

        $hasMetric = (bool) preg_match('/\d/', $text);

        if (! $hasMetric) {
            $text = rtrim($text, '. ').' — measured by [insert result/metric, e.g. latency, uptime, or time saved].';
        } elseif (! $isDuty) {
            // Already quantified and action-led: sharpen the leading verb only.
            $text = preg_replace('/^Used\b/i', 'Delivered with', $text) ?? $text;
        }

        return $text;
    }

    /**
     * Get the anchored band definition for a score (BARS-style).
     */
    public function getScoreBand(int $score): array
    {
        foreach (self::BANDS as $band) {
            if ($score >= $band['min']) {
                return [
                    'label' => $band['label'],
                    'definition' => $band['definition'],
                ];
            }
        }

        return [
            'label' => self::BANDS['weak']['label'],
            'definition' => self::BANDS['weak']['definition'],
        ];
    }

    /**
     * Weighted coverage for a group of requirements.
     */
    protected function coverage(array $items): float
    {
        $totalWeight = array_sum(array_column($items, 'weight'));

        if ($totalWeight === 0) {
            return 0.0;
        }

        $points = 0.0;
        foreach ($items as $item) {
            $points += $item['weight'] * $item['points'];
        }

        return $points / $totalWeight;
    }

    /**
     * Find the CV line that best matches a requirement.
     *
     * @return array{0: string, 1: float}|null [line, coverage]
     */
    protected function findBestEvidenceLine(string $requirement, array $lines): ?array
    {
        $terms = $this->extractTerms($requirement);

        if ($terms === []) {
            return null;
        }

        $bestLine = null;
        $bestCoverage = 0.0;

        foreach ($lines as $line) {
            $haystack = ' '.$this->normalize($line).' ';
            $matchedTerms = 0;

            foreach ($terms as $variants) {
                foreach ($variants as $term) {
                    if ($this->termExists($term, $haystack)) {
                        $matchedTerms++;

                        break;
                    }
                }
            }

            $coverage = $matchedTerms / count($terms);

            if ($coverage > $bestCoverage) {
                $bestCoverage = $coverage;
                $bestLine = $line;
            }
        }

        if ($bestLine === null || $bestCoverage < 0.34) {
            return null;
        }

        return [$bestLine, $bestCoverage];
    }

    /**
     * Extract salient term groups from a requirement. Each group holds a
     * primary term plus its alias variants (e.g. K8s = Kubernetes) so aliases
     * never inflate the coverage denominator.
     *
     * @return array<int, array<int, string>>
     */
    protected function extractTerms(string $requirement): array
    {
        $normalized = $this->normalize($requirement);
        $raw = preg_split('/[^a-z0-9+.\/]+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $groups = [];
        foreach ($raw as $token) {
            if (in_array($token, $this->stopWords, true) || mb_strlen($token) < 2) {
                continue;
            }

            $variants = [$token];

            foreach ($this->aliasMap[$token] ?? [] as $alias) {
                $variants[] = $this->normalize($alias);
            }

            $groups[] = array_values(array_unique($variants));
        }

        return $groups;
    }

    /**
     * Check whether a term (or its singular/plural variant) exists in a
     * normalized haystack, respecting alphanumeric word boundaries so
     * punctuation does not break matches.
     */
    protected function termExists(string $term, string $haystack): bool
    {
        $pattern = '/(?<![a-z0-9])'.preg_quote($term, '/').'(?![a-z0-9])/';

        if (preg_match($pattern, $haystack)) {
            return true;
        }

        // Naive singular/plural handling.
        if (str_ends_with($term, 's')) {
            return (bool) preg_match('/(?<![a-z0-9])'.preg_quote(substr($term, 0, -1), '/').'(?![a-z0-9])/', $haystack);
        }

        return (bool) preg_match('/(?<![a-z0-9])'.preg_quote($term, '/').'s?(?![a-z0-9])/', $haystack);
    }

    /**
     * Detect quantified evidence in a CV line (STAR "Result" check):
     * numbers with units, percentages, scale, or metric acronyms.
     */
    protected function hasQuantifiedEvidence(string $line): bool
    {
        $normalized = $this->normalize($line);

        if (preg_match('/\d+(?:[.,]\d+)?\s*(?:%|percent|x\b|kb|mb|gb|tb|pb|ms|vcpu|nodes?|clusters?|servers?|instances?|users?|requests?|rps|qps)/i', $normalized)) {
            return true;
        }

        if (preg_match('/\b(?:mttr|mttd|mtbf|sla|slo|sli|rto|rpo|uptime|availability|latency|throughput)\b/', $normalized)
            && preg_match('/\d+/', $normalized)) {
            return true;
        }

        // Improvement verbs followed by a number, or a number followed by an
        // improvement verb (e.g. "reduced deployment time by 40%").
        if (preg_match('/(?:reduc|increas|improv|sav|cut|grew|scal|migrat|automat|provision|deploy|manag|support)[a-z]*\b[^.]{0,80}?\d+/', $normalized)
            || preg_match('/\d+[^.]{0,80}?(?:reduc|increas|improv|sav)/', $normalized)) {
            return true;
        }

        return false;
    }

    /**
     * Split CV content into evidence lines (bullets/sentences).
     *
     * @return array<int, string>
     */
    protected function splitIntoEvidenceLines(string $cvContent): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $cvContent) ?: [];
        $evidence = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || mb_strlen($line) < 8) {
                continue;
            }

            // Split long lines into sentences to keep quotes precise.
            if (mb_strlen($line) > 240) {
                foreach (preg_split('/(?<=[.!?;])\s+/', $line) ?: [$line] as $sentence) {
                    $sentence = trim($sentence);
                    if ($sentence !== '') {
                        $evidence[] = $sentence;
                    }
                }

                continue;
            }

            $evidence[] = $line;
        }

        return $evidence;
    }

    /**
     * Validate that an evidence quote actually exists in the CV content
     * (anti-hallucination check for AI-supplied quotes).
     */
    protected function quoteExistsInCv(string $quote, string $cvContent): bool
    {
        $needle = preg_replace('/\s+/', ' ', $this->normalize($quote)) ?? '';
        $haystack = preg_replace('/\s+/', ' ', $this->normalize($cvContent)) ?? '';

        if ($needle === '') {
            return false;
        }

        if (str_contains($haystack, $needle)) {
            return true;
        }

        // Fall back to checking the leading 6-word window of the quote.
        $words = preg_split('/\s+/', $needle) ?: [];
        if (count($words) > 6) {
            $window = implode(' ', array_slice($words, 0, 6));

            return str_contains($haystack, $window);
        }

        return false;
    }

    protected function truncateQuote(string $quote): string
    {
        $quote = trim(preg_replace('/\s+/', ' ', $quote) ?? $quote);

        return mb_strlen($quote) > 300 ? mb_substr($quote, 0, 297).'...' : $quote;
    }

    protected function normalize(string $value): string
    {
        return mb_strtolower(trim($value));
    }

    protected function inferSeniorityFromYears(array $senioritySignals): string
    {
        foreach ($senioritySignals as $signal) {
            if (preg_match('/(\d+)/', $signal, $m)) {
                return match (true) {
                    (int) $m[1] >= 7 => 'Senior',
                    (int) $m[1] >= 4 => 'Mid-Senior',
                    default => 'Mid',
                };
            }
        }

        return 'Not Clear';
    }
}
