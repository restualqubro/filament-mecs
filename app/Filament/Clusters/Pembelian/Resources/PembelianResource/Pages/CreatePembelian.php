<?php

namespace App\Filament\Clusters\Pembelian\Resources\PembelianResource\Pages;

use App\Filament\Clusters\Pembelian\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {        
        $data['tot_har'] = (int)str_replace('.', '', $data['tot_har']);        
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);               
                
        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
