<?php

namespace App\Filament\Resources\Stock\StockinResource\Pages;

use App\Filament\Resources\Stock\StockinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockin extends EditRecord
{
    protected static string $resource = StockinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
