<?php

namespace App\Filament\Resources\Connect\EkspedisiResource\Pages;

use App\Filament\Resources\Connect\EkspedisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEkspedisi extends EditRecord
{
    protected static string $resource = EkspedisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
