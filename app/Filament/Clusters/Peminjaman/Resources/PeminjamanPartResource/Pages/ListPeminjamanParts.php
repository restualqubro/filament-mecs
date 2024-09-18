<?php

namespace App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource\Pages;

use App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanParts extends ListRecords
{
    protected static string $resource = PeminjamanPartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
