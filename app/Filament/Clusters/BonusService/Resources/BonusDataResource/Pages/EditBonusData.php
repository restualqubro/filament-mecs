<?php

namespace App\Filament\Clusters\BonusService\Resources\BonusDataResource\Pages;

use App\Filament\Clusters\BonusService\Resources\BonusDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBonusData extends EditRecord
{
    protected static string $resource = BonusDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
