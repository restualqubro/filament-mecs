<?php

namespace App\Filament\Resources\Services\ServiceGaransiResource\Pages;

use App\Filament\Resources\Services\ServiceGaransiResource;
use Filament\Actions;
use App\Models\Service\Garansi;
use App\Models\Service\LogService;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceGaransi extends CreateRecord
{
    protected static string $resource = ServiceGaransiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $row = Garansi::where('invoice_id', $data['invoice_id'])->first();
        $data['service_id'] = $row->invoice->selesai->service->id;
        $data['status'] = 'Baru';
        $data['user_id'] = auth()->id();
        $data['description'] = 'Garansi : Unit Garansi Service';
        
        LogService::create($data);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
