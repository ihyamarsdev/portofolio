@php
    use App\Filament\Resources\Posts\PostResource;
    use App\Filament\Resources\Projects\ProjectResource;
    use App\Filament\Pages\CVGenerator;
    use App\Models\Post;
    use App\Models\Project;

    $hour = (int) now()->format('G');
    $timeGreeting = match (true) {
        $hour < 11 => 'Good morning',
        $hour < 15 => 'Good afternoon',
        $hour < 19 => 'Good evening',
        default => 'Good night',
    };

    $published = Post::published()->count();
    $drafts = Post::where('status', '!=', 'published')->count();
    $totalProjects = Project::count();
@endphp

<div class="dashboard-hero">
    <div class="dashboard-hero-main">
        <div class="dashboard-hero-greeting">
            <span class="dashboard-hero-badge">DevOps Portfolio</span>
            <span class="dashboard-hero-user">{{ $timeGreeting }}, {{ $userName }}</span>
        </div>

        <h1 class="dashboard-hero-title">
            Control Center
        </h1>

        <p class="dashboard-hero-subtitle">
            {{ now()->isoFormat('dddd, D MMMM Y') }} &bull; Managing
            <strong>{{ $totalProjects }}</strong> projects and
            <strong>{{ $published }}</strong> published posts
            @if ($drafts > 0)
                <span class="dashboard-hero-drafts">({{ $drafts }} draft{{ $drafts === 1 ? '' : 's' }} pending)</span>
            @endif
        </p>
    </div>

    <div class="dashboard-hero-actions">
        <a href="{{ ProjectResource::getUrl('create') }}" class="dashboard-quick-action">
            <x-heroicon-o-plus class="w-4 h-4" />
            <span>New Project</span>
        </a>
        <a href="{{ PostResource::getUrl('create') }}" class="dashboard-quick-action">
            <x-heroicon-o-pencil-square class="w-4 h-4" />
            <span>Write Post</span>
        </a>
        <a href="{{ CVGenerator::getUrl() }}" class="dashboard-quick-action is-accent">
            <x-heroicon-o-sparkles class="w-4 h-4" />
            <span>Build CV</span>
        </a>
    </div>
</div>
