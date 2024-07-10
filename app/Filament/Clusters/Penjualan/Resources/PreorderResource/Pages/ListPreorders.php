<?php

namespace App\Filament\Clusters\Penjualan\Resources\PreorderResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PreorderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreorders extends ListRecords
{
    protected static string $resource = PreorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
