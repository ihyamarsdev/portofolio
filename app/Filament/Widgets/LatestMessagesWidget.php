<?php

namespace App\Filament\Widgets;

use App\Models\Inbox;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestMessagesWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 1];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest Messages')
            ->description('Fresh inquiries from the contact form')
            ->query(
                Inbox::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('warning'),
                TextColumn::make('name')
                    ->weight('bold')
                    ->description(fn (Inbox $record) => $record->email),
                TextColumn::make('subject')
                    ->limit(38)
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
