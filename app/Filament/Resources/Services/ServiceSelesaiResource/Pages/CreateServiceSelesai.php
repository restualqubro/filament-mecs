<?php

namespace App\Filament\Resources\Services\ServiceSelesaiResource\Pages;

use App\Filament\Resources\Services\ServiceSelesaiResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceSelesai extends CreateRecord
{
    protected static string $resource = ServiceSelesaiResource::class;

    

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['teknisi_id'] = auth()->id();
        $data['subtotal_products'] = (int)str_replace('.', '', $data['subtotal_products']);
        $data['totaldiscount_products'] = (int)str_replace('.', '', $data['totaldiscount_products']);
        $data['subtotal_service'] = (int)str_replace('.', '', $data['subtotal_service']);
        $data['totaldiscount_service'] = (int)str_replace('.', '', $data['totaldiscount_service']);
        $data['subtotal_component'] = (int)str_replace('.', '', $data['subtotal_component']);
        $data['total'] = (int)str_replace('.', '', $data['total']);
    
        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('service-selesai');
    }
}
