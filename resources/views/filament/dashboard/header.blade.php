@php
    use App\Filament\Pages\CVGenerator;
    use App\Filament\Resources\Posts\PostResource;
    use App\Filament\Resources\Projects\ProjectResource;
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

<div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2">
                <span class="rounded-full border border-zinc-200 bg-zinc-100 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                    DevOps Portfolio
                </span>
                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $timeGreeting }}, {{ $userName }}</span>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                Control Center
            </h1>

            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ now()->isoFormat('dddd, D MMMM Y') }} &bull; Managing
                <strong class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $totalProjects }}</strong> projects and
                <strong class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $published }}</strong> published posts
                @if ($drafts > 0)
                    <span class="text-amber-600 dark:text-amber-400">({{ $drafts }} draft{{ $drafts === 1 ? '' : 's' }} pending)</span>
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <x-filament::button :href="ProjectResource::getUrl('create')" tag="a" color="gray" icon="heroicon-o-plus">
                New Project
            </x-filament::button>
            <x-filament::button :href="PostResource::getUrl('create')" tag="a" color="gray" icon="heroicon-o-pencil-square">
                Write Post
            </x-filament::button>
            <x-filament::button :href="CVGenerator::getUrl()" tag="a" color="primary" icon="heroicon-o-sparkles">
                Build CV
            </x-filament::button>
        </div>
    </div>
</div>
