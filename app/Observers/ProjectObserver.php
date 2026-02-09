<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\DiagramRenderer;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    protected DiagramRenderer $diagramRenderer;

    public function __construct(DiagramRenderer $diagramRenderer)
    {
        $this->diagramRenderer = $diagramRenderer;
    }

    /**
     * Handle the Project "saved" event.
     */
    public function saved(Project $project): void
    {
        // Check if architecture_diagram was changed
        if ($project->wasChanged('architecture_diagram') || $project->wasRecentlyCreated) {
            $this->renderDiagram($project);
        }
    }

    /**
     * Render the architecture diagram to SVG
     */
    protected function renderDiagram(Project $project): void
    {
        if (empty($project->architecture_diagram)) {
            // Clear existing diagrams if no diagram code
            $project->clearMediaCollection('diagrams');
            return;
        }

        try {
            $success = $this->diagramRenderer->renderAndSaveToMedia(
                $project,
                $project->architecture_diagram,
                'diagrams'
            );

            if ($success) {
                Log::info("Architecture diagram rendered for project: {$project->title}");
            } else {
                Log::warning("Failed to render architecture diagram for project: {$project->title}");
            }
        } catch (\Exception $e) {
            Log::error("Error rendering diagram for project {$project->id}: " . $e->getMessage());
        }
    }
}
