<?php

namespace App\Filament\Admin\Resources\EventsResource\Pages;

use App\Filament\Admin\Resources\EventsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvents extends EditRecord
{
    protected static string $resource = EventsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
