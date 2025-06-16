<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Widget;
class MonthlyEventsChart extends ChartWidget
{
    protected static ?string $heading = 'Events Created Per Month';

    protected function getData(): array
    {
        $monthlyEvents = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data = [];
        $labels = [];

        foreach (range(1, 12) as $month) {
            $labels[] = date('F', mktime(0, 0, 0, $month, 10));
            $data[] = $monthlyEvents[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    
    public static function canView(): bool
    {
        return request()->routeIs('filament.admin.pages.event-dashboard');
    }

    
}
