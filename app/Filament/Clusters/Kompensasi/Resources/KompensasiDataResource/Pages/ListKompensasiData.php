<?php

namespace App\Filament\Clusters\Kompensasi\Resources\KompensasiDataResource\Pages;

use App\Filament\Clusters\Kompensasi\Resources\KompensasiDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKompensasiData extends ListRecords
{
    protected static string $resource = KompensasiDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
