<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    /**
     * Customize the profile form schema
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    /**
     * Customize the password field component
     */
    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('New Password')
            ->helperText('Leave blank to keep your current password')
            ->dehydrated(fn($state) => filled($state))
            ->required(false);
    }

    /**
     * Customize the password confirmation field component
     */
    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->label('Confirm New Password')
            ->dehydrated(fn($state) => filled($state))
            ->required(false);
    }
}
