<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SvgIconPicker extends Component
{
    public string $search = '';
    public int $perPage = 48;
    public bool $showModal = false;

    public function getIconsProperty(): array
    {
        $icons = Cache::remember('thesvg.registry', 3600, function () {
            try {
                $response = Http::timeout(10)
                    ->get('https://cdn.jsdelivr.net/gh/glincker/thesvg@main/src/data/icons.json');
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                // Return empty array on failure
            }
            return [];
        });

        if (!is_array($icons)) {
            $icons = [];
        }

        if ($this->search !== '') {
            $q = strtolower($this->search);
            $icons = array_filter($icons, function ($icon) use ($q) {
                return str_contains(strtolower($icon['title'] ?? ''), $q)
                    || str_contains(strtolower($icon['slug'] ?? ''), $q)
                    || str_contains(strtolower(implode(' ', $icon['aliases'] ?? [])), $q);
            });
        }

        return array_values($icons);
    }

    public function loadMore(): void
    {
        $this->perPage += 48;
    }

    public function select(string $slug): void
    {
        $url = "https://cdn.jsdelivr.net/gh/glincker/thesvg@main/public/icons/{$slug}/default.svg";
        $this->dispatch('svg-icon-selected', url: $url);
        $this->search = '';
        $this->perPage = 48;
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.svg-icon-picker');
    }
}
