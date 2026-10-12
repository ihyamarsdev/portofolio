<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stack;
use Database\Seeders\SkillEvidenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SkillEvidenceSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_fixes_stacks_attaches_evidence_and_is_idempotent(): void
    {
        foreach (['devops', 'container-orchestration', 'cicd-gitops', 'cloud-engineering'] as $slug) {
            Category::factory()->create(['slug' => $slug, 'name' => $slug]);
        }

        $stacks = [];
        foreach (['AWS', 'GCP', 'Grafana', 'Prometheus', 'Helm', 'ArgoCD'] as $name) {
            $stacks[$name] = Stack::query()->create(['name' => $name]);
        }

        $skills = [];
        foreach (['AWS', 'GCP', 'Grafana', 'Prometheus', 'Docker', 'Kubernetes', 'Ansible', 'Terraform', 'GitLab CI/CD'] as $name) {
            $skills[$name] = Skill::factory()->create(['name' => $name]);
        }
        $skills['GCP']->stacks()->attach($stacks['AWS']);

        $kgen = Project::query()->create(['title' => 'KGen', 'slug' => 'kgen-kubernetes-helm-generator-cli', 'description' => 'd']);
        $kgen->skills()->attach($skills['Docker']); // simulates a link added in the admin
        $charts = Project::query()->create(['title' => 'Charts', 'slug' => 'kubernetes-helm-charts-collection', 'description' => 'd']);
        $experience = Experience::factory()->create(['company' => 'Digital Sekuriti Indonesia']);

        $this->seed(SkillEvidenceSeeder::class);
        $afterFirst = [DB::table('project_skill')->count(), DB::table('experience_skill')->count()];
        $this->seed(SkillEvidenceSeeder::class);

        $this->assertSame(1, Skill::query()->where('name', 'Helm')->count());
        $this->assertSame('container-orchestration', Skill::query()->where('name', 'Helm')->first()->category->slug);
        $this->assertSame('devops', Skill::query()->where('name', 'Linux')->first()->category->slug);
        $this->assertSame(['GCP'], $skills['GCP']->stacks()->pluck('name')->all());
        $this->assertSame(['Docker', 'Helm', 'Kubernetes'], $kgen->skills()->pluck('name')->sort()->values()->all());
        $this->assertContains('ArgoCD', $charts->skills()->pluck('name')->all());
        $this->assertCount(6, $experience->skills);
        $this->assertSame($afterFirst, [DB::table('project_skill')->count(), DB::table('experience_skill')->count()]);
    }
}
