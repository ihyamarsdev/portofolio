<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Http\Middleware\SetLocale;
use App\Livewire\LanguageSwitcher;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Icons\Heroicon;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->profile(EditProfile::class)
            ->maxContentWidth('7xl')
            ->sidebarCollapsibleOnDesktop()
            ->globalSearch()
            ->colors([
                'primary' => Color::Sky,
                'gray' => Color::Zinc,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'info' => Color::Blue,
            ])
            ->navigationGroups([
                NavigationGroup::make('Content & Communication')
                    ->icon(Heroicon::OutlinedNewspaper),
                NavigationGroup::make('Career & CV')
                    ->icon(Heroicon::OutlinedSparkles),
                NavigationGroup::make('Settings')
                    ->icon(Heroicon::OutlinedCog6Tooth)
                    ->collapsed(),
            ])
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages',
            )
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets',
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->renderHook(
                'panels::topbar.end',
                fn (): HtmlString => new HtmlString(
                    (string) Livewire::mount(LanguageSwitcher::class)
                ),
            )
            ->authMiddleware([Authenticate::class]);
    }
}
