<?php

namespace App\Filament\Resources\Transaksi\PembelianResource\Pages;

use App\Filament\Resources\Transaksi\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
