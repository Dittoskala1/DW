<?php

namespace App\Filament\Admin\Resources\EventResource\Pages;

use App\Exports\EventExport;
use App\Filament\Admin\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('export')
                ->label('Export to Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return response()->streamDownload(function () {
                        echo Excel::raw(new EventExport(), \Maatwebsite\Excel\Excel::XLSX);
                    }, 'events.xlsx');
                }),
        ];
    }
}
