<?php

namespace App\Filament\Clusters\Keuangan\Resources\KeuanganCategoriesResource\Pages;

use App\Filament\Clusters\Keuangan\Resources\KeuanganCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeuanganCategories extends ListRecords
{
    protected static string $resource = KeuanganCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
