<?php

namespace App\Filament\Clusters\Service\Resources\CategoriesResource\Pages;

use App\Filament\Clusters\Service\Resources\CategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategories extends EditRecord
{
    protected static string $resource = CategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
