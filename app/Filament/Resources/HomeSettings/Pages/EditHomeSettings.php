<?php

namespace App\Filament\Resources\HomeSettings\Pages;

use App\Filament\Resources\HomeSettings\HomeSettingsResource;
use App\Models\HomeSettings;
use Filament\Resources\Pages\EditRecord;

class EditHomeSettings extends EditRecord
{
    protected static string $resource = HomeSettingsResource::class;

    public function mount($record = null): void
    {
        $settings = HomeSettings::first();
        if (! $settings) {
            $settings = HomeSettings::getSettings();
        }

        parent::mount($settings->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }
}
