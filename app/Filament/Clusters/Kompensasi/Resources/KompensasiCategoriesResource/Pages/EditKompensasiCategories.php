<?php

namespace App\Filament\Clusters\Kompensasi\Resources\KompensasiCategoriesResource\Pages;

use App\Filament\Clusters\Kompensasi\Resources\KompensasiCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompensasiCategories extends EditRecord
{
    protected static string $resource = KompensasiCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
