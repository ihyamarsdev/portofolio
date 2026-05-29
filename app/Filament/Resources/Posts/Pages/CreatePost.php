<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

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
                            'content' => '📝 Content / Article Only',
                            'seo' => '🔍 Excerpt & SEO Metadata Only',
                            'both' => '✨ Both Content & SEO Metadata',
                        ])
                        ->default('both')
                        ->required()
                        ->native(false)
                        ->live(),
                    \Filament\Forms\Components\Select::make('tone')
                        ->label('AI Persona / Tone')
                        ->options([
                            'cyberpunk' => '✨ Neon Cyberpunk (Analytical, edgy, tech-focused)',
                            'professional' => '👔 Enterprise Architect (Formal, authoritative, sleek)',
                            'casual' => '⚡ Casual Hacker (Conversational, plain, punchy)',
                        ])
                        ->default('cyberpunk')
                        ->required()
                        ->native(false),
                    \Filament\Forms\Components\TextInput::make('keywords')
                        ->label('Target Keywords')
                        ->placeholder('e.g., laravel, docker, devops, kubernetes')
                        ->helperText('Add comma-separated keywords you want the AI to naturally target.'),
                    \Filament\Forms\Components\Placeholder::make('console_loading')
                        ->label('AI Status Monitor')
                        ->content(new \Illuminate\Support\HtmlString(
                            '<div class="p-3 bg-[#09090d] border border-cyan-500/20 rounded-lg font-mono text-[11px] text-cyan-400 leading-normal select-none">
                                <span class="text-yellow-500 animate-pulse">[SYSTEM]</span> AI Post Synthesis module ready.<br>
                                <span class="text-yellow-500 animate-pulse">[SYSTEM]</span> Select target, persona & keywords above to execute.
                             </div>'
                        ))
                ])
                ->action(function (array $data, $livewire) {
                    $title = $livewire->data['title'] ?? '';
                    $currentContent = $livewire->data['content'] ?? '';
                    if (is_array($currentContent)) {
                        $currentContent = json_encode($currentContent);
                    }
                    $target = $data['generate_target'] ?? 'both';
                    $tone = $data['tone'] ?? 'cyberpunk';
                    $keywords = $data['keywords'] ?? null;

                    if (blank($title)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Title Required')
                            ->body('Please fill in the Title field first.')
                            ->warning()
                            ->send();
                        return;
                    }

                    if ($target === 'seo' && blank($currentContent)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Content Required')
                            ->body('Please write or generate post content before generating SEO metadata.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $agent = new \App\Ai\Agents\PostGenerationAgent($title, $target, $tone, $keywords, $currentContent);
                    $result = json_decode(
                        $agent->prompt(
                            prompt: 'Generate ' . $target . ' based on the post title and input parameters.',
                            provider: config('cv.provider', 'cv'),
                            model: config('cv.model'),
                        )->text,
                        true
                    );

                    if (is_array($result) && (isset($result['content']) || isset($result['excerpt']))) {
                        $updateData = $livewire->data;

                        if (isset($result['content'])) {
                            $updateData['content'] = $result['content'];
                        }
                        if (isset($result['excerpt'])) {
                            $updateData['excerpt'] = \Illuminate\Support\Str::limit($result['excerpt'], 160, '');
                        }
                        if (isset($result['meta_title'])) {
                            $updateData['meta_title'] = \Illuminate\Support\Str::limit($result['meta_title'], 60, '');
                        }
                        if (isset($result['meta_description'])) {
                            $updateData['meta_description'] = \Illuminate\Support\Str::limit($result['meta_description'], 160, '');
                        }

                        $livewire->form->fill($updateData);

                        \Filament\Notifications\Notification::make()
                            ->title('AI Generation Success')
                            ->body('Successfully generated ' . $target . ' for your post!')
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

    public function getMaxContentWidth(): string|Width|null
    {
        return 'full';
    }
}
