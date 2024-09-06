<?php

namespace App\Filament\Resources\Retur\ReturServiceResource\Pages;

use App\Filament\Resources\Retur\ReturServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturServices extends ListRecords
{
    protected static string $resource = ReturServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
