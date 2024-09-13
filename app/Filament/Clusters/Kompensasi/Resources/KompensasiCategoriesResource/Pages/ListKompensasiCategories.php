<?php

namespace App\Filament\Clusters\Kompensasi\Resources\KompensasiCategoriesResource\Pages;

use App\Filament\Clusters\Kompensasi\Resources\KompensasiCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKompensasiCategories extends ListRecords
{
    protected static string $resource = KompensasiCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
