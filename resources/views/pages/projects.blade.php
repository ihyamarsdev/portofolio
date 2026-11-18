<x-layouts.app title="{{ __('messages.projects.title') }}">
    @php($projects = \App\Models\Project::with('skills', 'media')->orderBy('order')->latest()->get())

    <div class="section-container py-14 sm:py-20">
        <x-ui.section-header
            :index="'Index — '.str_pad($projects->count(), 2, '0', STR_PAD_LEFT)"
            :as="'h1'"
            :title="__('messages.projects.title')"
            :description="__('messages.projects.subtitle')" />

        @if($projects->isNotEmpty())
            <div class="mt-10 grid gap-6 md:grid-cols-2">
                @foreach($projects as $project)
                    <x-ui.project-item :project="$project" :index="str_pad($loop->iteration, 2, '0', STR_PAD_LEFT)" layout="card" />
                @endforeach
            </div>
        @else
            <p class="mt-10 border border-line bg-surface p-10 text-center text-sm text-ink-2">{{ __('messages.projects.no_projects_found') }}</p>
        @endif
    </div>
</x-layouts.app>
