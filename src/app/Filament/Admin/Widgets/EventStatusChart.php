<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;

class EventStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Event Status Distribution';

    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.event-dashboard');
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $data = Event::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'datasets' => [
                [
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#10B981', // published
                        '#3B82F6', // draft
                        '#F59E0B', // pending
                        '#EF4444', // archived
                    ],
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 1; 
    }

    public function getColumnStart(): int|string|array
    {
        return 1; 
    }

    
}
