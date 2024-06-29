<?php

namespace App\Filament\Resources\Stock\StockCategoriesResource\Pages;

use App\Filament\Resources\Stock\StockCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockCategories extends EditRecord
{
    protected static string $resource = StockCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
