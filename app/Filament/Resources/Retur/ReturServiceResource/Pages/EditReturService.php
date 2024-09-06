<?php

namespace App\Filament\Resources\Retur\ReturServiceResource\Pages;

use App\Filament\Resources\Retur\ReturServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturService extends EditRecord
{
    protected static string $resource = ReturServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
