<?php

namespace App\Filament\Widgets;

use App\Models\Inbox;
use App\Models\Post;
use App\Models\Project;
use App\Models\ServiceStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $operationalServices = ServiceStatus::where('status', 'operational')->count();
        $totalServices = ServiceStatus::count();
        $operationalPercentage = $totalServices > 0
            ? round(($operationalServices / $totalServices) * 100)
            : 100;

        return [
            Stat::make('Total Projects', Project::count())
                ->description('Portfolio projects')
                ->descriptionIcon('heroicon-m-folder')
                ->color('success'),

            Stat::make('Blog Posts', Post::where('is_published', true)->count())
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Unread Messages', Inbox::where('is_read', false)->count())
                ->description('From contact form')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),

            Stat::make('Services Status', $operationalPercentage . '%')
                ->description($operationalServices . '/' . $totalServices . ' operational')
                ->descriptionIcon('heroicon-m-server')
                ->color($operationalPercentage >= 100 ? 'success' : ($operationalPercentage >= 80 ? 'warning' : 'danger')),
        ];
    }
}
