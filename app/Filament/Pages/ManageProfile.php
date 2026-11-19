<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Schemas\ProfileForm;
use App\Models\Profile;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageProfile extends Page
{
    protected static bool $shouldRegisterNavigation = true;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedUserCircle;
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return 'Settings';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    protected string $view = 'filament.pages.manage-profile';

    protected static ?string $title = 'Profile Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $profile = Profile::first();

        if ($profile) {
            $this->form->fill($profile->attributesToArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(ProfileForm::configure($schema)->getComponents())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $profile = Profile::first() ?? new Profile;
        $profile->fill($data);
        $profile->save();

        Notification::make()
            ->success()
            ->title('Profile updated successfully')
            ->send();
    }
}
