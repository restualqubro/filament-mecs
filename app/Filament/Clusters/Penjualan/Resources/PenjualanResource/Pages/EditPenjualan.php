<?php

namespace App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;

use App\Filament\Clusters\Penjualan\Resources\PenjualanResource;
use Filament\Actions;
use App\Models\Transaksi\Preorder;
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
        $subtotal = $data['tot_har'] - $data['tot_disc'];
        $data['totaldp'] = number_format($data['tot_pr'], 0, '', '.');
        $data['nominal_dp'] = number_format($data['tot_pr'], 0, '', '.');
        $data['subtotal'] = number_format($subtotal, 0, '', '.');
        $data['tot_disc'] = number_format($data['tot_disc'], 0, '', '.');
        $data['tot_har'] = number_format($data['tot_har'], 0, '', '.');
        $data['sisa'] = number_format($data['sisa'], 0, '', '.');
        if ($data['preorder_id']) {
            $data['tot_pr'] = (int)str_replace('.', '', $data['totaldp']);
            Preorder::where('id', $data['preorder_id'])->update([
                'status'    => 'Selesai'
            ]);
        } else {
            $data['tot_pr'] = 0;
            Preorder::where('id', $data['preorder_id'])->update([
                'status'    => 'Baru'
            ]);
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {        
        
        $data['tot_pr'] = (int)str_replace('.', '', $data['totaldp']);        
        $data['tot_disc'] = (int)str_replace('.', '', $data['tot_disc']);
        $data['tot_har'] = (int)str_replace('.', '', $data['tot_har']);
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);                

        return $data;
    }
}
