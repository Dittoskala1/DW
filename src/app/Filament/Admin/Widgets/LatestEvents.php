<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;


class LatestEvents extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (fn() => Event::query()->latest()->limit(5))

            )
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('start_date')->label('Date')->date(),
                Tables\Columns\TextColumn::make('start_time')->label('Time')->time(),
                Tables\Columns\TextColumn::make('status')->badge(),
            ]);
    }
    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.event-dashboard');
    }
}