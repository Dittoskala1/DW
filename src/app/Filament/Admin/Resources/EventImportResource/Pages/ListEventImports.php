<?php

namespace App\Filament\Admin\Resources\EventImportResource\Pages;

use App\Filament\Admin\Resources\EventImportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventImports extends ListRecords
{
    protected static string $resource = EventImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
