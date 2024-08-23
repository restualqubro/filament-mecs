<?php

namespace App\Filament\Resources\Services\InvoiceResource\Pages;

use App\Filament\Resources\Services\InvoiceResource;
use Filament\Actions;
use App\Models\Service\LogService;
use App\Models\Service\Data;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['totalbayar'] = $data['totalbayar'];
        
        // if ($data['sisa'] > 0 )
        // {
        //     $data['status'] = 'PIUTANG';
        // } else 
        // {
        //     $data['status'] = 'CASH';
        // }

        return dd($data);
        
        // LogService::create([
        //     'service_id'    => $data['service.id'],
        //     'status'        => 'Keluar',
        //     'description'   => 'Unit Telah selesai proses service, Sudah diambil oleh Customer',
        //     'user_id'       => auth()->user()->id
        // ]);
        // Data::where('id', $data['service_id'])->update(['status' => 'Selesai']);

        // return $data;        
    }
}
