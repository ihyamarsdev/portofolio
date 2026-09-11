@php
    $statusLabel = match ($overall) {
        'operational', 'healthy', 'ok' => 'Operational',
        'degraded', 'partial' => 'Degraded',
        'down', 'outage', 'critical' => 'Down',
        default => ucfirst((string) $overall),
    };

    $statusClass = match ($overall) {
        'operational', 'healthy', 'ok' => 'is-operational',
        'degraded', 'partial' => 'is-degraded',
        'down', 'outage', 'critical' => 'is-down',
        default => '',
    };
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        heading="System Status"
        description="{{ $statusText }}"
    >
        <div class="system-status">
            <div class="system-status-overall {{ $statusClass }}">
                <span class="system-status-dot" aria-hidden="true"></span>
                <div>
                    <div class="system-status-overall-label">{{ $statusLabel }}</div>
                    <div class="system-status-overall-sub">Current infrastructure health</div>
                </div>
            </div>

            <ul class="system-status-list">
                @foreach ($services as $service)
                    @php
                        $serviceStatus = $service['status'] ?? 'operational';
                        $isUp = in_array($serviceStatus, ['operational', 'healthy', 'ok', 'up', 'running'], true);
                        $isWarn = in_array($serviceStatus, ['degraded', 'partial', 'warning'], true);
                    @endphp
                    <li class="system-status-item">
                        <span class="system-status-dot {{ $isUp ? 'is-operational' : ($isWarn ? 'is-degraded' : 'is-down') }}" aria-hidden="true"></span>
                        <span class="system-status-name">{{ $service['name'] ?? 'Service' }}</span>
                        <span class="system-status-badge {{ $isUp ? 'is-operational' : ($isWarn ? 'is-degraded' : 'is-down') }}">
                            {{ ucfirst($serviceStatus) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
