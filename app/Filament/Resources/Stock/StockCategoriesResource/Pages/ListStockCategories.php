<?php

namespace App\Filament\Resources\Stock\StockCategoriesResource\Pages;

use App\Filament\Resources\Stock\StockCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockCategories extends ListRecords
{
    protected static string $resource = StockCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
