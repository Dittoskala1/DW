<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EventExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Event::with(['category', 'organizer', 'location', 'audience'])
            ->get()
            ->map(function ($event) {
                return [
                    'Title'       => $event->title,
                    'Description' => $event->description,
                    'Start Date'  => $event->start_date,
                    'Start Time'  => $event->start_time,
                    'End Date'    => $event->end_date,
                    'End Time'    => $event->end_time,
                    'Status'      => $event->status,
                    'Category'    => $event->category?->name ?? '-',
                    'Organizer'   => $event->organizer?->name ?? '-',
                    'Location'    => $event->location?->venue_name ?? '-',
                    'Audience'    => $event->audience?->name ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Start Date',
            'Start Time',
            'End Date',
            'End Time',
            'Status',
            'Category',
            'Organizer',
            'Location',
            'Audience',
        ];
    }
}
