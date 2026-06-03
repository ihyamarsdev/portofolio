<?php

namespace App\Filament\Resources\Posts\Pages\Concerns;

use App\Ai\Agents\PostGenerationAgent;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait HasPostAiGeneration
{
    protected function getGenerateAiAction(): Action
    {
        return Action::make('generate_ai')
            ->label('Generate with AI')
            ->color('warning')
            ->icon('heroicon-o-sparkles')
            ->modalHeading('AI Post Generator')
            ->modalSubmitActionLabel('Generate Content')
            ->form([
                Select::make('generate_target')
                    ->label('Output')
                    ->options([
                        'content' => 'Content / Article Only',
                        'seo' => 'Excerpt & SEO Metadata Only',
                        'both' => 'Content + SEO Metadata',
                    ])
                    ->default('both')
                    ->required()
                    ->native(false)
                    ->live(),

                Select::make('persona')
                    ->label('AI Persona')
                    ->options($this->postAiPersonaOptions())
                    ->default('devops_architect')
                    ->required()
                    ->native(false)
                    ->live(),

                Textarea::make('custom_persona')
                    ->label('Custom Persona / Direction')
                    ->placeholder('Example: Write like a senior DevOps engineer explaining practical CI/CD lessons to Indonesian developers.')
                    ->rows(3)
                    ->visible(fn (Get $get): bool => $get('persona') === 'custom')
                    ->columnSpanFull(),

                TextInput::make('keywords')
                    ->label('Target Keywords')
                    ->placeholder('e.g., docker, kubernetes, terraform, ci/cd')
                    ->helperText('Comma-separated keywords, integrated naturally.')
                    ->columnSpanFull(),

                Placeholder::make('ai_status_monitor')
                    ->label('AI Status Monitor')
                    ->content(fn (Get $get) => new HtmlString($this->renderPostAiStatusMonitor(
                        (string) ($get('generate_target') ?? 'both'),
                        (string) ($get('persona') ?? 'devops_architect'),
                        (string) ($get('keywords') ?? ''),
                    )))
                    ->columnSpanFull(),
            ])
            ->action(fn (array $data, $livewire) => $this->generatePostWithAi($data, $livewire));
    }

    protected function generatePostWithAi(array $data, $livewire): void
    {
        $title = $livewire->data['title'] ?? '';
        $currentContent = $livewire->data['content'] ?? '';

        if (is_array($currentContent)) {
            $currentContent = json_encode($currentContent);
        }

        $target = $data['generate_target'] ?? 'both';
        $persona = $data['persona'] ?? 'devops_architect';
        $customPersona = $data['custom_persona'] ?? null;
        $keywords = $data['keywords'] ?? null;
        $personaPrompt = $this->resolvePostAiPersona($persona, $customPersona);

        if (blank($title)) {
            Notification::make()
                ->title('Title Required')
                ->body('Please fill in the Title field first.')
                ->warning()
                ->send();

            return;
        }

        if ($target === 'seo' && blank($currentContent)) {
            Notification::make()
                ->title('Content Required')
                ->body('Please write or generate post content before generating SEO metadata.')
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('AI Generation Started')
            ->body('Running post synthesis with persona: '.$this->postAiPersonaOptions()[$persona])
            ->info()
            ->send();

        $agent = new PostGenerationAgent($title, $target, $personaPrompt, $keywords, $currentContent);
        $response = $agent->prompt(
            prompt: 'Generate '.$target.' based on the post title and input parameters.',
            provider: config('cv.provider', 'cv'),
            model: config('cv.model'),
        );

        $result = json_decode($response->text, true);

        if (($result['error'] ?? false) === true) {
            return;
        }

        if (is_array($result) && (isset($result['content']) || isset($result['excerpt']))) {
            $updateData = $livewire->data;

            if (isset($result['content'])) {
                $updateData['content'] = $result['content'];
            }

            if (isset($result['excerpt'])) {
                $updateData['excerpt'] = Str::limit($result['excerpt'], 160, '');
            }

            if (isset($result['meta_title'])) {
                $updateData['meta_title'] = Str::limit($result['meta_title'], 60, '');
            }

            if (isset($result['meta_description'])) {
                $updateData['meta_description'] = Str::limit($result['meta_description'], 160, '');
            }

            $livewire->form->fill($updateData);

            Notification::make()
                ->title('AI Generation Complete')
                ->body('Generated '.$target.' using persona: '.$this->postAiPersonaOptions()[$persona])
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('AI Generation Failed')
            ->body('AI returned an invalid response. Please try again.')
            ->danger()
            ->send();
    }

    protected function postAiPersonaOptions(): array
    {
        return [
            'devops_architect' => 'DevOps Architect',
            'sre_practitioner' => 'SRE Practitioner',
            'cloud_engineer' => 'Cloud Engineer',
            'security_reviewer' => 'DevSecOps Security Reviewer',
            'technical_writer' => 'Technical Writer',
            'beginner_mentor' => 'Beginner-Friendly Mentor',
            'case_study' => 'Case Study Storyteller',
            'custom' => 'Custom Persona',
        ];
    }

    protected function resolvePostAiPersona(string $persona, ?string $customPersona): string
    {
        return match ($persona) {
            'devops_architect' => 'Write as a senior DevOps architect: practical, infrastructure-focused, specific about CI/CD, containers, Kubernetes, IaC, observability, reliability, and trade-offs.',
            'sre_practitioner' => 'Write as an SRE practitioner: emphasize reliability, SLOs, incident prevention, monitoring, alerting, capacity, failure modes, and operational lessons.',
            'cloud_engineer' => 'Write as a cloud engineer: focus on AWS/GCP/Azure architecture, networking, security baseline, scalability, cost, automation, and maintainability.',
            'security_reviewer' => 'Write as a DevSecOps security reviewer: highlight threat modeling, CI/CD security, secrets, scanning, policy as code, least privilege, and remediation.',
            'technical_writer' => 'Write as a polished technical writer: clear structure, concise sections, strong headings, accessible explanations, and SEO-friendly phrasing.',
            'beginner_mentor' => 'Write as a beginner-friendly mentor: explain concepts simply, include analogies, define jargon, and give practical next steps.',
            'case_study' => 'Write as a case study storyteller: frame the problem, constraints, implementation, results, lessons learned, and measurable impact.',
            'custom' => filled($customPersona) ? $customPersona : 'Write in a clear, practical technical voice.',
            default => 'Write in a clear, practical technical voice.',
        };
    }

    protected function renderPostAiStatusMonitor(string $target, string $persona, string $keywords): string
    {
        $personaLabel = e($this->postAiPersonaOptions()[$persona] ?? 'Custom Persona');
        $keywordStatus = filled($keywords) ? e($keywords) : 'none';
        $targetLabel = e($target);

        return <<<HTML
            <div
                x-data="{
                    running: false,
                    step: 0,
                    logs: [
                        { level: 'info', text: 'AI post generator ready' },
                        { level: 'config', text: 'Target: {$targetLabel}' },
                        { level: 'config', text: 'Persona: {$personaLabel}' },
                        { level: 'config', text: 'Keywords: {$keywordStatus}' },
                    ],
                    processLogs: [
                        { level: 'check', text: 'Validating title and current editor state' },
                        { level: 'check', text: 'Resolving persona instructions' },
                        { level: 'check', text: 'Composing structured JSON schema' },
                        { level: 'request', text: 'Sending request to AI provider' },
                        { level: 'wait', text: 'Waiting for model response' },
                        { level: 'parse', text: 'Parsing generated JSON payload' },
                        { level: 'done', text: 'Filling post content and SEO fields' },
                    ],
                    badgeClass(level) {
                        return {
                            info: 'bg-sky-500/10 text-sky-300 ring-sky-400/20',
                            config: 'bg-cyan-500/10 text-cyan-300 ring-cyan-400/20',
                            check: 'bg-emerald-500/10 text-emerald-300 ring-emerald-400/20',
                            request: 'bg-yellow-500/10 text-yellow-300 ring-yellow-400/20',
                            wait: 'bg-orange-500/10 text-orange-300 ring-orange-400/20',
                            parse: 'bg-violet-500/10 text-violet-300 ring-violet-400/20',
                            done: 'bg-green-500/10 text-green-300 ring-green-400/20',
                        }[level] ?? 'bg-slate-500/10 text-slate-300 ring-slate-400/20';
                    },
                    start() {
                        if (this.running) return;
                        this.running = true;
                        this.step = 0;
                        this.logs = [
                            { level: 'info', text: 'AI generation started' },
                            { level: 'config', text: 'Target: {$targetLabel}' },
                            { level: 'config', text: 'Persona: {$personaLabel}' },
                            { level: 'config', text: 'Keywords: {$keywordStatus}' },
                        ];
                        this.tick();
                    },
                    tick() {
                        if (! this.running || this.step >= this.processLogs.length) return;
                        this.logs.push(this.processLogs[this.step]);
                        this.step++;
                        this.\$nextTick(() => this.\$refs.logList.scrollTop = this.\$refs.logList.scrollHeight);
                        setTimeout(() => this.tick(), 850);
                    }
                }"
                x-init="\$root.closest('[role=dialog]')?.querySelector('button[type=submit]')?.addEventListener('click', () => start())"
                class="rounded-xl border border-slate-200 bg-white/80 shadow-sm ring-1 ring-slate-950/5 dark:border-slate-800 dark:bg-slate-950/60"
            >
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-800">
                    <div>
                        <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">AI Status Monitor</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Live generation process log</div>
                    </div>
                    <span x-show="! running" class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-600 ring-1 ring-emerald-500/20 dark:text-emerald-300">Ready</span>
                    <span x-show="running" class="rounded-full bg-yellow-500/10 px-2.5 py-1 text-xs font-medium text-yellow-700 ring-1 ring-yellow-500/20 dark:text-yellow-300">Running</span>
                </div>
                <div x-ref="logList" class="max-h-64 min-h-44 space-y-2 overflow-y-auto p-4">
                    <template x-for="(log, index) in logs" :key="index">
                        <div class="flex gap-3 rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2 text-xs dark:border-slate-800 dark:bg-slate-900/70">
                            <span class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-current" :class="badgeClass(log.level)"></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="rounded-md px-2 py-0.5 text-[10px] font-semibold uppercase ring-1" :class="badgeClass(log.level)" x-text="log.level"></span>
                                    <span class="text-[10px] text-slate-400" x-text="String(index + 1).padStart(2, '0')"></span>
                                </div>
                                <p class="mt-1 text-slate-700 dark:text-slate-200" x-text="log.text"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        HTML;
    }
}
