<?php

namespace App\Filament\Resources\Inboxes\Pages;

use App\Filament\Resources\Inboxes\InboxResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInbox extends ViewRecord
{
    protected static string $resource = InboxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply_via_email')
                ->label('Reply via Email')
                ->url(fn ($record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: '.$record->subject))
                ->openUrlInNewTab(false)
                ->icon('heroicon-o-envelope')
                ->color('primary'),
            DeleteAction::make(),
        ];
    }
}
