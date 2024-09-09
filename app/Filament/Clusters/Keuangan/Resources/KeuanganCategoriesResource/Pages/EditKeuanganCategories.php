<?php

namespace App\Filament\Clusters\Keuangan\Resources\KeuanganCategoriesResource\Pages;

use App\Filament\Clusters\Keuangan\Resources\KeuanganCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeuanganCategories extends EditRecord
{
    protected static string $resource = KeuanganCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
