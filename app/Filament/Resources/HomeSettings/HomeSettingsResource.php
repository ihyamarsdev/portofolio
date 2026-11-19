<?php

namespace App\Filament\Resources\HomeSettings;

use App\Filament\Resources\HomeSettings\Pages\EditHomeSettings;
use App\Filament\Resources\HomeSettings\Schemas\HomeSettingsForm;
use App\Models\HomeSettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class HomeSettingsResource extends Resource
{
    protected static ?string $model = HomeSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return HomeSettingsForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditHomeSettings::route('/'),
        ];
    }
}
