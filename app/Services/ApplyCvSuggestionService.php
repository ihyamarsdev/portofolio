<?php

namespace App\Services;

use App\Models\Category;
use App\Models\CVAnalysis;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Support\AiStructuredOutputSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Review & Apply engine for AI Match suggestions.
 *
 * Design invariants:
 * - The legacy `suggestions` (string list) and `job_match_detail` structures
 *   are never rewritten. Structured patches and apply state live in NEW keys
 *   inside the existing `job_match_detail` JSON column — no migration.
 * - Translatable fields (Profile.biography, Experience.description,
 *   Project.description) are updated via Spatie's setTranslation() so other
 *   locales survive. A full translations map is snapshotted for exact undo.
 * - target_id is always re-validated against the database before a write.
 * - Every mutation returns a result array with a translation key + params so
 *   the Livewire layer only emits notifications.
 */
class ApplyCvSuggestionService
{
    public const APPLICABLE_ACTIONS = ['update_summary', 'rewrite_experience', 'add_skill', 'tailor_project'];

    public function __construct(protected JobMatchService $jobMatchService) {}

    /**
     * Build the full, presentation-ready suggestion list for an analysis.
     * Hydrates before_text from the database (never trusting the AI's copy),
     * assigns stable IDs and attaches the persisted apply state.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildSuggestions(CVAnalysis $analysis, string $locale): array
    {
        $detail = is_array($analysis->job_match_detail) ? $analysis->job_match_detail : [];
        $raw = is_array($detail['actionable_suggestions'] ?? null) ? $detail['actionable_suggestions'] : [];
        $items = AiStructuredOutputSanitizer::normalizeActionableSuggestions($raw);

        // Legacy analyses have no structured patches. Map their existing
        // fields into advisory items so the studio never crashes or shows an
        // empty queue for old records.
        if ($items === []) {
            $items = $this->legacySuggestions($analysis);
        }

        $state = is_array($detail['apply_state'] ?? null) ? $detail['apply_state'] : [];
        $built = [];

        foreach ($items as $item) {
            $id = $this->makeId($item);
            $hydrated = $this->hydrate($item, $locale);
            $hydrated['id'] = $id;

            $entry = is_array($state[$id] ?? null) ? $state[$id] : [];
            $hydrated['state'] = $entry['status'] ?? 'pending';
            $hydrated['applied_content'] = (string) ($entry['applied_content'] ?? '');
            $hydrated['applied_locale'] = (string) ($entry['locale'] ?? '');

            $built[] = $hydrated;
        }

        return $built;
    }

    /**
     * Apply one suggestion. Returns ['ok' => bool, 'message' => key, 'params' => array].
     */
    public function apply(CVAnalysis $analysis, string $suggestionId, ?string $editedContent, string $locale): array
    {
        $item = $this->findItem($analysis, $suggestionId, $locale);

        if ($item === null) {
            return $this->failure('not_found');
        }

        if (($item['state'] ?? 'pending') === 'applied') {
            return $this->failure('already_applied');
        }

        if (! in_array($item['action_type'], self::APPLICABLE_ACTIONS, true)) {
            return $this->failure('advisory_only');
        }

        $content = trim($editedContent ?? $item['suggested_text'] ?? '');
        if ($content === '' && $item['action_type'] !== 'add_skill') {
            return $this->failure('empty_content');
        }

        // add_skill uses the skill name from the inline editor / suggestion.
        if ($item['action_type'] === 'add_skill') {
            return $this->applySkill($analysis, $item, $content, $locale);
        }

        [$model, $field, $translatable] = $this->resolveTarget($item);
        if ($model === null) {
            return $this->failure('invalid_target');
        }

        $snapshot = $translatable ? $model->getTranslations($field) : $model->{$field};

        DB::transaction(function () use ($model, $field, $translatable, $content, $locale): void {
            if ($translatable) {
                $model->setTranslation($field, $locale, $content);
            } else {
                $model->{$field} = $content;
            }
            $model->save();
        });

        $this->writeState($analysis, $suggestionId, [
            'status' => 'applied',
            'model' => get_class($model),
            'model_id' => $model->id,
            'field' => $field,
            'translatable' => $translatable,
            'snapshot' => $snapshot,
            'applied_content' => $content,
            'locale' => $locale,
            'applied_at' => now()->toIso8601String(),
        ]);

        return [
            'ok' => true,
            'message' => 'applied',
            'params' => ['target' => $item['target_label'] ?: $this->defaultLabel($item['action_type'])],
        ];
    }

