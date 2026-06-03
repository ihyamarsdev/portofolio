<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\Pages\Concerns\HasPostAiGeneration;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPost extends EditRecord
{
    use HasPostAiGeneration;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getGenerateAiAction(),
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        return $data;
    }

    public function getMaxContentWidth(): string|Width|null
    {
        return 'full';
    }
}
