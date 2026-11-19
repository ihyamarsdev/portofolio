<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CVAnalysis;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Services\ApplyCvSuggestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplyCvSuggestionTest extends TestCase
{
    use RefreshDatabase;

    protected Profile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profile = Profile::factory()->create(['is_primary' => true]);
        $this->app->setLocale('en');
    }

    /**
     * Create an analysis carrying a structured patch in job_match_detail.
     */
    protected function analysisWithPatches(array $patches): CVAnalysis
    {
        return $this->profile->cvAnalyses()->create([
            'ats_score' => 80,
            'format_score' => 80,
            'job_match_detail' => ['actionable_suggestions' => $patches],
        ]);
    }

    protected function service(): ApplyCvSuggestionService
    {
        return app(ApplyCvSuggestionService::class);
    }

    protected function idFor(CVAnalysis $analysis, string $action): string
    {
        foreach ($this->service()->buildSuggestions($analysis, 'en') as $item) {
            if ($item['action_type'] === $action) {
                return $item['id'];
            }
        }

        $this->fail("No suggestion found for action: {$action}");
    }

    public function test_apply_update_summary_updates_only_active_locale_and_preserves_others(): void
    {
        $this->profile->setTranslation('biography', 'en', 'Old English summary.');
        $this->profile->setTranslation('biography', 'id', 'Ringkasan lama.');
        $this->profile->save();

        $analysis = $this->analysisWithPatches([[
            'action_type' => 'update_summary',
            'target_id' => null,
            'target_label' => 'Professional Summary',
            'impact' => 'high',
            'rationale' => 'Mentions Kubernetes delivery.',
            'before_text' => 'Old English summary.',
            'suggested_text' => 'New English summary.',
        ]]);

        $id = $this->idFor($analysis, 'update_summary');
        $result = $this->service()->apply($analysis, $id, null, 'en');

        $this->assertTrue($result['ok']);
        $this->profile->refresh();
        $this->assertSame('New English summary.', $this->profile->getTranslation('biography', 'en'));
        $this->assertSame('Ringkasan lama.', $this->profile->getTranslation('biography', 'id'));
    }

    public function test_apply_rewrite_experience_updates_translatable_description(): void
    {
        $exp = Experience::factory()->create([
            'description' => ['en' => 'Responsible for deployments.', 'id' => 'Bertanggung jawab atas deployment.'],
        ]);

        $analysis = $this->analysisWithPatches([[
            'action_type' => 'rewrite_experience',
            'target_id' => $exp->id,
            'target_label' => 'Experience',
            'impact' => 'high',
            'rationale' => 'Strengthens deployment evidence.',
            'before_text' => 'Responsible for deployments.',
            'suggested_text' => 'Delivered deployments with [insert metric].',
        ]]);

        $id = $this->idFor($analysis, 'rewrite_experience');
        $result = $this->service()->apply($analysis, $id, null, 'en');

        $this->assertTrue($result['ok']);
        $exp->refresh();
        $this->assertSame('Delivered deployments with [insert metric].', $exp->getTranslation('description', 'en'));
        $this->assertSame('Bertanggung jawab atas deployment.', $exp->getTranslation('description', 'id'));
    }

    public function test_inline_edited_content_is_applied(): void
    {
        $analysis = $this->analysisWithPatches([[
            'action_type' => 'update_summary',
            'target_id' => null,
            'target_label' => 'Professional Summary',
            'impact' => 'medium',
            'rationale' => 'Test.',
            'before_text' => $this->profile->getTranslation('biography', 'en'),
            'suggested_text' => 'Original suggestion.',
        ]]);

        $id = $this->idFor($analysis, 'update_summary');
        $result = $this->service()->apply($analysis, $id, 'User edited version.', 'en');

        $this->assertTrue($result['ok']);
        $this->profile->refresh();
        $this->assertSame('User edited version.', $this->profile->getTranslation('biography', 'en'));
    }

    public function test_undo_restores_exact_prior_translations_map(): void
    {
        $this->profile->setTranslation('biography', 'en', 'Original EN.');
        $this->profile->setTranslation('biography', 'id', 'Original ID.');
        $this->profile->save();
        $before = $this->profile->getTranslations('biography');

        $analysis = $this->analysisWithPatches([[
            'action_type' => 'update_summary',
            'target_id' => null,
            'target_label' => 'Professional Summary',
            'impact' => 'high',
            'rationale' => 'Test.',
            'before_text' => 'Original EN.',
            'suggested_text' => 'Changed EN.',
        ]]);

        $id = $this->idFor($analysis, 'update_summary');
        $this->service()->apply($analysis, $id, null, 'en');
        $this->assertSame('Changed EN.', $this->profile->fresh()->getTranslation('biography', 'en'));

        $undo = $this->service()->undo($analysis, $id, 'en');
        $this->assertTrue($undo['ok']);
        $this->assertSame($before, $this->profile->fresh()->getTranslations('biography'));
    }

    public function test_add_skill_creates_skill_and_undo_deletes_it(): void
    {
        Category::factory()->create();

        $analysis = $this->analysisWithPatches([[
            'action_type' => 'add_skill',
            'target_id' => null,
            'target_label' => 'Skill',
            'impact' => 'high',
            'rationale' => 'JD requires Kubernetes.',
            'before_text' => '',
            'suggested_text' => 'Kubernetes',
        ]]);

        $id = $this->idFor($analysis, 'add_skill');
        $result = $this->service()->apply($analysis, $id, null, 'en');

        $this->assertTrue($result['ok']);
        $this->assertDatabaseHas('skills', ['name' => 'Kubernetes']);
        $skill = Skill::where('name', 'Kubernetes')->first();

        $undo = $this->service()->undo($analysis, $id, 'en');
        $this->assertTrue($undo['ok']);
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_duplicate_skill_is_rejected_case_insensitively(): void
    {
        Category::factory()->create();
        Skill::factory()->create(['name' => 'Kubernetes']);

        $analysis = $this->analysisWithPatches([[
            'action_type' => 'add_skill',
            'target_id' => null,
            'target_label' => 'Skill',
            'impact' => 'high',
            'rationale' => 'JD requires Kubernetes.',
            'before_text' => '',
            'suggested_text' => 'kubernetes',
        ]]);

        $id = $this->idFor($analysis, 'add_skill');
        $result = $this->service()->apply($analysis, $id, null, 'en');

        $this->assertFalse($result['ok']);
        $this->assertSame('duplicate_skill', $result['message']);
        $this->assertSame(1, Skill::whereRaw('LOWER(name) = ?', ['kubernetes'])->count());
    }

    public function test_invalid_target_id_is_rejected_without_writing(): void
    {
        $analysis = $this->analysisWithPatches([[
            'action_type' => 'rewrite_experience',
            'target_id' => 999999,
            'target_label' => 'Experience',
            'impact' => 'high',
            'rationale' => 'Test.',
            'before_text' => 'Something.',
            'suggested_text' => 'Something better.',
        ]]);

        $id = $this->idFor($analysis, 'rewrite_experience');
        $result = $this->service()->apply($analysis, $id, null, 'en');

        $this->assertFalse($result['ok']);
        $this->assertSame('invalid_target', $result['message']);
    }

    public function test_legacy_analysis_with_string_suggestions_maps_to_advisory_items(): void
    {
        $analysis = $this->profile->cvAnalyses()->create([
            'ats_score' => 70,
            'format_score' => 70,
            'suggestions' => ['Add more metrics', 'Shorten summary'],
        ]);

        $items = $this->service()->buildSuggestions($analysis, 'en');

        $this->assertCount(2, $items);
        $this->assertTrue(collect($items)->every(fn ($i) => $i['action_type'] === 'advisory'));
    }

    public function test_dismiss_then_state_is_recorded(): void
    {
        $analysis = $this->analysisWithPatches([[
            'action_type' => 'update_summary',
            'target_id' => null,
            'target_label' => 'Professional Summary',
            'impact' => 'low',
            'rationale' => 'Test.',
            'before_text' => 'Old.',
            'suggested_text' => 'New.',
        ]]);

        $id = $this->idFor($analysis, 'update_summary');
        $result = $this->service()->dismiss($analysis, $id, 'en');

        $this->assertTrue($result['ok']);
        $items = $this->service()->buildSuggestions($analysis->fresh(), 'en');
        $this->assertSame('dismissed', $items[0]['state']);
    }
}
