<?php

namespace App\Filament\Clusters\Kompensasi\Resources\KompensasiDataResource\Pages;

use App\Filament\Clusters\Kompensasi\Resources\KompensasiDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompensasiData extends EditRecord
{
    protected static string $resource = KompensasiDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
