<?php

namespace App\Filament\Resources\Connect\EkspedisiResource\Pages;

use App\Filament\Resources\Connect\EkspedisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEkspedisis extends ListRecords
{
    protected static string $resource = EkspedisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
