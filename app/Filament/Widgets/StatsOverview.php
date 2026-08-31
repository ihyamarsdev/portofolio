<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Inbox;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $unreadMessages = Inbox::where('is_read', false)->count();

        return [
            Stat::make('Total Projects', Project::count())
                ->description('Portfolio projects')
                ->descriptionIcon('heroicon-m-folder')
                ->color('success'),

            Stat::make('Blog Posts', Post::published()->count())
                ->description(Post::where('status', '!=', 'published')->count().' draft(s)')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Unread Messages', $unreadMessages)
                ->description($unreadMessages > 0 ? 'Needs your attention' : 'Inbox all clear')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadMessages > 0 ? 'warning' : 'success'),

            Stat::make('Certificates', Certificate::count())
                ->description('Active credentials')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Skills', Skill::count())
                ->description('Across all categories')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('gray'),
        ];
    }
}
