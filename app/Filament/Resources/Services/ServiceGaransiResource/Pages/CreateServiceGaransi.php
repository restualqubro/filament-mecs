<?php

namespace App\Filament\Resources\Services\ServiceGaransiResource\Pages;

use App\Filament\Resources\Services\ServiceGaransiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceGaransi extends CreateRecord
{
    protected static string $resource = ServiceGaransiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'Baru';
        $data['user_id'] = auth()->id();
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