    /**
     * Restore the exact pre-apply database state. For translatable fields the
     * full translations map is restored; for add_skill the created row is
     * deleted. On success the state entry is removed so the item is pending
     * again and can be re-applied.
     */
    public function undo(CVAnalysis $analysis, string $suggestionId, string $locale): array
    {
        $detail = is_array($analysis->job_match_detail) ? $analysis->job_match_detail : [];
        $state = is_array($detail['apply_state'] ?? null) ? $detail['apply_state'] : [];
        $entry = is_array($state[$suggestionId] ?? null) ? $state[$suggestionId] : null;

        if ($entry === null || ($entry['status'] ?? '') !== 'applied') {
            return $this->failure('not_applied');
        }

        $modelClass = (string) ($entry['model'] ?? '');
        $modelId = (int) ($entry['model_id'] ?? 0);
        $field = (string) ($entry['field'] ?? '');
        $translatable = (bool) ($entry['translatable'] ?? false);

        if ($modelClass === Skill::class && $modelId > 0) {
            // add_skill undo: remove exactly the row we created.
            Skill::where('id', $modelId)->delete();
        } elseif ($modelClass !== '' && $modelId > 0 && $field !== '') {
            $model = $modelClass::find($modelId);
            if ($model === null) {
                return $this->failure('target_gone');
            }

            $snapshot = $entry['snapshot'] ?? null;
            DB::transaction(function () use ($model, $field, $translatable, $snapshot): void {
                if ($translatable && is_array($snapshot)) {
                    $model->setTranslations($field, $snapshot);
                } else {
                    $model->{$field} = $snapshot;
                }
                $model->save();
            });
        } else {
            return $this->failure('invalid_snapshot');
        }

        unset($state[$suggestionId]);
        $this->writeState($analysis, $suggestionId, null, $state);

        return ['ok' => true, 'message' => 'undone', 'params' => []];
    }

    /**
     * Dismiss a suggestion without touching the CV. Reversible via re-apply.
     */
    public function dismiss(CVAnalysis $analysis, string $suggestionId, string $locale): array
    {
        $item = $this->findItem($analysis, $suggestionId, $locale);
        if ($item === null) {
            return $this->failure('not_found');
        }

        $this->writeState($analysis, $suggestionId, ['status' => 'dismissed']);

        return ['ok' => true, 'message' => 'dismissed', 'params' => []];
    }

    /**
     * Resolve the DB model + field for a patch item. target_id is validated
     * here — a stale or foreign ID yields [null, null, false].
     *
     * @return array{0: Model|null, 1: string|null, 2: bool}
     */
    protected function resolveTarget(array $item): array
    {
        return match ($item['action_type']) {
            'update_summary' => [$this->primaryProfile(), 'biography', true],
            'rewrite_experience' => [$this->validated(Experience::class, $item['target_id'] ?? null), 'description', true],
            'tailor_project' => [$this->validated(Project::class, $item['target_id'] ?? null), 'description', true],
            default => [null, null, false],
        };
    }

