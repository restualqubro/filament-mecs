<?php

namespace App\Filament\Clusters\BonusService\Resources\BonusDataResource\Pages;

use App\Filament\Clusters\BonusService\Resources\BonusDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBonusData extends ListRecords
{
    protected static string $resource = BonusDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
