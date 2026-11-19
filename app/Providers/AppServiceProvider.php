<?php

namespace App\Providers;

use App\Ai\Gateway\CvOpenAiCompatibleGateway;
use App\Mail\Transports\MailtrapSdkTransport;
use App\Observers\PortfolioCacheObserver;
use App\Support\Filament\AssetManager as FilteredAssetManager;
use Filament\Support\Assets\AssetManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
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
        // Drop unused RicherEditor highlighter scripts (9.65 MB Shiki bundle)
        // from runtime registration and filament:assets republishing.
        $this->app->scoped(AssetManager::class, fn () => new FilteredAssetManager);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (PortfolioCacheObserver::watchedModels() as $model) {
            $model::observe(PortfolioCacheObserver::class);
        }

        Ai::extend('cv-openai-compatible', fn ($app, array $config): OpenAiProvider => new OpenAiProvider(
            new CvOpenAiCompatibleGateway($app['events']),
            $config,
            $app->make(Dispatcher::class),
        ));

        Mail::extend('mailtrap', function (array $config) {
            return new MailtrapSdkTransport(
                $config['api_key'] ?? env('MAILTRAP_API_TOKEN', '')
            );
        });

        if (! $this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
