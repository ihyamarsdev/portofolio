{{-- Selected Projects Partial — compact, ATS-safe, single column.
     Shows title, stack summary, concise impact description, and links.
     Variables: $projects (collection with ->skills relation), $heading
--}}
@if(($projects ?? collect())->count() > 0)
    <div class="section">
        <h2 class="section-title">{{ $heading ?? 'Selected Projects' }}</h2>
        @foreach($projects as $project)
            <div class="project-item">
                <div class="entry-head">
                    <span class="entry-role">{{ $project->title }}</span>
                    @if($project->live_url || $project->repository_url)
                        <span class="entry-date">
                            @if($project->live_url)<a href="{{ $project->live_url }}" target="_blank" rel="noopener">Live</a>@endif
                            @if($project->live_url && $project->repository_url) | @endif
                            @if($project->repository_url)<a href="{{ $project->repository_url }}" target="_blank" rel="noopener">Repo</a>@endif
                        </span>
                    @endif
                </div>
                @if($project->skills && $project->skills->count() > 0)
                    <p class="entry-context">{{ $project->skills->pluck('name')->join(' · ') }}</p>
                @endif
                @if($project->description)
                    <p class="project-description">{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 220) }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
