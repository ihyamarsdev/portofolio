<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Ai\Agents\ArchitectureDiagramAgent;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Project Information")
                ->description("Basic information about the project")
                ->schema([
                    TextInput::make("title")
                        ->required()
                        ->live()
                        ->afterStateUpdated(
                            fn(Set $set, ?string $state) => $set(
                                "slug",
                                Str::slug($state),
                            ),
                        )
                        ->maxLength(255)
                        ->helperText(
                            "The slug will be automatically generated from the title",
                        ),
                    TextInput::make("slug")
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText("URL-friendly version of the title"),
                ])
                ->columns(2),

            Section::make("Description")
                ->description(
                    "Detailed project description and technical details",
                )
                ->schema([
                    Textarea::make("description")
                        ->required()
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(false),

            Section::make("Tech Stack")
                ->description("Skills and technologies used in this project")
                ->schema([
                    Select::make("skills")
                        ->multiple()
                        ->relationship("skills", "name")
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            Section::make("Links")
                ->description("External links for the project")
                ->schema([
                    TextInput::make("repository_url")
                        ->url()
                        ->prefixIcon("heroicon-o-globe-alt")
                        ->placeholder("https://github.com/username/repo"),
                    TextInput::make("live_url")
                        ->url()
                        ->prefixIcon("heroicon-o-link")
                        ->placeholder("https://project-demo.com"),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make("Architecture Diagram")
                ->description("Generate Mermaid diagram with AI")
                ->schema([
                    Textarea::make("architecture_diagram")
                        ->rows(10)
                        ->columnSpanFull()
                        ->helperText(
                            "Enter Mermaid diagram syntax or use AI to generate one",
                        )
                        ->belowContent(
                            Action::make("generateDiagram")
                                ->label("Generate with AI")
                                ->icon("heroicon-o-sparkles")
                                ->color("primary")
                                ->action(function (Get $get, Set $set) {
                                    $description = $get("description");
                                    if (empty(trim($description ?? ""))) {
                                        Notification::make()
                                            ->title("Description Required")
                                            ->body(
                                                "Please enter a project description first.",
                                            )
                                            ->warning()
                                            ->send();

                                        return;
                                    }

                                    $agent = new ArchitectureDiagramAgent(
                                        $description,
                                    );
                                    $result = json_decode(
                                        $agent->prompt(
                                            prompt: "Please generate a Mermaid architecture diagram based on the project description provided in the instructions.",
                                            model: "gemini-2.5-flash",
                                        )->text,
                                        true,
                                    );

                                    if (isset($result["diagram"])) {
                                        $set(
                                            "architecture_diagram",
                                            $result["diagram"],
                                        );
                                        Notification::make()
                                            ->title("Diagram Generated")
                                            ->body(
                                                $result["explanation"] ??
                                                    "Architecture diagram generated successfully!",
                                            )
                                            ->success()
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title("Error")
                                            ->body(
                                                "Failed to generate diagram. Please try again.",
                                            )
                                            ->danger()
                                            ->send();
                                    }
                                }),
                        ),
                ])
                ->collapsible()
                ->collapsed(),

            Section::make("Settings")
                ->description("Project display settings")
                ->schema([
                    Toggle::make("is_featured")
                        ->label("Featured Project")
                        ->helperText("Show this project on the homepage")
                        ->default(false)
                        ->columnSpan(1),
                    TextInput::make("order")
                        ->label("Display Order")
                        ->helperText("Lower numbers appear first")
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->columnSpan(1),
                ])
                ->columns(2)
                ->collapsible(),
        ]);
    }
}
