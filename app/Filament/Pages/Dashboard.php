<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LatestMessagesWidget;
use App\Filament\Widgets\LatestProjectsWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SystemStatusWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 2];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            LatestMessagesWidget::class,
            SystemStatusWidget::class,
            LatestProjectsWidget::class,
        ];
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }

    public function getHeader(): ?View
    {
        return view('filament.dashboard.header', [
            'userName' => Auth::user()?->name ?? 'there',
        ]);
    }
}
