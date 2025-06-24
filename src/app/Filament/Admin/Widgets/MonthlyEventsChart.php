<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;

class MonthlyEventsChart extends ChartWidget
{
    protected static ?string $heading = 'Events per Start Month';

    protected function getData(): array
    {
        $monthlyEvents = Event::selectRaw('MONTH(start_date) as month, COUNT(*) as count')
            ->whereNotNull('start_date')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data = [];
        $labels = [];

        foreach (range(1, 12) as $month) {
            $labels[] = date('F', mktime(0, 0, 0, $month, 10)); // Nama bulan
            $data[] = $monthlyEvents[$month] ?? 0; // Isi data atau 0
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
