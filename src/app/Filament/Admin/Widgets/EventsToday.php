<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;

class EventsToday extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()->whereDate('start_date', now()->toDateString())
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('start_time')->label('Start')->time(),
                Tables\Columns\TextColumn::make('location.venue_name')->label('Location'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ]);
    }

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.event-dashboard');
    }
}
