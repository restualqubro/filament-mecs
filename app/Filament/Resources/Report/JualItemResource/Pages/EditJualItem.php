<?php

namespace App\Filament\Resources\Report\JualItemResource\Pages;

use App\Filament\Resources\Report\JualItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJualItem extends EditRecord
{
    protected static string $resource = JualItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
