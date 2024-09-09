<?php

namespace App\Filament\Clusters\Keuangan\Resources\PemasukanResource\Pages;

use App\Filament\Clusters\Keuangan\Resources\PemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemasukan extends EditRecord
{
    protected static string $resource = PemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
