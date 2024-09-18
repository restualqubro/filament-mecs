<?php

namespace App\Filament\Clusters\BonusService\Resources\PiutangBonusResource\Pages;

use App\Filament\Clusters\BonusService\Resources\PiutangBonusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPiutangBonuses extends ListRecords
{
    protected static string $resource = PiutangBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
