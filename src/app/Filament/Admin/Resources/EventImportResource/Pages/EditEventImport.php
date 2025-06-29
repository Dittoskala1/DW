<?php

namespace App\Filament\Admin\Resources\EventImportResource\Pages;

use App\Filament\Admin\Resources\EventImportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventImport extends EditRecord
{
    protected static string $resource = EventImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
