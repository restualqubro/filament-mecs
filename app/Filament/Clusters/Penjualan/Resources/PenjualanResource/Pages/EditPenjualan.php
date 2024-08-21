<?php

namespace App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {                        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {        
        $data['subtotal_products'] = (int)str_replace('.', '', $data['subtotal_products']);
        $data['totaldiscount_products'] = (int)str_replace('.', '', $data['totaldiscount_products']);
        $data['subtotal_service'] = (int)str_replace('.', '', $data['subtotal_service']);
        $data['totaldiscount_service'] = (int)str_replace('.', '', $data['totaldiscount_service']);
        $data['subtotal_component'] = (int)str_replace('.', '', $data['subtotal_component']);
        $data['total'] = (int)str_replace('.', '', $data['total']);                

        return $data;
    }
}
