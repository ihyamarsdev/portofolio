<?php

namespace App\Filament\Widgets;

use App\Models\HomeSettings;
use Filament\Widgets\Widget;

class SystemStatusWidget extends Widget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 1];

    protected string $view = 'filament.widgets.system-status';

    protected function getViewData(): array
    {
        $settings = HomeSettings::getSettings();

        $services = $settings->infrastructure_services;

        if (! is_array($services) || $services === []) {
            $services = [
                ['name' => 'Application', 'status' => 'operational'],
                ['name' => 'Database', 'status' => 'operational'],
                ['name' => 'Cache', 'status' => 'operational'],
                ['name' => 'Queue', 'status' => 'operational'],
            ];
        }

        $overall = $settings->infrastructure_status ?? 'operational';

        return [
            'services' => $services,
            'overall' => $overall,
            'statusText' => $settings->infrastructure_status_text ?? 'All services running',
        ];
    }
}
