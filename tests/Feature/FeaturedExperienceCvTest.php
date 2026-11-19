<?php

namespace Tests\Feature;

use App\Filament\Pages\CVGenerator;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\User;
use App\Services\CVGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FeaturedExperienceCvTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_featured_experiences_appear_in_cv_data(): void
    {
        Profile::factory()->create(['is_primary' => true]);

        $featured = Experience::factory()->featured()->create([
            'position' => 'Featured Engineer',
            'company' => 'Visible Corp',
            'order' => 1,
        ]);
        Experience::factory()->create([
            'position' => 'Hidden Engineer',
            'company' => 'Hidden Corp',
            'order' => 2,
        ]);

        $data = $this->app->make(CVGeneratorService::class)
            ->buildCvData(Profile::where('is_primary', true)->firstOrFail());

        $this->assertCount(1, $data['experiences']);
        $this->assertSame($featured->id, $data['experiences']->first()->id);
        $this->assertSame(1, $data['totalExperiences']);
    }

    public function test_no_experiences_appear_when_none_are_featured(): void
    {
        Profile::factory()->create(['is_primary' => true]);

        Experience::factory()->create(['position' => 'First Role', 'order' => 1]);
        Experience::factory()->create(['position' => 'Second Role', 'order' => 2]);

        $data = $this->app->make(CVGeneratorService::class)
            ->buildCvData(Profile::where('is_primary', true)->firstOrFail());

        $this->assertCount(0, $data['experiences']);
        $this->assertSame(0, $data['totalExperiences']);
    }

    public function test_featured_experience_renders_in_preview_and_text_export(): void
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        Experience::factory()->featured()->create([
            'position' => 'Principal SRE',
            'company' => 'Featured Corp',
            'order' => 1,
        ]);
        Experience::factory()->create([
            'position' => 'Junior Hidden',
            'company' => 'Hidden Corp',
            'order' => 2,
        ]);

        $this->get(route('cv.preview'))
            ->assertOk()
            ->assertSee('Principal SRE')
            ->assertDontSee('Junior Hidden');

        $text = $this->app->make(CVGeneratorService::class)->generateText($profile);
        $this->assertStringContainsString('PRINCIPAL SRE', $text);
        $this->assertStringNotContainsString('JUNIOR HIDDEN', $text);
    }

    public function test_markdown_and_docx_exports_respect_featured_selection(): void
    {
        $profile = Profile::factory()->create(['is_primary' => true]);

        Experience::factory()->featured()->create([
            'position' => 'Cloud Architect',
            'company' => 'Featured Corp',
            'order' => 1,
        ]);
        Experience::factory()->create([
            'position' => 'Hidden Role',
            'company' => 'Hidden Corp',
            'order' => 2,
        ]);

        $service = $this->app->make(CVGeneratorService::class);

        $markdown = $service->generateMarkdown($profile);
        $this->assertStringContainsString('Cloud Architect', $markdown);
        $this->assertStringNotContainsString('Hidden Role', $markdown);

        $docx = $service->generateDOCX('ats', $profile);
        $this->assertNotEmpty($docx);
    }

    public function test_filament_experience_form_exposes_featured_toggle(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get('/admin/experiences/create')
            ->assertSuccessful()
            ->assertSee('Tampilkan di CV');
    }

    public function test_save_experience_via_livewire_persists_featured_flag(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $this->actingAs($user);

        Livewire::test(CVGenerator::class)
            ->set('exp_company', 'Test Corp')
            ->set('exp_position', 'Platform Engineer')
            ->set('exp_employment_type', 'Full-time')
            ->set('exp_location', 'Jakarta')
            ->set('exp_start_date', '2023-01-01')
            ->set('exp_is_featured', true)
            ->call('saveExperience');

        $this->assertDatabaseHas('experiences', [
            'company' => json_encode(['en' => 'Test Corp']),
            'position' => json_encode(['en' => 'Platform Engineer']),
            'location' => json_encode(['en' => 'Jakarta']),
            'is_featured' => true,
        ]);
    }
}
