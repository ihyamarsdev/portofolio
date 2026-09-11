<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Inbox;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $unreadMessages = Inbox::where('is_read', false)->count();

        return [
            Stat::make('Total Projects', Project::count())
                ->description(Project::where('is_featured', true)->count().' featured')
                ->descriptionIcon('heroicon-m-folder')
                ->chart($this->weeklyTrend(Project::query()))
                ->color('success'),

            Stat::make('Blog Posts', Post::published()->count())
                ->description(Post::where('status', '!=', 'published')->count().' draft(s)')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart($this->weeklyTrend(Post::query()))
                ->color('info'),

            Stat::make('Unread Messages', $unreadMessages)
                ->description($unreadMessages > 0 ? 'Needs your attention' : 'Inbox all clear')
                ->descriptionIcon('heroicon-m-envelope')
                ->chart($this->weeklyTrend(Inbox::query()))
                ->color($unreadMessages > 0 ? 'warning' : 'success'),

            Stat::make('Certificates', Certificate::count())
                ->description('Active credentials')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart($this->weeklyTrend(Certificate::query()))
                ->color('primary'),

            Stat::make('Skills', Skill::count())
                ->description('Across all categories')
                ->descriptionIcon('heroicon-m-bolt')
                ->chart($this->weeklyTrend(Skill::query()))
                ->color('gray'),
        ];
    }

    /**
     * Build a 7-day cumulative count sparkline for a model query.
     *
     * @return array<int>
     */
    protected function weeklyTrend(Builder $query): array
    {
        $createdAt = (clone $query)->pluck('created_at');

        $counts = [];

        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::today()->subDays($daysAgo)->endOfDay();

            $counts[] = $createdAt
                ->filter(fn ($value) => $value !== null && Carbon::parse($value)->lessThanOrEqualTo($date))
                ->count();
        }

        return $counts;
    }
}
