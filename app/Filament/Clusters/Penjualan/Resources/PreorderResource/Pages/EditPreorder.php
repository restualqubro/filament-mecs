<?php

namespace App\Filament\Clusters\Penjualan\Resources\PreorderResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PreorderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreorder extends EditRecord
{
    protected static string $resource = PreorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
