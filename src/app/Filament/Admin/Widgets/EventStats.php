<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Events', Event::count()),
            Stat::make('Published', Event::where('status', 'published')->count()),
            Stat::make('Draft', Event::where('status', 'draft')->count()),
        ];
    }
}