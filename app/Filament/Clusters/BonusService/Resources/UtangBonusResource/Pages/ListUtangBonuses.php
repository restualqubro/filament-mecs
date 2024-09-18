<?php

namespace App\Filament\Clusters\BonusService\Resources\UtangBonusResource\Pages;

use App\Filament\Clusters\BonusService\Resources\UtangBonusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUtangBonuses extends ListRecords
{
    protected static string $resource = UtangBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
