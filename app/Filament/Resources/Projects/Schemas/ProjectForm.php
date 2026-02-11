<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Ai\Agents\ArchitectureDiagramAgent;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('skills')
                    ->multiple()
                    ->relationship('skills', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                TextInput::make('repository_url')
                    ->url(),
                TextInput::make('live_url')
                    ->url(),
                Textarea::make('architecture_diagram')
                    ->rows(10)
                    ->columnSpanFull()
                    ->belowContent(
                        Action::make('generateDiagram')
                            ->label('Generate with AI')
                            ->icon('heroicon-o-sparkles')
                            ->color('primary')
                            ->action(function (Get $get, Set $set) {
                                $description = $get('description');
                                if (empty(trim($description ?? ''))) {
                                    Notification::make()->title('Description Required')->body('Please enter a project description first.')->warning()->send();
                                    return;
                                }

                                $agent = new ArchitectureDiagramAgent($description);
                                $result = json_decode($agent->prompt(
                                    prompt: 'Please generate a Mermaid architecture diagram based on the project description provided in the instructions.',
                                    model: 'gemini-2.5-flash'
                                )->text, true);

                                if (isset($result['diagram'])) {
                                    $set('architecture_diagram', $result['diagram']);
                                    Notification::make()->title('Diagram Generated')->body($result['explanation'] ?? 'Architecture diagram generated successfully!')->success()->send();
                                } else {
                                    Notification::make()->title('Error')->body('Failed to generate diagram. Please try again.')->danger()->send();
                                }
                            })
                    ),
                Toggle::make('is_featured')->required(),
                TextInput::make('order')->required()->numeric()->default(0),
            ]);
    }
}
