<?php

namespace App\Filament\Clusters\Penjualan\Resources\PreorderResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PreorderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePreorder extends CreateRecord
{
    protected static string $resource = PreorderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'Baru';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
