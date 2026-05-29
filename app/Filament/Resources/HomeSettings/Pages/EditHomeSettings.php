<?php

namespace App\Filament\Resources\HomeSettings\Pages;

use App\Filament\Resources\HomeSettings\HomeSettingsResource;
use App\Models\HomeSettings;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class EditHomeSettings extends Page
{
    protected static string $resource = HomeSettingsResource::class;

    protected string $view = 'filament.pages.manage-home-settings';

    protected static ?string $title = 'Home Page Settings';

    // State properties
    public ?string $site_brand = '';
    public ?string $site_title = '';
    public ?string $meta_description = '';
    public ?string $footer_text = '';

    public ?string $hero_greeting = '';
    public ?string $hero_description = '';
    public ?string $availability_status = '';
    public bool $availability_active = true;
    public array $terminal_commands = [];
    public string $new_command = '';

    public ?string $tech_stack_title = '';
    public ?string $tech_stack_description = '';
    public ?string $featured_projects_title = '';
    public ?string $featured_projects_description = '';
    public ?string $cta_title = '';
    public ?string $cta_description = '';
    public ?string $cta_primary_button_text = '';
    public ?string $cta_secondary_button_text = '';

    public ?string $infrastructure_title = '';
    public ?string $infrastructure_description = '';
    public ?string $infrastructure_status_text = '';
    public string $infrastructure_status = 'operational';
    public array $infrastructure_services = [];

    public string $activeTab = 'branding'; // Options: branding, hero, section_copy, infrastructure, preview

    public function mount(): void
    {
        $settings = HomeSettings::first();
        if (! $settings) {
            $settings = HomeSettings::getSettings();
        }

        // Branding
        $this->site_brand = $settings->site_brand;
        $this->site_title = $settings->site_title;
        $this->meta_description = $settings->meta_description;
        $this->footer_text = $settings->footer_text;

        // Hero Section
        $this->hero_greeting = $settings->hero_greeting;
        $this->hero_description = $settings->hero_description;
        $this->availability_status = $settings->availability_status;
        $this->availability_active = (bool) $settings->availability_active;
        $this->terminal_commands = $settings->terminal_commands ?? [];

        // Section copies
        $this->tech_stack_title = $settings->tech_stack_title;
        $this->tech_stack_description = $settings->tech_stack_description;
        $this->featured_projects_title = $settings->featured_projects_title;
        $this->featured_projects_description = $settings->featured_projects_description;
        $this->cta_title = $settings->cta_title;
        $this->cta_description = $settings->cta_description;
        $this->cta_primary_button_text = $settings->cta_primary_button_text;
        $this->cta_secondary_button_text = $settings->cta_secondary_button_text;

        // Infrastructure
        $this->infrastructure_title = $settings->infrastructure_title;
        $this->infrastructure_description = $settings->infrastructure_description;
        $this->infrastructure_status_text = $settings->infrastructure_status_text;
        $this->infrastructure_status = $settings->infrastructure_status ?? 'operational';
        $this->infrastructure_services = $settings->infrastructure_services ?? [];
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function addCommand(): void
    {
        if (trim($this->new_command) !== '') {
            $this->terminal_commands[] = trim($this->new_command);
            $this->new_command = '';
        }
    }

    public function removeCommand(int $index): void
    {
        if (isset($this->terminal_commands[$index])) {
            unset($this->terminal_commands[$index]);
            $this->terminal_commands = array_values($this->terminal_commands);
        }
    }

    public function saveSettings(): void
    {
        $this->validate([
            'site_brand' => 'required|string|max:255',
            'site_title' => 'required|string|max:255',
            'meta_description' => 'nullable|string',
            'footer_text' => 'required|string|max:255',
            'hero_greeting' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'availability_status' => 'required|string|max:255',
            'tech_stack_title' => 'required|string|max:255',
            'tech_stack_description' => 'required|string|max:255',
            'featured_projects_title' => 'required|string|max:255',
            'featured_projects_description' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_description' => 'required|string',
            'cta_primary_button_text' => 'required|string|max:255',
            'cta_secondary_button_text' => 'required|string|max:255',
        ]);

        $settings = HomeSettings::first() ?? new HomeSettings();

        $settings->fill([
            'site_brand' => $this->site_brand,
            'site_title' => $this->site_title,
            'meta_description' => $this->meta_description,
            'footer_text' => $this->footer_text,
            'hero_greeting' => $this->hero_greeting,
            'hero_description' => $this->hero_description,
            'availability_status' => $this->availability_status,
            'availability_active' => $this->availability_active,
            'terminal_commands' => $this->terminal_commands,
            'tech_stack_title' => $this->tech_stack_title,
            'tech_stack_description' => $this->tech_stack_description,
            'featured_projects_title' => $this->featured_projects_title,
            'featured_projects_description' => $this->featured_projects_description,
            'cta_title' => $this->cta_title,
            'cta_description' => $this->cta_description,
            'cta_primary_button_text' => $this->cta_primary_button_text,
            'cta_secondary_button_text' => $this->cta_secondary_button_text,
            'infrastructure_title' => $this->infrastructure_title,
            'infrastructure_description' => $this->infrastructure_description,
            'infrastructure_status_text' => $this->infrastructure_status_text,
            'infrastructure_status' => $this->infrastructure_status,
            'infrastructure_services' => $this->infrastructure_services,
        ]);

        $settings->save();

        Notification::make()
            ->title('Homepage Settings Updated')
            ->body('All settings have been successfully synchronized.')
            ->success()
            ->send();
    }
}
