<?php

namespace App\Filament\Resources\Retur\ReturPenjualanResource\Pages;

use App\Filament\Resources\Retur\ReturPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturPenjualan extends EditRecord
{
    protected static string $resource = ReturPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
