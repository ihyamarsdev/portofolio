<?php

namespace App\Filament\Widgets;

use App\Models\Inbox;
use App\Models\Post;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
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
        ];
    }
}
