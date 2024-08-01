<?php

namespace App\Filament\Clusters\Service\Resources\ServiceCatalogResource\Pages;

use App\Filament\Clusters\Service\Resources\ServiceCatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceCatalogs extends ListRecords
{
    protected static string $resource = ServiceCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
