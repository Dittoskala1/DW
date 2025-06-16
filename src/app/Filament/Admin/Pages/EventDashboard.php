<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\EventStatusChart;
use App\Filament\Admin\Widgets\TopCategoriesChart;
use Filament\Pages\Page;
use App\Filament\Admin\Widgets\EventStats;
use App\Filament\Admin\Widgets\EventsToday;
use App\Filament\Admin\Widgets\LatestEvents;
use App\Filament\Admin\Widgets\MonthlyEventsChart;   

class EventDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Events';
    protected static ?string $navigationLabel = 'Event Dashboard';
    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament.admin.pages.event-dashboard';

    public function getHeaderWidgets(): array
{
    return [
        EventStats::class,
    ];
}

public function getFooterWidgets(): array
{
    return [
        EventsToday::class,
        LatestEvents::class,
        MonthlyEventsChart::class,
        TopCategoriesChart::class,
        EventStatusChart::class,
    ];
}

}
