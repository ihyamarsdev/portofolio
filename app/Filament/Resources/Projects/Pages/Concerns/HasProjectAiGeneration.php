<?php

namespace App\Filament\Resources\Projects\Pages\Concerns;

use App\Ai\Agents\ProjectGenerationAgent;
use App\Models\Skill;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

trait HasProjectAiGeneration
{
    protected function getGenerateAiAction(): Action
    {
        return Action::make('generate_ai')
            ->label('⚡ Generate with AI')
            ->color('warning')
            ->icon('heroicon-o-sparkles')
            ->form([
                Select::make('generate_target')
                    ->label('What would you like to generate?')
                    ->options([
                        'description' => '📝 Technical Description Only',
                        'diagram' => '📊 Architecture Diagram Only',
                        'both' => '✨ Both Description & Diagram',
                    ])
                    ->default('both')
                    ->required()
                    ->native(false)
                    ->live(),
                Select::make('diagram_style')
                    ->label('Diagram Style')
                    ->options([
                        'flowchart TD' => '📊 Top-Down Flowchart',
                        'flowchart LR' => '➡️ Left-Right Flowchart',
                        'graph TD' => '🌐 Network Graph (Top-Down)',
                    ])
                    ->default('flowchart TD')
                    ->required()
                    ->native(false)
                    ->visible(fn (Get $get) => in_array($get('generate_target'), ['diagram', 'both'])),
                Placeholder::make('console_loading')
                    ->label('AI Architect Status')
                    ->content(new HtmlString(
                        '<div class="p-3 bg-zinc-950 border border-zinc-700 rounded-lg font-mono text-[11px] text-zinc-300 leading-normal select-none">
                                <span class="text-zinc-400">[SYSTEM]</span> AI Project Synthesis module ready.<br>
                                <span class="text-zinc-400">[SYSTEM]</span> Select target, style & confirm to execute.
                             </div>'
                    )),
            ])
            ->action(function (array $data, $livewire) {
                $title = $livewire->data['title'] ?? '';
                $currentDescription = $livewire->data['description'] ?? '';
                $target = $data['generate_target'] ?? 'both';
                $style = $data['diagram_style'] ?? 'flowchart TD';

                if (blank($title)) {
                    Notification::make()
                        ->title('Title Required')
                        ->body('Please fill in the Project Title field first.')
                        ->warning()
                        ->send();

                    return;
                }

                if ($target === 'diagram' && blank($currentDescription)) {
                    Notification::make()
                        ->title('Description Required')
                        ->body('Please write or generate a project description first.')
                        ->warning()
                        ->send();

                    return;
                }

                $skillIds = $livewire->data['skills'] ?? [];
                $techStack = '';
                if (! empty($skillIds)) {
                    $techStack = Skill::whereIn('id', $skillIds)->pluck('name')->implode(', ');
                }

                $agent = new ProjectGenerationAgent($title, $target, $techStack, $currentDescription, $style);
                $response = $agent->prompt(
                    prompt: 'Generate '.$target.' based on the project title and input parameters.',
                    provider: config('cv.provider', 'cv'),
                    model: config('cv.model'),
                );
                $result = json_decode($response->text, true);

                if (($result['error'] ?? false) === true) {
                    return;
                }

                if (is_array($result) && (isset($result['description']) || isset($result['diagram']))) {
                    $updateData = $livewire->data;

                    if (isset($result['description'])) {
                        $updateData['description'] = $result['description'];
                    }
                    if (isset($result['diagram'])) {
                        $updateData['architecture_diagram'] = $result['diagram'];
                    }

                    $livewire->form->fill($updateData);

                    Notification::make()
                        ->title('AI Generation Success')
                        ->body('Successfully generated '.$target.' for your project!')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('AI Generation Failed')
                        ->body('Failed to generate requested parts. Please try again.')
                        ->danger()
                        ->send();
                }
            });
    }
}
