<?php

// app/Filament/Admin/Widgets/TopCategoriesChart.php

namespace App\Filament\Admin\Widgets;


use App\Models\Category;
use Filament\Widgets\ChartWidget;

class TopCategoriesChart extends ChartWidget
{
    protected static ?string $heading = 'Top Event Categories';

    protected function getData(): array
    {
        $data = Category::withCount('events')
            ->orderByDesc('events_count')
            ->take(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Events',
                    'data' => $data->pluck('events_count'),
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => $data->pluck('name'),
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
