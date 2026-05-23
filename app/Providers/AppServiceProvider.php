<?php

namespace App\Providers;

use App\Ai\Gateway\CvOpenAiCompatibleGateway;
use App\Models\Project;
use App\Observers\ProjectObserver;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Ai\Ai;
use Laravel\Ai\Providers\OpenAiProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Ai::extend('cv-openai-compatible', fn ($app, array $config): OpenAiProvider => new OpenAiProvider(
            new CvOpenAiCompatibleGateway($app['events']),
            $config,
            $app->make(Dispatcher::class),
        ));

        Project::observe(ProjectObserver::class);

        if (! $this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
