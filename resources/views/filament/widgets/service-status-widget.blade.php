<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span>Service Status</span>
                <x-filament::badge :color="$this->getOverallStatusColor()">
                    {{ $this->getOverallStatus() }}
                </x-filament::badge>
            </div>
        </x-slot>

        @if($this->getServices()->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">
                No services configured. Add services in the Service Status resource.
            </p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($this->getServices() as $service)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full animate-pulse
                                        @if($service->status === 'operational') bg-emerald-500
                                        @elseif($service->status === 'degraded') bg-yellow-500
                                        @else bg-red-500
                                        @endif
                                    "></div>
                            <div>
                                <p class="font-medium text-sm text-gray-900 dark:text-gray-100">
                                    {{ $service->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($service->status) }}
                                    @if($service->uptime_percentage)
                                        · {{ number_format($service->uptime_percentage, 1) }}% uptime
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($service->response_time_ms)
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                {{ $service->response_time_ms }}ms
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>