@php
    use App\Filament\Resources\Posts\PostResource;
    use App\Filament\Resources\Projects\ProjectResource;
    use App\Filament\Pages\CVGenerator;
    use App\Models\Inbox;
    use App\Models\Post;
    use App\Models\Project;

    $hour = (int) now()->format('G');
    $timeGreeting = match (true) {
        $hour < 11 => 'Good morning',
        $hour < 15 => 'Good afternoon',
        $hour < 19 => 'Good evening',
        default => 'Good night',
    };

    $unread = Inbox::where('is_read', false)->count();
    $published = Post::published()->count();
    $drafts = Post::where('status', '!=', 'published')->count();
    $featured = Project::where('is_featured', true)->count();
@endphp

<div class="dashboard-hero">
    <div class="dashboard-hero-glow" aria-hidden="true"></div>

    <div class="dashboard-hero-main">
        <div class="dashboard-hero-greeting">
            <span class="dashboard-hero-pulse" aria-hidden="true"></span>
            <span>{{ $timeGreeting }}, {{ $userName }}</span>
        </div>

        <h1 class="dashboard-hero-title">
            Control <span>Center</span>
        </h1>

        <p class="dashboard-hero-subtitle">
            {{ now()->isoFormat('dddd, D MMMM Y') }} · Managing
            <strong>{{ Project::count() }}</strong> projects,
            <strong>{{ $published }}</strong> published posts
            @if ($drafts > 0) · <strong>{{ $drafts }}</strong> draft{{ $drafts === 1 ? '' : 's' }} pending @endif
        </p>
    </div>

    <div class="dashboard-hero-aside">
        <div class="dashboard-hero-chip {{ $unread > 0 ? 'is-alert' : 'is-clear' }}">
            @if ($unread > 0)
                <x-heroicon-o-envelope class="dashboard-hero-chip-icon" />
                <div>
                    <div class="dashboard-hero-chip-value">{{ $unread }}</div>
                    <div class="dashboard-hero-chip-label">Unread</div>
                </div>
            @else
                <x-heroicon-o-envelope-open class="dashboard-hero-chip-icon" />
                <div>
                    <div class="dashboard-hero-chip-value">All clear</div>
                    <div class="dashboard-hero-chip-label">Inbox</div>
                </div>
            @endif
        </div>

        <div class="dashboard-hero-chip">
            <x-heroicon-o-star class="dashboard-hero-chip-icon" />
            <div>
                <div class="dashboard-hero-chip-value">{{ $featured }}</div>
                <div class="dashboard-hero-chip-label">Featured</div>
            </div>
        </div>
    </div>

    <div class="dashboard-hero-actions">
        <a href="{{ ProjectResource::getUrl('create') }}" class="dashboard-quick-action">
            <x-heroicon-o-plus class="w-4 h-4" />
            New Project
        </a>
        <a href="{{ PostResource::getUrl('create') }}" class="dashboard-quick-action">
            <x-heroicon-o-pencil-square class="w-4 h-4" />
            Write Post
        </a>
        <a href="{{ CVGenerator::getUrl() }}" class="dashboard-quick-action is-accent">
            <x-heroicon-o-sparkles class="w-4 h-4" />
            Build CV
        </a>
    </div>
</div>
