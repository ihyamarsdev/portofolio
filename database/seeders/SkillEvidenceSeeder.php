<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Repairs skill data for the public About page: adds missing skills, fixes
 * skill → stack links and attaches projects/experiences as evidence.
 *
 * Idempotent. Not registered in DatabaseSeeder; run explicitly:
 *   php artisan db:seed --class=SkillEvidenceSeeder --force
 */
class SkillEvidenceSeeder extends Seeder
{
    /** @var array<string, string> skill name => category slug */
    private const NEW_SKILLS = [
        'Linux' => 'devops',
        'Helm' => 'container-orchestration',
        'ArgoCD' => 'cicd-gitops',
    ];

    /** @var array<string, list<string>> skill name => stack names */
    private const SKILL_STACKS = [
        'AWS' => ['AWS'],
        'GCP' => ['GCP'],
        'Grafana' => ['Grafana'],
        'Prometheus' => ['Prometheus'],
        'Helm' => ['Helm'],
        'ArgoCD' => ['ArgoCD'],
    ];

    /** @var array<string, list<string>> project slug => skill names (only tools named in the project) */
    private const PROJECT_SKILLS = [
        'civa-cli-interactive-vps-automation' => ['Ansible', 'Docker'],
        'kgen-kubernetes-helm-generator-cli' => ['Kubernetes', 'Helm'],
        'kubernetes-helm-charts-collection' => ['Helm', 'Kubernetes', 'ArgoCD'],
        'ansible-automation-infrastructure-playbooks' => ['Ansible', 'Linux'],
        'trc-kubernetes-gitops-deployment' => ['Kubernetes', 'Helm'],
        'infrastructure-cloud-technical-solution' => ['AWS', 'Docker', 'Linux'],
    ];

    /** @var array<string, list<string>> experience company (en) => skill names (only tools named in the role) */
    private const EXPERIENCE_SKILLS = [
        'Digital Sekuriti Indonesia' => ['Docker', 'Ansible', 'Terraform', 'Grafana', 'GitLab CI/CD', 'GCP'],
        'Bangkit Academy led by Google, Tokopedia, Gojek, & Traveloka' => ['Terraform', 'GCP', 'Docker', 'Kubernetes'],
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            $this->createMissingSkills();

            $skills = Skill::query()->get()->keyBy('name');

            $this->syncStacks($skills);
            $projectLinks = $this->attachProjectEvidence($skills);
            $experienceLinks = $this->attachExperienceEvidence($skills);

            $this->command?->info("Skill evidence: {$projectLinks} project links, {$experienceLinks} experience links attached.");
        });
    }

    private function createMissingSkills(): void
    {
        foreach (self::NEW_SKILLS as $name => $categorySlug) {
            $category = Category::query()->where('slug', $categorySlug)->first();

            if (! $category) {
                $this->command?->warn("Category {$categorySlug} not found; skipped {$name}");

                continue;
            }

            Skill::query()->firstOrCreate(
                ['name' => $name],
                ['category_id' => $category->id, 'proficiency' => 3, 'years_experience' => 0],
            );
        }
    }

    /**
     * @param  Collection<string, Skill>  $skills
     */
    private function syncStacks($skills): void
    {
        foreach (self::SKILL_STACKS as $skillName => $stackNames) {
            $skill = $skills->get($skillName);

            if (! $skill) {
                $this->command?->warn("Skill {$skillName} not found; skipped");

                continue;
            }

            $stackIds = Stack::query()->whereIn('name', $stackNames)->pluck('id')->all();

            if ($stackIds === []) {
                $this->command?->warn('Stacks '.implode(', ', $stackNames)." not found; skipped {$skillName}");

                continue;
            }

            $skill->stacks()->sync($stackIds);
        }
    }

    /**
     * @param  Collection<string, Skill>  $skills
     */
    private function attachProjectEvidence($skills): int
    {
        $attached = 0;

        foreach (self::PROJECT_SKILLS as $slug => $skillNames) {
            $project = Project::query()->where('slug', $slug)->first();

            if (! $project) {
                $this->command?->warn("Project {$slug} not found; skipped");

                continue;
            }

            $attached += count($project->skills()->syncWithoutDetaching($this->skillIds($skills, $skillNames))['attached']);
        }

        return $attached;
    }

    /**
     * @param  Collection<string, Skill>  $skills
     */
    private function attachExperienceEvidence($skills): int
    {
        $attached = 0;
        $experiences = Experience::query()->get();

        foreach (self::EXPERIENCE_SKILLS as $company => $skillNames) {
            $experience = $experiences->first(
                fn (Experience $experience) => trim((string) $experience->getTranslation('company', 'en', false)) === $company
            );

            if (! $experience) {
                $this->command?->warn("Experience {$company} not found; skipped");

                continue;
            }

            $attached += count($experience->skills()->syncWithoutDetaching($this->skillIds($skills, $skillNames))['attached']);
        }

        return $attached;
    }

    /**
     * @param  Collection<string, Skill>  $skills
     * @param  list<string>  $names
     * @return list<int>
     */
    private function skillIds($skills, array $names): array
    {
        $ids = [];

        foreach ($names as $name) {
            $skill = $skills->get($name);

            if (! $skill) {
                $this->command?->warn("Skill {$name} not found; skipped");

                continue;
            }

            $ids[] = $skill->id;
        }

        return $ids;
    }
}
