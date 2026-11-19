<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\HomeSettings;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stack;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

/**
 * Caches the heavy read queries served by PageController.
 *
 * Keys are scoped by locale (en/id) because most content models are
 * translatable. Invalidation uses a per-group generation counter: bumping a
 * generation instantly makes every key in that group unreachable, which also
 * covers deleted rows and changed slugs without any DB queries in the flush
 * path. Stale values are reclaimed by the normal TTL.
 */
class PortfolioCacheService
{
    private const PREFIX = 'portfolio:';

    private const NULL_SENTINEL = '__portfolio_null__';

    private const GENERATION_PREFIX = 'portfolio:gen:';

    /** Cache groups and the models that invalidate them. */
    private const MODEL_GROUPS = [
        HomeSettings::class => ['settings', 'home', 'projects', 'project', 'about', 'certificates', 'blog', 'post', 'contact'],
        Profile::class => ['profile', 'home', 'about', 'contact'],
        Project::class => ['project', 'home', 'projects', 'about'],
        Post::class => ['post', 'home', 'blog', 'about'],
        Category::class => ['about', 'blog'],
        Experience::class => ['home', 'about'],
        Education::class => ['about'],
        Certificate::class => ['certificates', 'settings'],
        Skill::class => ['home', 'about'],
        Stack::class => ['home', 'about'],
    ];

    /** All cache groups, used by flush(). */
    private const GROUPS = [
        'settings', 'profile', 'home', 'projects', 'project', 'about',
        'certificates', 'blog', 'post', 'contact',
    ];

    private function enabled(): bool
    {
        return (bool) config('portfolio-cache.enabled', true);
    }

    private function ttl(): int
    {
        return (int) config('portfolio-cache.ttl', 3600);
    }

    private function generation(string $group): int
    {
        return (int) Cache::get(self::GENERATION_PREFIX.App::getLocale().':'.$group, 0);
    }

    private function key(string $group, string $name): string
    {
        return self::PREFIX.App::getLocale().':'.$group.':'.$this->generation($group).':'.$name;
    }

    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    private function remember(string $group, string $name, callable $callback): mixed
    {
        if (! $this->enabled()) {
            return $callback();
        }

        $key = $this->key($group, $name);

        if (Cache::has($key)) {
            $value = Cache::get($key);

            return $value === self::NULL_SENTINEL ? null : $value;
        }

        $value = $callback();

        // The callback may create a model (e.g. HomeSettings::getSettings()
        // firstOrCreate), firing an observer that bumps the generation. Store
        // under the current generation so the value stays reachable.
        Cache::put($this->key($group, $name), $value ?? self::NULL_SENTINEL, $this->ttl());

        return $value;
    }

    // ------------------------------------------------------------------
    // Shared loaders
    // ------------------------------------------------------------------

    public function settings(): HomeSettings
    {
        return $this->remember('settings', 'settings', fn (): HomeSettings => HomeSettings::getSettings());
    }

    public function primaryProfile(): ?Profile
    {
        return $this->remember('profile', 'primary', fn (): ?Profile => Profile::where('is_primary', true)->first());
    }

    public function hasCertificates(): bool
    {
        return $this->remember('settings', 'has_certificates', fn (): bool => Certificate::query()->exists());
    }
    // ------------------------------------------------------------------
    // Page payloads
    // ------------------------------------------------------------------

    /** @return array{profile: ?Profile, homeSettings: HomeSettings, stacks: Collection, featuredProjects: Collection, latestPosts: Collection, latestExperiences: Collection} */
    public function home(): array
    {
        return $this->remember('home', 'page', function (): array {
            return [
                'profile' => Profile::where('is_primary', true)->first(),
                'homeSettings' => HomeSettings::getSettings(),
                'stacks' => Stack::orderBy('name')->get(),
                'featuredProjects' => Project::with('skills', 'media')
                    ->where('is_featured', true)->orderBy('order')->latest()->take(3)->get(),
                'latestPosts' => Post::published()->latest('published_at')->take(3)->get(),
                'latestExperiences' => Experience::orderBy('order')->orderByDesc('start_date')->take(3)->get(),
                'projectCount' => Project::query()->count(),
            ];
        });
    }

