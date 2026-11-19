<?php

namespace App\Observers;

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
use App\Services\PortfolioCacheService;

/**
 * Flushes the relevant public-page cache groups whenever content changes in
 * Filament (or anywhere else). Uses a shared observer so registration stays in
 * one place in AppServiceProvider.
 */
class PortfolioCacheObserver
{
    public function __construct(private readonly PortfolioCacheService $cache) {}

    public function saved(object $model): void
    {
        $this->flushFor($model);
    }

    public function deleted(object $model): void
    {
        $this->flushFor($model);
    }

    public function restored(object $model): void
    {
        $this->flushFor($model);
    }

    private function flushFor(object $model): void
    {
        $this->cache->flushModel($model::class);
    }

    /** @return list<class-string> */
    public static function watchedModels(): array
    {
        return [
            HomeSettings::class,
            Profile::class,
            Project::class,
            Post::class,
            Category::class,
            Experience::class,
            Education::class,
            Certificate::class,
            Skill::class,
            Stack::class,
        ];
    }
}
