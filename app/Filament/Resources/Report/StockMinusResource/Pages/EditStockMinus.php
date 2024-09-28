<?php

namespace App\Filament\Resources\Report\StockMinusResource\Pages;

use App\Filament\Resources\Report\StockMinusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockMinus extends EditRecord
{
    protected static string $resource = StockMinusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