    protected function applySkill(CVAnalysis $analysis, array $item, string $content, string $locale): array
    {
        $name = $content !== '' ? $content : trim((string) ($item['suggested_text'] ?? ''));
        if ($name === '') {
            return $this->failure('empty_content');
        }

        // Case-insensitive duplicate prevention across all skills.
        $exists = Skill::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->exists();
        if ($exists) {
            return $this->failure('duplicate_skill', ['skill' => $name]);
        }

        $categoryId = Category::query()->orderBy('id')->value('id');

        $skill = DB::transaction(fn (): Skill => Skill::create([
            'name' => $name,
            'category_id' => $categoryId,
            'proficiency' => 3,
            'years_experience' => 0.0,
        ]));

        $this->writeState($analysis, $item['id'], [
            'status' => 'applied',
            'model' => Skill::class,
            'model_id' => $skill->id,
            'field' => 'name',
            'translatable' => false,
            'snapshot' => null,
            'applied_content' => $name,
            'locale' => $locale,
            'applied_at' => now()->toIso8601String(),
        ]);

        return ['ok' => true, 'message' => 'skill_added', 'params' => ['skill' => $name]];
    }

    /**
     * Hydrate DB truth into a normalized item: before_text, target_label and
     * a default label when the AI omitted one.
     */
    protected function hydrate(array $item, string $locale): array
    {
        $action = $item['action_type'];

        switch ($action) {
            case 'update_summary':
                $profile = $this->primaryProfile();
                $item['before_text'] = $profile ? $this->localeValue($profile, 'biography', $locale) : '';
                $item['target_label'] = $item['target_label'] ?: 'Professional Summary';
                break;

            case 'rewrite_experience':
                // When the AI omitted target_id, resolve it from the exact
                // evidence quote so the patch still lands on the right row.
                if (empty($item['target_id']) && trim((string) ($item['before_text'] ?? '')) !== '') {
                    [$resolvedId, $resolvedLabel, $resolvedAction] = $this->jobMatchService
                        ->resolveTargetFromQuote((string) $item['before_text'], $locale);
                    if ($resolvedId !== null && $resolvedAction === 'rewrite_experience') {
                        $item['target_id'] = $resolvedId;
                        $item['target_label'] = $item['target_label'] ?: $resolvedLabel;
                    }
                }
                $exp = $this->validated(Experience::class, $item['target_id'] ?? null);
                if ($exp) {
                    $item['before_text'] = $this->localeValue($exp, 'description', $locale);
                    $item['target_label'] = $item['target_label'] ?: trim($exp->getTranslation('position', $locale).' @ '.$exp->getTranslation('company', $locale));
                }
                break;

            case 'tailor_project':
                if (empty($item['target_id']) && trim((string) ($item['before_text'] ?? '')) !== '') {
                    [$resolvedId, $resolvedLabel, $resolvedAction] = $this->jobMatchService
                        ->resolveTargetFromQuote((string) $item['before_text'], $locale);
                    if ($resolvedId !== null && $resolvedAction === 'tailor_project') {
                        $item['target_id'] = $resolvedId;
                        $item['target_label'] = $item['target_label'] ?: $resolvedLabel;
                    }
                }
                $project = $this->validated(Project::class, $item['target_id'] ?? null);
                if ($project) {
                    $item['before_text'] = $this->localeValue($project, 'description', $locale);
                    $item['target_label'] = $item['target_label'] ?: 'Project: '.$project->getTranslation('title', $locale);
                }
                break;

            case 'add_skill':
                $item['before_text'] = '';
                $item['suggested_text'] = $item['suggested_text'] ?: $item['target_label'];
                $item['target_label'] = $item['target_label'] ?: 'Skill';
                break;
        }

        $item['target_id'] = isset($item['target_id']) ? (int) $item['target_id'] : null;
        $item['target_label'] = (string) $item['target_label'];
        $item['before_text'] = (string) $item['before_text'];
        $item['suggested_text'] = (string) $item['suggested_text'];

        return $item;
    }

