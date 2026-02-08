<?php

namespace App\Filament\Resources\Inboxes\Pages;

use App\Filament\Resources\Inboxes\InboxResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInbox extends CreateRecord
{
    protected static string $resource = InboxResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
