<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('generate_ai')
                ->label('⚡ Generate with AI')
                ->color('warning')
                ->icon('heroicon-o-sparkles')
                ->form([
                    \Filament\Forms\Components\Select::make('generate_target')
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
                    \Filament\Forms\Components\Select::make('diagram_style')
                        ->label('Diagram Style')
                        ->options([
                            'flowchart TD' => '📊 Top-Down Flowchart',
                            'flowchart LR' => '➡️ Left-Right Flowchart',
                            'graph TD' => '🌐 Network Graph (Top-Down)',
                        ])
                        ->default('flowchart TD')
                        ->required()
                        ->native(false)
                        ->visible(fn (\Filament\Forms\Components\Utilities\Get $get) => in_array($get('generate_target'), ['diagram', 'both'])),
                    \Filament\Forms\Components\Placeholder::make('console_loading')
                        ->label('AI Architect Status')
                        ->content(new \Illuminate\Support\HtmlString(
                            '<div class="p-3 bg-[#09090d] border border-cyan-500/20 rounded-lg font-mono text-[11px] text-cyan-400 leading-normal select-none">
                                <span class="text-yellow-500 animate-pulse">[SYSTEM]</span> AI Project Synthesis module ready.<br>
                                <span class="text-yellow-500 animate-pulse">[SYSTEM]</span> Select target, style & confirm to execute.
                             </div>'
                        ))
                ])
                ->action(function (array $data, $livewire) {
                    $title = $livewire->data['title'] ?? '';
                    $currentDescription = $livewire->data['description'] ?? '';
                    $target = $data['generate_target'] ?? 'both';
                    $style = $data['diagram_style'] ?? 'flowchart TD';

                    if (blank($title)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Title Required')
                            ->body('Please fill in the Project Title field first.')
                            ->warning()
                            ->send();
                        return;
                    }

                    if ($target === 'diagram' && blank($currentDescription)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Description Required')
                            ->body('Please write or generate a project description first.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $skillIds = $livewire->data['skills'] ?? [];
                    $techStack = '';
                    if (! empty($skillIds)) {
                        $techStack = \App\Models\Skill::whereIn('id', $skillIds)->pluck('name')->implode(', ');
                    }

                    $agent = new \App\Ai\Agents\ProjectGenerationAgent($title, $target, $techStack, $currentDescription, $style);
                    $result = json_decode(
                        $agent->prompt(
                            prompt: 'Generate ' . $target . ' based on the project title and input parameters.',
                            provider: config('cv.provider', 'cv'),
                            model: config('cv.model'),
                        )->text,
                        true
                    );

                    if (is_array($result) && (isset($result['description']) || isset($result['diagram']))) {
                        $updateData = $livewire->data;

                        if (isset($result['description'])) {
                            $updateData['description'] = $result['description'];
                        }
                        if (isset($result['diagram'])) {
                            $updateData['architecture_diagram'] = $result['diagram'];
                        }

                        $livewire->form->fill($updateData);

                        \Filament\Notifications\Notification::make()
                            ->title('AI Generation Success')
                            ->body('Successfully generated ' . $target . ' for your project!')
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('AI Generation Failed')
                            ->body('Failed to generate requested parts. Please try again.')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
