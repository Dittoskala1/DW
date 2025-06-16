<?php

namespace App\Filament\Admin\Resources\AudienceResource\Pages;

use App\Filament\Admin\Resources\AudienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAudiences extends ListRecords
{
    protected static string $resource = AudienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
