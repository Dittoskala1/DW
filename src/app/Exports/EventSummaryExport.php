<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EventSummaryExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        $rows = [];

        // Analisa umum
        $rows[] = ['Total Event', Event::count()];
        $rows[] = ['Draft Event', Event::where('status', 'draft')->count()];
        $rows[] = ['Published Event', Event::where('status', 'published')->count()];
        $rows[] = ['Archived Event', Event::where('status', 'archived')->count()];
        $rows[] = ['Pending Event', Event::where('status', 'pending')->count()];
        $rows[] = [''];

        // Event per kategori
        $rows[] = ['Event per Kategori', 'Jumlah'];
        foreach (Category::withCount('events')->get() as $category) {
            $rows[] = [$category->name, $category->events_count];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Kategori Analisa', 'Jumlah'];
    }
}
