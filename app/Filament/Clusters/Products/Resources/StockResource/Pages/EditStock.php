<?php

namespace App\Filament\Clusters\Products\Resources\StockResource\Pages;

use App\Filament\Clusters\Products\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStock extends EditRecord
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
