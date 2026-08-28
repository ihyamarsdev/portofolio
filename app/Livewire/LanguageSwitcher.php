<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public array $locales = [
        'en' => 'EN',
        'id' => 'ID',
    ];

    public function mount(): void
    {
        $this->currentLocale = Session::get('locale', App::getLocale());
    }

    public function switch(string $locale): void
    {
        if (! in_array($locale, array_keys($this->locales), true)) {
            return;
        }

        Session::put('locale', $locale);
        $this->currentLocale = $locale;

        // Reload so the SetLocale middleware applies the new locale.
        $this->js('window.location.reload()');
    }

    public function render(): View
    {
        return view('livewire.language-switcher');
    }
}
