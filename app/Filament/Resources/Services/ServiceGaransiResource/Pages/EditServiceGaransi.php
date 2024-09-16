<?php

namespace App\Filament\Resources\Services\ServiceGaransiResource\Pages;

use App\Filament\Resources\Services\ServiceGaransiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceGaransi extends EditRecord
{
    protected static string $resource = ServiceGaransiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
