<?php

namespace App\Filament\Admin\Resources\EventRegistrationResource\Pages;

use App\Filament\Admin\Resources\EventRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventRegistration extends CreateRecord
{
    protected static string $resource = EventRegistrationResource::class;
}
