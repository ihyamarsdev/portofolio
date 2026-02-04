<?php

namespace App\Filament\Widgets;

use App\Models\ServiceStatus;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ServiceStatusWidget extends Widget
{
    protected string $view = 'filament.widgets.service-status-widget';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getServices(): Collection
    {
        return ServiceStatus::orderBy('name')->get();
    }

    public function getOverallStatus(): string
    {
        $services = ServiceStatus::all();

        if ($services->isEmpty()) {
            return 'No services configured';
        }

        $allOperational = $services->every(fn($s) => $s->status === 'operational');
        $anyDown = $services->contains(fn($s) => $s->status === 'down');

        if ($allOperational) {
            return 'All Systems Operational';
        } elseif ($anyDown) {
            return 'System Outage Detected';
        } else {
            return 'Partial System Degradation';
        }
    }

    public function getOverallStatusColor(): string
    {
        $services = ServiceStatus::all();

        if ($services->isEmpty()) {
            return 'gray';
        }

        $allOperational = $services->every(fn($s) => $s->status === 'operational');
        $anyDown = $services->contains(fn($s) => $s->status === 'down');

        if ($allOperational) {
            return 'success';
        } elseif ($anyDown) {
            return 'danger';
        } else {
            return 'warning';
        }
    }
}