    /** @return array{homeSettings: HomeSettings, projects: Collection} */
    public function projects(): array
    {
        return $this->remember('projects', 'page', function (): array {
            return [
                'homeSettings' => HomeSettings::getSettings(),
                'projects' => Project::with('skills', 'media')->orderBy('order')->latest()->get(),
            ];
        });
    }

    /** @return array{project: Project, homeSettings: HomeSettings, allProjects: Collection} */
    public function projectShow(string $slug): array
    {
        return $this->remember('project', 'page:'.$slug, function () use ($slug): array {
            $project = Project::where('slug', $slug)->firstOrFail();

            return [
                'project' => $project,
                'homeSettings' => HomeSettings::getSettings(),
                'allProjects' => Project::orderBy('order')->get(),
            ];
        });
    }

    /** @return array{profile: ?Profile, homeSettings: HomeSettings, skills: Collection, skillGroups: Collection, coreSkills: Collection} */
    public function about(): array
    {
        return $this->remember('about', 'page', function (): array {
            $profile = Profile::where('is_primary', true)->first();
            $homeSettings = HomeSettings::getSettings();
            $evidence = fn (Skill $skill): int => $skill->projects->count() + $skill->experiences->count();

            $skills = Skill::with(['category', 'projects', 'experiences'])->get()
                ->sortBy([
                    fn (Skill $a, Skill $b) => $evidence($b) <=> $evidence($a),
                    fn (Skill $a, Skill $b) => (float) $b->years_experience <=> (float) $a->years_experience,
                    fn (Skill $a, Skill $b) => strcmp($a->name, $b->name),
                ])->values();

            $skillGroups = $skills
                ->groupBy(fn (Skill $skill) => $skill->category?->name ?? __('messages.about.other_category'))
                ->sortKeys()
                ->sortByDesc(fn ($group) => $group->sum($evidence));

            $coreSkills = $skills->filter(fn (Skill $skill) => $evidence($skill) > 0)->take(6);

            return compact('profile', 'homeSettings', 'skills', 'skillGroups', 'coreSkills');
        });
    }

    /** @return array{homeSettings: HomeSettings, certificates: Collection} */
    public function certificates(): array
    {
        return $this->remember('certificates', 'page', function (): array {
            return [
                'homeSettings' => HomeSettings::getSettings(),
                'certificates' => Certificate::query()->with('media')->orderBy('issue_date', 'desc')->get(),
            ];
        });
    }

    /** @return array{homeSettings: HomeSettings, posts: LengthAwarePaginator} */
    public function blog(?int $page = null): array
    {
        $page ??= max(1, (int) request()->query('page', 1));

        return $this->remember('blog', 'page:'.$page, function (): array {
            return [
                'homeSettings' => HomeSettings::getSettings(),
                'posts' => Post::published()->latest('published_at')->paginate(9),
            ];
        });
    }

    /**
     * Guest posts are cached separately from authenticated requests because
     * the published() scope is only applied for guests.
     *
     * @return array{post: Post, homeSettings: HomeSettings}
     */
    public function blogShow(string $slug, bool $authenticated = false): array
    {
        $audience = $authenticated ? 'auth' : 'guest';

        return $this->remember('post', 'post:'.$audience.':'.$slug, function () use ($slug, $authenticated): array {
            $query = Post::where('slug', $slug);

            if (! $authenticated) {
                $query->published();
            }

            return [
                'post' => $query->firstOrFail(),
                'homeSettings' => HomeSettings::getSettings(),
            ];
        });
    }

    /** @return array{profile: ?Profile, homeSettings: HomeSettings} */
    public function contact(): array
    {
        return $this->remember('contact', 'page', function (): array {
            return [
                'profile' => Profile::where('is_primary', true)->first(),
                'homeSettings' => HomeSettings::getSettings(),
            ];
        });
    }

    // ------------------------------------------------------------------
    // Invalidation
    // ------------------------------------------------------------------

    /** Bump every group for every locale. */
    public function flush(): void
    {
        $this->bump(self::GROUPS);
    }

    /** Bump only the groups affected by a given model class. */
    public function flushModel(string $modelClass): void
    {
        $this->bump(self::MODEL_GROUPS[$modelClass] ?? []);
    }

    /** @param list<string> $groups */
    private function bump(array $groups): void
    {
        foreach (['en', 'id'] as $locale) {
            foreach ($groups as $group) {
                Cache::increment(self::GENERATION_PREFIX.$locale.':'.$group);
            }
        }
    }
}
