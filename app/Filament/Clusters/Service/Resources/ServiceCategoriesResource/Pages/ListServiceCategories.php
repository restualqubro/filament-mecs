<?php

namespace App\Filament\Clusters\Service\Resources\ServiceCategoriesResource\Pages;

use App\Filament\Clusters\Service\Resources\ServiceCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceCategories extends ListRecords
{
    protected static string $resource = ServiceCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
