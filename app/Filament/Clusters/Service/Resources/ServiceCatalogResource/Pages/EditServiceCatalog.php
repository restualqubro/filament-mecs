<?php

namespace App\Filament\Clusters\Service\Resources\ServiceCatalogResource\Pages;

use App\Filament\Clusters\Service\Resources\ServiceCatalogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCatalog extends EditRecord
{
    protected static string $resource = ServiceCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