    /**
     * Read a translatable field for the active locale, falling back to the
     * app fallback when that locale has no translation yet.
     */
    protected function localeValue($model, string $field, string $locale): string
    {
        if (! method_exists($model, 'getTranslation')) {
            return (string) ($model->{$field} ?? '');
        }

        $value = $model->getTranslation($field, $locale, true);

        return is_string($value) ? $value : '';
    }

    protected function findItem(CVAnalysis $analysis, string $id, string $locale): ?array
    {
        foreach ($this->buildSuggestions($analysis, $locale) as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }

        return null;
    }

    protected function primaryProfile(): ?Profile
    {
        return Profile::where('is_primary', true)->first() ?? Profile::first();
    }

    protected function validated(string $class, mixed $id)
    {
        if (! is_numeric($id) || (int) $id <= 0) {
            return null;
        }

        return $class::find((int) $id);
    }

    /**
     * Stable ID: identical input always yields the same key across reloads,
     * but different patches never collide in practice.
     */
    protected function makeId(array $item): string
    {
        $seed = implode('|', [
            $item['action_type'] ?? '',
            (string) ($item['target_id'] ?? ''),
            $item['rationale'] ?? '',
            $item['suggested_text'] ?? '',
        ]);

        return substr(hash('sha256', $seed), 0, 16);
    }

    /**
     * Map legacy string suggestions / rewrite pairs into advisory items.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function legacySuggestions(CVAnalysis $analysis): array
    {
        $detail = is_array($analysis->job_match_detail) ? $analysis->job_match_detail : [];
        $items = [];

        foreach (is_array($detail['rewrite_suggestions'] ?? null) ? $detail['rewrite_suggestions'] : [] as $rw) {
            if (! is_array($rw) || trim((string) ($rw['after'] ?? '')) === '') {
                continue;
            }
            $items[] = [
                'action_type' => 'advisory',
                'target_id' => null,
                'target_label' => (string) ($rw['requirement'] ?? ''),
                'impact' => 'medium',
                'rationale' => (string) ($rw['requirement'] ?? ''),
                'before_text' => (string) ($rw['before'] ?? ''),
                'suggested_text' => (string) ($rw['after'] ?? ''),
            ];
        }

        foreach (is_array($analysis->suggestions) ? $analysis->suggestions : [] as $text) {
            if (! is_string($text) || trim($text) === '') {
                continue;
            }
            $items[] = [
                'action_type' => 'advisory',
                'target_id' => null,
                'target_label' => '',
                'impact' => 'medium',
                'rationale' => trim($text),
                'before_text' => '',
                'suggested_text' => '',
            ];
        }

        return $items;
    }

    /**
     * Persist (or remove) one state entry inside job_match_detail. Re-reads
     * the row to avoid clobbering state written by a concurrent action.
     */
    protected function writeState(CVAnalysis $analysis, string $id, ?array $entry, ?array $stateOverride = null): void
    {
        $fresh = CVAnalysis::find($analysis->id);
        if ($fresh === null) {
            return;
        }

        $detail = is_array($fresh->job_match_detail) ? $fresh->job_match_detail : [];
        $state = $stateOverride ?? (is_array($detail['apply_state'] ?? null) ? $detail['apply_state'] : []);

        if ($entry === null) {
            unset($state[$id]);
        } else {
            $state[$id] = $entry;
        }

        $detail['apply_state'] = $state;
        $fresh->job_match_detail = $detail;
        $fresh->save();

        // Keep the caller's in-memory model in sync for subsequent reads.
        $analysis->job_match_detail = $detail;
    }

    protected function defaultLabel(string $action): string
    {
        return match ($action) {
            'update_summary' => 'Professional Summary',
            'rewrite_experience' => 'Experience',
            'tailor_project' => 'Project',
            'add_skill' => 'Skill',
            default => 'CV',
        };
    }

    protected function failure(string $key, array $params = []): array
    {
        return ['ok' => false, 'message' => $key, 'params' => $params];
    }
}
