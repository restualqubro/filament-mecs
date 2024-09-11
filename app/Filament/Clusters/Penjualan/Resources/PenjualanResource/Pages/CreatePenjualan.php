<?php

namespace App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PenjualanResource;
use Filament\Actions;
use App\Models\Transaksi\Preorder;
use Filament\Resources\Pages\CreateRecord;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {        
        $data['tot_har'] = (int)str_replace('.', '', $data['tot_har']);
        $data['tot_disc'] = (int)str_replace('.', '', $data['tot_disc']);
        $data['tot_bayar'] = (int)str_replace('.', '', $data['tot_bayar']);
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);        
        if ($data['preorder_id']) {
            $data['tot_pr'] = (int)str_replace('.', '', $data['totaldp']);
            Preorder::where('id', $data['preorder_id'])->update([
                'status'    => 'Selesai'
            ]);
        } else {
            $data['tot_pr'] = 0;
        }
                
        return $data;        
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
