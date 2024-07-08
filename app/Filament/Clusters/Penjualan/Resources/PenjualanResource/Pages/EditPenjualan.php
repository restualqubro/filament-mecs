<?php

namespace App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
