<?php

namespace App\Filament\Resources\Retur\ReturPembelianResource\Pages;

use App\Filament\Resources\Retur\ReturPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturPembelian extends EditRecord
{
    protected static string $resource = ReturPembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
