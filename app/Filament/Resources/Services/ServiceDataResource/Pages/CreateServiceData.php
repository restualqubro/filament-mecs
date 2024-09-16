<?php

namespace App\Filament\Resources\Services\ServiceDataResource\Pages;

use App\Filament\Resources\Services\ServiceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceData extends CreateRecord
{
    protected static string $resource = ServiceDataResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
