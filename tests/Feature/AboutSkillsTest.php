<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutSkillsTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_shows_evidence_and_years_without_self_rated_levels(): void
    {
        $category = Category::factory()->create();
        $docker = Skill::factory()->create(['name' => 'Docker', 'category_id' => $category->id, 'proficiency' => 5, 'years_experience' => 4.0]);
        $helm = Skill::factory()->create(['name' => 'Helm', 'category_id' => $category->id, 'proficiency' => 1, 'years_experience' => 0]);

        $project = Project::query()->create(['title' => 'KGen', 'slug' => 'kgen', 'description' => 'd']);
        $project->skills()->attach($helm);

        $experience = Experience::factory()->create(['company' => 'Acme Corp']);
        $experience->skills()->attach($docker);

        $this->get('/about')
            ->assertOk()
            ->assertSee('KGen')
            ->assertSee(route('projects.show', 'kgen'), false)
            ->assertSee('#experience-'.$experience->id, false)
            ->assertSee('id="experience-'.$experience->id.'"', false)
            ->assertSee('4 yrs')
            ->assertDontSee('0 yrs')
            ->assertDontSee('Beginner')
            ->assertDontSee('Expert')
            ->assertSeeInOrder(['Core stack', 'Docker', 'Helm']);
    }
}
