<?php

namespace App\Filament\Resources\ServiceDataResource\Pages;

use App\Filament\Resources\ServiceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceData extends ListRecords
{
    protected static string $resource = ServiceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
