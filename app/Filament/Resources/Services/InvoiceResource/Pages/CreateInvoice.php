<?php

namespace App\Filament\Resources\Services\InvoiceResource\Pages;

use App\Filament\Resources\Services\InvoiceResource;
use App\Models\Finance\UtangBonus;
use App\Models\Service\DetailService;
use Filament\Actions;
use App\Models\Service\LogService;
use App\Models\Service\Data;
use App\Models\Service\Selesai;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $selesai = Selesai::where('id', $data['selesai_id'])->first();
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);
        $data['totalbayar'] = $data['totalbayar'];        
        if ($data['sisa'] > 0 )
        {
            $data['status'] = 'PIUTANG';
        } else 
        {
            $data['status'] = 'CASH';
        }               
        LogService::create([
            'service_id'    => $selesai->service->id,
            'status'        => 'Keluar',
            'description'   => 'Unit Telah selesai proses service, Sudah diambil oleh Customer',
            'user_id'       => auth()->user()->id
        ]);
        Data::where('id', $selesai->service->id)->update(['status' => 'Keluar']);                  
        return dd($data);        
    }    

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
