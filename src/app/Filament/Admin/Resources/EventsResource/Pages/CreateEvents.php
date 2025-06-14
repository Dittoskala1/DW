<?php

namespace App\Filament\Admin\Resources\EventsResource\Pages;

use App\Filament\Admin\Resources\EventsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvents extends CreateRecord
{
    protected static string $resource = EventsResource::class;
}
