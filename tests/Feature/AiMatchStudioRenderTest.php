<?php

namespace Tests\Feature;

use App\Filament\Pages\CVGenerator;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AiMatchStudioRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Profile::factory()->create(['is_primary' => true]);
        $this->actingAs(User::factory()->create());
        $this->app->setLocale('en');
    }

    public function test_empty_state_renders_in_english(): void
    {
        Livewire::test(CVGenerator::class)
            ->set('activeTab', 'aimatch')
            ->assertSuccessful()
            ->assertSee('Review & Apply Studio')
            ->assertSee('No analysis results yet')
            ->assertSee('Run AI Analysis');
    }

    public function test_empty_state_renders_in_indonesian(): void
    {
        $this->app->setLocale('id');

        Livewire::test(CVGenerator::class)
            ->set('activeTab', 'aimatch')
            ->assertSuccessful()
            ->assertSee('Ubah analisis jadi tindakan')
            ->assertSee('Belum ada hasil analisis');
    }

    public function test_structured_analysis_renders_action_queue_with_tabs(): void
    {
        $profile = Profile::where('is_primary', true)->first();
        $profile->cvAnalyses()->create([
            'ats_score' => 82,
            'format_score' => 78,
            'job_match_score' => 74,
            'job_match_detail' => [
                'overall' => 74,
                'actionable_suggestions' => [[
                    'action_type' => 'update_summary',
                    'target_id' => null,
                    'target_label' => 'Professional Summary',
                    'impact' => 'high',
                    'rationale' => 'Maps to Kubernetes requirement.',
                    'before_text' => 'Old summary.',
                    'suggested_text' => 'New summary.',
                ]],
            ],
        ]);

        Livewire::test(CVGenerator::class)
            ->set('activeTab', 'aimatch')
            ->assertSuccessful()
            ->assertSee('Action Queue')
            ->assertSee('Matched Evidence')
            ->assertSee('Skill &amp; Requirement Gaps', false)
            ->assertSee('Professional Summary')
            ->assertSee('New summary.')
            ->assertSee('Apply to CV');
    }

    public function test_switching_segments_renders_gaps_and_rubric(): void
    {
        $profile = Profile::where('is_primary', true)->first();
        $profile->cvAnalyses()->create([
            'ats_score' => 60,
            'format_score' => 70,
            'job_match_detail' => [
                'overall' => 55,
                'requirements' => [
                    ['requirement' => 'Kubernetes', 'status' => 'verified', 'evidence_quote' => 'Used Kubernetes at scale.', 'reasoning' => 'Quantified.'],
                    ['requirement' => 'Terraform', 'status' => 'missing', 'evidence_quote' => '', 'reasoning' => 'Not found.'],
                ],
            ],
        ]);

        Livewire::test(CVGenerator::class)
            ->set('activeTab', 'aimatch')
            ->call('setAimatchTab', 'evidence')
            ->assertSee('Matched Evidence')
            ->assertSee('Kubernetes')
            ->assertSee('Verified')
            ->call('setAimatchTab', 'gaps')
            ->assertSee('Skill &amp; Requirement Gaps', false)
            ->assertSee('Terraform')
            ->call('setAimatchTab', 'rubric')
            ->assertSee('Rubric Breakdown');
    }

    public function test_legacy_analysis_renders_without_crashing(): void
    {
        $profile = Profile::where('is_primary', true)->first();
        $profile->cvAnalyses()->create([
            'ats_score' => 70,
            'format_score' => 65,
            'suggestions' => ['Add more quantified metrics'],
        ]);

        Livewire::test(CVGenerator::class)
            ->set('activeTab', 'aimatch')
            ->assertSuccessful()
            ->assertSee('Action Queue')
            ->assertSee('Add more quantified metrics');
    }

    public function test_apply_then_view_preview_switches_tab_and_bumps_nonce(): void
    {
        $profile = Profile::where('is_primary', true)->first();
        $profile->setTranslation('biography', 'en', 'Old bio.');
        $profile->save();

        $analysis = $profile->cvAnalyses()->create([
            'ats_score' => 80,
            'format_score' => 80,
            'job_match_detail' => ['actionable_suggestions' => [[
                'action_type' => 'update_summary',
                'target_id' => null,
                'target_label' => 'Professional Summary',
                'impact' => 'high',
                'rationale' => 'Test.',
                'before_text' => 'Old bio.',
                'suggested_text' => 'New bio.',
            ]]],
        ]);

        $component = Livewire::test(CVGenerator::class)->set('activeTab', 'aimatch');
        $suggestionId = $component->get('actionableSuggestions.0.id');

        $component->call('applySuggestion', $suggestionId, null)
            ->assertSet('activeTab', 'aimatch')
            ->assertSee('Applied')
            ->call('viewUpdatedPreview')
            ->assertSet('activeTab', 'preview');

        $this->assertSame('New bio.', $profile->fresh()->getTranslation('biography', 'en'));
        $this->assertGreaterThan(0, $component->get('previewNonce'));
    }

    public function test_jd_input_collapses_after_analysis_and_can_be_toggled(): void
    {
        Livewire::test(CVGenerator::class)
            ->set('activeTab', 'aimatch')
            ->assertSet('showJdInput', true)
            ->call('toggleJdInput')
            ->assertSet('showJdInput', false)
            ->assertSee('Edit JD / New Match')
            ->call('toggleJdInput')
            ->assertSet('showJdInput', true);
    }
}
