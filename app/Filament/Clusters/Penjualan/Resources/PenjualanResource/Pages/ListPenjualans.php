<?php

namespace App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
