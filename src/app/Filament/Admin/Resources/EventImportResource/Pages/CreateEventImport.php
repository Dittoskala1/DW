<?php

namespace App\Filament\Admin\Resources\EventImportResource\Pages;

use App\Filament\Admin\Resources\EventImportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventImport extends CreateRecord
{
    protected static string $resource = EventImportResource::class;
}
