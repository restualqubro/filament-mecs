<?php

namespace App\Filament\Clusters\Pembelian\Resources\PembelianResource\Pages;

use App\Filament\Clusters\Pembelian\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelians extends ListRecords
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
