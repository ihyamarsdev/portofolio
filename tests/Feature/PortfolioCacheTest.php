<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\HomeSettings;
use App\Models\Stack;
use App\Services\PortfolioCacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PortfolioCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['portfolio-cache.enabled' => true]);
        Cache::flush();
    }

    public function test_settings_are_cached_and_reused(): void
    {
        $cache = $this->app->make(PortfolioCacheService::class);

        $first = $cache->settings();
        $second = $cache->settings();

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, HomeSettings::count(), 'Settings should be created exactly once and reused.');
    }

    public function test_home_query_result_is_cached_and_skips_database_on_hit(): void
    {
        $cache = $this->app->make(PortfolioCacheService::class);

        $cache->home();
        $this->resetQueryLog();
        $cache->home();
        $this->assertSame(0, $this->countQueries(), 'Cached home() must not run queries on a hit.');
    }

    public function test_saving_a_model_invalidates_the_relevant_cache_group(): void
    {
        $cache = $this->app->make(PortfolioCacheService::class);
        $cache->home();

        $this->resetQueryLog();
        $cache->home();
        $this->assertSame(0, $this->countQueries(), 'Expected a warm cache before update.');

        Stack::create(['name' => 'Terraform', 'icon' => 'T', 'category_id' => $this->category()->id]);

        $this->resetQueryLog();
        $cache->home();
        $this->assertGreaterThan(0, $this->countQueries(), 'Saving a Stack must invalidate the home cache.');
    }

    public function test_deleting_a_model_invalidates_the_relevant_cache_group(): void
    {
        $cache = $this->app->make(PortfolioCacheService::class);
        $stack = Stack::create(['name' => 'Ansible', 'icon' => 'A', 'category_id' => $this->category()->id]);
        $cache->home();

        $this->resetQueryLog();
        $cache->home();
        $this->assertSame(0, $this->countQueries(), 'Expected a warm cache before delete.');

        $stack->delete();

        $this->resetQueryLog();
        $cache->home();
        $this->assertGreaterThan(0, $this->countQueries(), 'Deleting a Stack must invalidate the home cache.');
    }

    public function test_cache_keys_are_scoped_by_locale(): void
    {
        $cache = $this->app->make(PortfolioCacheService::class);

        app()->setLocale('en');
        $cache->home();
        $this->resetQueryLog();
        $cache->home();
        $this->assertSame(0, $this->countQueries(), 'en cache should be warm.');

        app()->setLocale('id');
        $this->resetQueryLog();
        $cache->home();
        $this->assertGreaterThan(0, $this->countQueries(), 'Changing locale must miss the cache.');
    }

    public function test_flushing_clears_all_locale_caches(): void
    {
        $cache = $this->app->make(PortfolioCacheService::class);

        foreach (['en', 'id'] as $locale) {
            app()->setLocale($locale);
            $cache->home();
        }

        $cache->flush();

        foreach (['en', 'id'] as $locale) {
            app()->setLocale($locale);
            $this->resetQueryLog();
            $cache->home();
            $this->assertGreaterThan(0, $this->countQueries(), "Cache for {$locale} must be flushed.");
        }
    }

    public function test_cached_page_is_served_by_controller_and_invalidated_by_model_update(): void
    {
        $category = $this->category();
        Stack::create(['name' => 'Kubernetes', 'icon' => 'K', 'category_id' => $category->id]);

        $this->get('/')->assertOk()->assertSee('Kubernetes');
        $this->resetQueryLog();
        $this->get('/')->assertOk();
        $this->assertSame(0, $this->countQueries(), 'Second request must be served from cache.');

        Stack::create(['name' => 'Prometheus', 'icon' => 'P', 'category_id' => $category->id]);

        $this->get('/')->assertOk()->assertSee('Prometheus');
    }

    public function test_cache_is_bypassed_when_disabled(): void
    {
        config(['portfolio-cache.enabled' => false]);
        $cache = $this->app->make(PortfolioCacheService::class);

        $cache->home();
        $this->resetQueryLog();
        $cache->home();

        $this->assertGreaterThan(0, $this->countQueries(), 'Disabled cache must always hit the database.');
    }

    private function category(): Category
    {
        return Category::factory()->create();
    }

    private function resetQueryLog(): void
    {
        \DB::flushQueryLog();
        \DB::enableQueryLog();
    }

    private function countQueries(): int
    {
        return count(\DB::getQueryLog());
    }
}
